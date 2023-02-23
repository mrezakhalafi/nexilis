<?php

  // KONEKSI

  include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
  $dbconn = paliolite();
  session_start();

  // GET USER FROM SESSION

  if (!isset($_SESSION['user_f_pin'])){
		$id_user = $_GET['f_pin'];
		$_SESSION['user_f_pin'] = $id_user;
	}else{
		$id_user = $_SESSION["user_f_pin"];
	}

  // CHECK USER

  if (!isset($id_user)) {
    die("ID User Tidak Diset.");
  }

  // SELECT ORDER FROM PURCHASE

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $querySearch = $_GET['query'];
    $userOrders = [];
    
    $query = $dbconn->prepare("SELECT PURCHASE.*, SHOP.*, PRODUCT.THUMB_ID, PRODUCT.CODE AS P_CODE, PURCHASE.PRICE AS TOTAL_PRICE
                                FROM PURCHASE LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = SHOP.CODE 
                                LEFT JOIN PRODUCT ON PURCHASE.PRODUCT_ID = PRODUCT.CODE 
                                WHERE FPIN ='".$id_user."' AND TRANSACTION_ID IN (SELECT 
                                B.TRANSACTION_ID FROM PRODUCT A LEFT JOIN PURCHASE B ON A.CODE = 
                                B.PRODUCT_ID LEFT JOIN SHOP S ON S.CODE = A.SHOP_CODE WHERE A.NAME 
                                LIKE '%".$querySearch."%' OR S.NAME LIKE '%".$querySearch."%' AND PURCHASE.PRODUCT_ID IS NOT NULL GROUP BY 
                                B.TRANSACTION_ID) ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $userOrdersPurchase = $query->get_result();
    $query->close();

    while ($row = $userOrdersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $userOrders[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, SHOP.*, POST.THUMB_ID, POST.POST_ID AS P_CODE, PURCHASE.PRICE AS TOTAL_PRICE
                                FROM PURCHASE LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = SHOP.CODE 
                                LEFT JOIN POST ON PURCHASE.POST_ID = POST.POST_ID 
                                WHERE FPIN ='".$id_user."' AND TRANSACTION_ID IN (SELECT 
                                B.TRANSACTION_ID FROM POST A LEFT JOIN PURCHASE B ON A.POST_ID = 
                                B.POST_ID LEFT JOIN SHOP S ON S.CODE = A.MERCHANT WHERE A.TITLE 
                                LIKE '%".$querySearch."%' OR S.NAME LIKE '%".$querySearch."%' AND PURCHASE.POST_ID IS NOT NULL GROUP BY 
                                B.TRANSACTION_ID) ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $userOrdersPost = $query->get_result();
    $query->close();

    while ($row = $userOrdersPost->fetch_array(MYSQLI_ASSOC))
    {
      $userOrders[] = $row;
    }

  }else{

    $userOrders = [];

    $query = $dbconn->prepare("SELECT PURCHASE.*, SHOP.*, PRODUCT.THUMB_ID, PRODUCT.CODE AS 
                                P_CODE, PURCHASE.PRICE AS TOTAL_PRICE FROM PURCHASE LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = 
                                SHOP.CODE LEFT JOIN PRODUCT ON PURCHASE.PRODUCT_ID = 
                                PRODUCT.CODE WHERE FPIN ='".$id_user."' AND PURCHASE.PRODUCT_ID IS NOT NULL ORDER BY 
                                PURCHASE.CREATED_AT DESC");
    $query->execute();
    $userOrdersPurchase = $query->get_result();
    $query->close();

    while ($row = $userOrdersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $userOrders[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, SHOP.*, POST.THUMB_ID AS THUMB_ID, POST.POST_ID AS 
                  P_CODE, PURCHASE.PRICE AS TOTAL_PRICE FROM PURCHASE LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = 
                  SHOP.CODE LEFT JOIN POST ON PURCHASE.POST_ID = 
                  POST.POST_ID WHERE FPIN ='".$id_user."' AND PURCHASE.POST_ID IS NOT NULL ORDER BY 
                  PURCHASE.CREATED_AT DESC");
    $query->execute();
    $userOrdersPost = $query->get_result();
    $query->close();

    while ($row = $userOrdersPost->fetch_array(MYSQLI_ASSOC))
    {
      $userOrders[] = $row;
    }
  }

  // DUMMY DELIVERY PRICE

  $delivery = 8000;

  // SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Nexilis Sports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href="../assets/css/tab5-collection-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_11.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light mb-4" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; border-bottom: 1px solid #d1d5db">
    <div class="container">

      <?php if ($_GET['src'] == 'receipt'): ?>
        <a href="tab5.php">
          <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
        </a>
      <?php else: ?>
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white" onclick="goBack()">
      <?php endif; ?>

      <p class="navbar-title" data-translate="tab5orders-1" style="color: #000000">Orders</p>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="tab5-orders" method="GET" style="border: 1px solid #8a8a8a; width: 95%;">

          <?php
            $query = "";
            if (isset($_REQUEST['query'])) {
              $query = $_REQUEST['query'];
              
            }
          ?>

          <input id="query" placeholder="Search" type="text" class="search-query" name="query">
          <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
          <img id="voice-search" onclick="voiceSearch()" src="../assets/img/icons/Voice-Command-(Black).png">
        </form>
      </div>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img src="../assets/img/tab5/search-black.png" class="search-white-right">
      </div>
  </div>
  </nav>

  <!-- SECTION ORDERS -->

  <?php if (count($userOrders) > 0): ?>

  <!-- FOR LOOP SORTING DIFFERENT PURCHASE BUT SAME F_PIN -->

  <?php

  $arrayResult = array();

  foreach ($userOrders as $singleOrder){

    if (!isset($arrayResult[$singleOrder['TRANSACTION_ID']])){
        $current = new stdClass();
        $current->TRANSACTION_ID = $singleOrder['TRANSACTION_ID'];
        $current->PRICE = $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
        $current->AMOUNT = $singleOrder['AMOUNT'];
        $current->F_PIN = $singleOrder['FPIN'];
        $current->NAME = $singleOrder['NAME'];
        $current->IS_VERIFIED = $singleOrder['IS_VERIFIED'];
        $current->THUMB_ID = $singleOrder['THUMB_ID'];
        $current->CREATED_AT = $singleOrder['CREATED_AT'];
        $current->STATUS = $singleOrder['STATUS'];
        $current->P_CODE = $singleOrder['P_CODE'];
        $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;

    }else{
        $current = $arrayResult[$singleOrder['TRANSACTION_ID']];
        $current->AMOUNT += $singleOrder['AMOUNT'];
        $current->PRICE += $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
        $current->THUMB_ID .= "|".$singleOrder['THUMB_ID'];
        $current->P_CODE = $current->P_CODE."|".$singleOrder['P_CODE'];
        $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
    }
  }

  $arrayResult = array_values($arrayResult);
  ?>

  <!-- FOR LOOP PURCHASE SORTERED BY F_PIN -->
    
  <?php foreach ($arrayResult as $resultOrders):

    $recentOrders = json_decode(json_encode($resultOrders), true);

    $total_price = $recentOrders['PRICE'];
    $order_date = date_create($recentOrders['CREATED_AT']);
    
  ?>

  <div class="section-orders">
    <a href="tab5-receipt.php?id=<?= $recentOrders['TRANSACTION_ID'] ?>">
      <div class="container orders-list">
        <div class="row orders-header">
        
          <?php if ($recentOrders['IS_VERIFIED'] == 1): ?>
            <div class="col-8 col-md-8 col-lg-6">
              <img src="../assets/img/tab5/verified-black.png" class="verified" style="width:20px">
              <div class="orders-shop-title" style="margin-left:24px; margin-top:-20px"><b><?= $recentOrders["NAME"] ?></b></div>
            </div>
          <?php else: ?>
            <div class="col-6 col-md-6 col-lg-5" style="margin-left: 40px">
              <div class="orders-shop-title"><b><?= $recentOrders["NAME"] ?></b></div>
            </div>
          <?php endif; ?>
          
          <div class="col-4 col-md-4 col-lg-6">

              <?php if (($recentOrders['STATUS'] == 1) || ($recentOrders['STATUS'] == 0)): ?>
                <span class="float-end badge rounded-pill orange-badge text-light">
                  <span class="small-text" data-translate="tab5orders-2">Processing</span>
                </span>
              <?php elseif($recentOrders['STATUS'] == 2): ?>
                <span class="float-end badge rounded-pill orange-badge text-light">
                  <span class="small-text" data-translate="tab5orders-3">Shipped</span>
                </span>
              <?php elseif($recentOrders['STATUS'] == 3): ?>
                <span class="float-end badge rounded-pill orange-badge text-light">
                  <span class="small-text" data-translate="tab5orders-4">Out of Delivery</span>
                </span>
              <?php elseif($recentOrders['STATUS'] == 4): ?>
                <span class="float-end badge rounded-pill orange-badge text-light" style="background-color: darkgreen">
                  <span class="small-text" data-translate="tab5orders-5">Delivered</span>
                </span>
              <?php elseif($recentOrders['STATUS'] == 5): ?>
                <span class="float-end badge rounded-pill orange-badge text-light" style="background-color: darkred">
                  <span class="small-text" data-translate="tab5orders-10">Declined</span>
                </span>
              <?php endif; ?>							

          </div>
        </div>
        <div class="row">
          <div class="col-3 col-md-3 col-lg-2">

            <?php $product_image = explode('|', $recentOrders['THUMB_ID']); ?>

            <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

            <?php $i = 0; ?>

            <?php if(substr($product_image[$i], -3) == "mp4"): ?>

              <!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

              <?php 
              while (substr($product_image[$i], -3) == "mp4"):
                $product_image_video = $product_image[$i+1];
                $i++;
              endwhile; 
              ?>

              <!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

              <?php if ($product_image_video): ?>
                <img src="../images/<?= $product_image_video ?>" class="orders-thumbnail" style="border-radius: 10px">
              <?php else: ?>
                <video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="orders-thumbnail"></video>
              <?php endif; ?>

            <?php else: ?>

              <img src="../images/<?= $product_image[$i] ?>" class="orders-thumbnail" style="border-radius: 10px">

            <?php endif; ?>

            <?php $total_sub_items = explode("|", $recentOrders['P_CODE']); ?>

            <?php if (count($total_sub_items)>1): ?>
            <div style="position: relative">
              <span class="orders-thumbnail" style="position: absolute; border-radius: 10px; background-color:  #000000; opacity: 0.6; margin-top: -75px"></span>
              <p style="position: absolute; margin-top: -50px; margin-left: 25px; color: #FFFFFF">+<?= count($total_sub_items)-1 ?></p>
            </div>
            <?php endif; ?>

          </div>
          <div class="col-9 col-md-9 col-lg-10">
            <div class="text-grey smallest-text">
              <span data-translate="tab5orders-6">Order </span>#<?= $recentOrders['TRANSACTION_ID'] ?>
            </div>
            <div class="orders-text" style="font-weight:600">
              <?= date_format($order_date,"F d, Y"); ?>
            </div>
            <div class="orders-text" style="font-weight:600">
              Rp <?= number_format($total_price + $delivery,0,",",",") ?>
            </div>
            <div class="orders-text" style="font-weight:600">
              <?= $recentOrders['AMOUNT'] ?> <span data-translate="tab5orders-7">Items<span>
            </div>
          </div>
        </div>
      </div>
      <div class="orders-button">
        <div class="container">
          <div class="row">  
            <div class="col-5 col-md-5 col-lg-5">
              <p class="orders-small-text" data-translate="tab5orders-8" style="font-weight:600">Help with order</p>
            </div>
            <div class="col-7 col-md-7 col-lg-7">
              <p class="orders-small-text float-end" style="font-weight:600">Anteraja - 1-000-195-108-5070</p>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <?php endforeach; ?>
  <?php else: ?>

  <p class="text-center small-text mt-5" data-translate="tab5orders-9">Anda belum memesan produk apapun.</p>

  <?php endif; ?>

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

    if(localStorage.lang == 1){
      $('#query').attr('placeholder','Pencarian...');
    }

    $('body').show();
  });

  // SCRIPT SEARCH

  $('#searchBar').attr('style','display:none !important');

  $(".search-white-right").click(function(){
    $('.navbar-title').hide();

    $('#searchBar').attr('style','display:block !important');
  });

  <?php

  if (isset($_GET['query'])){
    echo "
    $('.navbar-title').hide();
    $('#searchBar').attr('style','display:block !important');

    $('#query').val(localStorage.getItem('search_keyword'));
    $('#delete-query').removeClass('d-none');
    ";
  }

  ?>

  // FUNCTION SAVE SEARCH

  $('#query').on('change', function(){
    localStorage.setItem("search_keyword", this.value);
  });

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function (){
    $('#query').val('');
    // localStorage.setItem("search_keyword", "");
    // $('#delete-query').addClass('d-none');
    window.location = 'tab5-orders.php';
  })

  $('#query').keyup(function (){
    console.log('is typing: ' + $(this).val());
    if ($(this).val() != '') {
        $('#delete-query').removeClass('d-none');
    } else {
        $('#delete-query').addClass('d-none');
    }
  })

  // GO BACK BUTTON

  function goBack(){
    window.history.back();
  }

  // FUNCTION VOICE SEARCH

  function voiceSearch(){
    Android.toggleVoiceSearch();
  }

  function submitVoiceSearch(searchQuery){
    $('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
  }

</script>
</html>