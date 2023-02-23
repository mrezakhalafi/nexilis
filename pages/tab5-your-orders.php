<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

	// ID SHOP GET

	if (!isset($_SESSION['id_shop'])){
		$id_shop = $_GET['shop_code'];
		$_SESSION['id_shop'] = $id_shop;
	}else{
		$id_shop = $_SESSION["id_shop"];
	}

  // ID SHOP CHECK

	if (!isset($id_shop)) {
		die("ID Shop Tidak Diset.");
	}

	// SELECT OPEN ORDER FROM PURCHASE

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $orders = [];
    $querySearch = $_GET['query'];

    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, PRODUCT.*, PRODUCT.CODE AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN PRODUCT ON PURCHASE.PRODUCT_ID = PRODUCT.CODE WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS !=4 AND TRANSACTION_ID IN (SELECT B.TRANSACTION_ID FROM PRODUCT A LEFT JOIN PURCHASE B ON A.CODE = B.PRODUCT_ID LEFT JOIN SHOP S ON
                                S.CODE = A.SHOP_CODE WHERE A.NAME LIKE '%".$querySearch."%' OR USER_LIST.FIRST_NAME LIKE '%".$querySearch."%' GROUP BY B.TRANSACTION_ID) ORDER BY PURCHASE.CREATED_AT DESC");
    
    $query->execute();
    $ordersPurchase = $query->get_result();
    $query->close();

    while ($row = $ordersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $orders[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, POST.*, POST.POST_ID AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN POST ON PURCHASE.POST_ID = POST.POST_ID WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS !=4 AND TRANSACTION_ID IN (SELECT B.TRANSACTION_ID FROM POST A LEFT JOIN PURCHASE B ON A.POST_ID = B.POST_ID LEFT JOIN SHOP S ON
                                S.CODE = A.MERCHANT WHERE A.TITLE LIKE '%".$querySearch."%' OR USER_LIST.FIRST_NAME LIKE '%".$querySearch."%' GROUP BY B.TRANSACTION_ID) ORDER BY PURCHASE.CREATED_AT DESC");

    $query->execute();
    $ordersPost = $query->get_result();
    $query->close();

    while ($row = $ordersPost->fetch_array(MYSQLI_ASSOC))
    {
      $orders[] = $row;
    }

  }else{

    $orders = [];
	
    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, PRODUCT.*, PRODUCT.CODE AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN PRODUCT ON PURCHASE.PRODUCT_ID = PRODUCT.CODE WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS !=4 ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $ordersPurchase = $query->get_result();
    $query->close();

    while ($row = $ordersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $orders[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, POST.*, POST.POST_ID AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN POST ON PURCHASE.POST_ID = POST.POST_ID WHERE MERCHANT_ID = '$id_shop'
                                AND PURCHASE.STATUS !=4 ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $ordersPost = $query->get_result();
    $query->close();

    while ($row = $ordersPost->fetch_array(MYSQLI_ASSOC))
    {
      $orders[] = $row;
    }

    // COUNT ORDER WITH NO DUPLICATE USER

    $query = $dbconn->prepare("SELECT COUNT(*) FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND STATUS
                              != 4 GROUP BY TRANSACTION_ID");
    $query->execute();
    $count = $query->get_result();
    $query->close();

  }

  // SELECT COMPLETED ORDER FROM PURCHASE

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $querySearch = $_GET['query'];
    $completedOrders = [];

    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, PRODUCT.*, PRODUCT.CODE AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN PRODUCT ON PURCHASE.PRODUCT_ID = PRODUCT.CODE WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS = 4 AND TRANSACTION_ID IN (SELECT B.TRANSACTION_ID FROM PRODUCT A LEFT JOIN PURCHASE B ON A.CODE = B.PRODUCT_ID LEFT JOIN SHOP S ON
                                S.CODE = A.SHOP_CODE WHERE A.NAME LIKE '%".$querySearch."%' OR USER_LIST.FIRST_NAME LIKE '%".$querySearch."%' GROUP BY B.TRANSACTION_ID) ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $completedOrdersPurchase = $query->get_result();
    $query->close();

    while ($row = $completedOrdersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $completedOrders[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, POST.*, POST.POST_ID AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN POST ON PURCHASE.POST_ID = POST.POST_ID WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS = 4 AND TRANSACTION_ID IN (SELECT B.TRANSACTION_ID FROM POST A LEFT JOIN PURCHASE B ON A.POST_ID = B.POST_ID LEFT JOIN SHOP S ON
                                S.CODE = A.MERCHANT WHERE A.TITLE LIKE '%".$querySearch."%' OR USER_LIST.FIRST_NAME LIKE '%".$querySearch."%' GROUP BY B.TRANSACTION_ID) ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $completedOrdersPurchase = $query->get_result();
    $query->close();

    while ($row = $completedOrdersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $completedOrders[] = $row;
    }

  }else{

    $completedOrders = [];
	
    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, PRODUCT.*, PRODUCT.CODE AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN PRODUCT ON PURCHASE.PRODUCT_ID = PRODUCT.CODE WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS = 4 ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $completedOrdersPurchase = $query->get_result();
    $query->close();

    while ($row = $completedOrdersPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $completedOrders[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, USER_LIST.*, POST.*, POST.POST_ID AS P_CODE FROM PURCHASE JOIN USER_LIST ON PURCHASE.FPIN = 
                                USER_LIST.F_PIN JOIN POST ON PURCHASE.POST_ID = POST.POST_ID WHERE MERCHANT_ID = '$id_shop'
                                AND STATUS = 4 ORDER BY PURCHASE.CREATED_AT DESC");
    $query->execute();
    $completedOrdersPost = $query->get_result();
    $query->close();

    while ($row = $completedOrdersPost->fetch_array(MYSQLI_ASSOC))
    {
      $completedOrders[] = $row;
    }

  }

  $delivery = 8000;

  // FOR LOOP SORTING DIFFERENT PURCHASE BUT SAME F_PIN

  $arrayResult = array();
  foreach ($orders as $singleOrder){

    if (!isset($arrayResult[$singleOrder['TRANSACTION_ID']])){
      $current = new stdClass();
      $current->TRANSACTION_ID = $singleOrder['TRANSACTION_ID'];
      $current->PRICE = $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
      $current->AMOUNT = $singleOrder['AMOUNT'];
      $current->F_PIN = $singleOrder['FPIN'];
      $current->FIRST_NAME = $singleOrder['FIRST_NAME'];
      $current->THUMB_ID = $singleOrder['THUMB_ID'];
      $current->CREATED_AT = $singleOrder['CREATED_AT'];
      $current->STATUS = $singleOrder['STATUS'];
      $current->P_CODE = $singleOrder['P_CODE'];
      $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
    }else{
      $current = $arrayResult[$singleOrder['TRANSACTION_ID']];
      $current->PRICE += $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
      $current->AMOUNT += $singleOrder['AMOUNT'];
      $current->THUMB_ID .= "|".$singleOrder['THUMB_ID'];
      $current->P_CODE = $current->P_CODE."|".$singleOrder['P_CODE'];
      $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
    }
  }
  $arrayResult = array_values($arrayResult);

  $count = count($arrayResult);

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

  <style>

    /* FOR DISSAPEAR EMPTY IMAGE ICON */

    .galimage[src=""] {
      display:none;
    }

    .form-check-input {
      border: 1px solid #000000 !important;
    }

    button{
      border: none;
      outline: none;
      background-color: transparent;
    }

  </style>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_8.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light">
    <div class="container">

      <?php if ($_GET['src'] == 'od' || $_GET['src'] == 'sm'): ?>
        <a href="tab5-shop-manager.php">
          <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
        </a>
      <?php else: ?>
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black" onclick="goBack()">
      <?php endif; ?>

      <p class="navbar-title-2" data-translate="tab5yourorders-1">Orders</p>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="tab5-your-orders" method="GET" style="width: 95%; border: 1px solid #d1d5db !important">

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
        <img src="../assets/img/tab5/search-black.png" class="search-purple-right">
      </div>
    </div>
  </nav>

  <!-- SECTION OPEN SWITCH -->

  <div class="section-orders-nav-switch">
    <div class="container">
      <div class="row text-center">
        <div class="col-6 col-md-6 col-lg-6 orders-nav-single-left">
          <p class="small-text"><b><span data-translate="tab5yourorders-2">Open Orders</span> (<?= $count ?>)</b></p>
        </div>
        <div class="col-6 col-md-6 col-lg-6 orders-nav-single-right">
          <p class="small-text"><b data-translate="tab5yourorders-3">Completed Orders</b></p>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION OPEN ORDERS -->

  <!-- CHECK IF SHOP HAVE ORDER -->

  <div class="section-open-orders">

  <?php if (count($orders) > 0): ?>

    <!-- FOR LOOP PURCHASE SORTERED BY F_PIN -->

    <?php foreach ($arrayResult as $orderData): 

      $recentOrders = json_decode(json_encode($orderData), true);
        
      $total_price = $recentOrders["PRICE"];
      $order_date = date_create($recentOrders["CREATED_AT"]); ?>

      <a id="link_order_details" href="tab5-order-details.php?transaction_id=<?= $recentOrders['TRANSACTION_ID'] ?>">
      <div class="container orders-list">
        <div class="row orders-header">
          <div class="col-10 col-md-10 col-xl-11">
            <div class="your-orders-title"><b><?= $recentOrders['FIRST_NAME'] ?></b></div>
          </div>
          <div class="col-2 col-md-2 col-xl-1">
              
            <?php if ($recentOrders['STATUS'] == 1): ?>
              <span class="float-end badge rounded-pill orange-badge text-light">
                <span class="small-text" data-translate="tab5yourorders-4">Waiting to be Shipped</span>
              </span>
            <?php elseif ($recentOrders['STATUS'] == 2): ?>
              <span class="float-end badge rounded-pill orange-badge text-light">
                <span class="small-text" data-translate="tab5yourorders-5">Shipped</span>
              </span>
            <?php elseif ($recentOrders['STATUS'] == 3): ?>
              <span class="float-end badge rounded-pill orange-badge text-light">
                <span class="small-text" data-translate="tab5yourorders-6">Out for Delivery</span>
              </span>
              <?php elseif ($recentOrders['STATUS'] == 5): ?>
              <span class="float-end badge rounded-pill orange-badge text-light" style="background-color: darkred">
                <span class="small-text" data-translate="tab5yourorders-19">Declined</span>
              </span>
            <?php endif; ?>

          </div>
        </div>
        <div class="row">
          <div class="col-3 col-md-3 col-xl-2">

            <?php $product_image = explode('|', $recentOrders['THUMB_ID']); ?>

            <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

            <?php $i = 0; ?>

            <?php if (substr($product_image[$i], -3) == "mp4"): ?>

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

              <img src="../images/<?= $product_image[$i] ?>" class="orders-thumbnail galimage" style="border-radius: 10px">

            <?php endif; ?>

            <?php $total_sub_items = explode("|", $recentOrders['P_CODE']); ?>

            <?php if (count($total_sub_items)>1): ?>

            <div style="position: relative">
              <span class="orders-thumbnail" style="position: absolute; border-radius: 10px; background-color:  #000000; opacity: 0.6; margin-top: -75px"></span>
              <p style="position: absolute; margin-top: -50px; margin-left: 25px; color: #FFFFFF">+<?= count($total_sub_items)-1 ?></p>
            </div>

            <?php endif; ?>

          </div>
          <div class="col-7 col-md-7 col-xl-8">
            <div class="orders-text" style="font-weight:600"><?= date_format($order_date,"F d, Y"); ?></div>
            <div class="orders-text" style="font-weight:600">Rp <?= number_format($total_price+$delivery,0,",",",") ?></div>
            <div class="orders-text" style="font-weight:600"><?= $recentOrders['AMOUNT'] ?> <span data-translate="tab5yourorders-8">Items</span></div>
          </div>
          <div class="col-2 col-md-2 col-lg-3">
          <?php if ($recentOrders['STATUS'] == 0): ?>
              <form action="../logics/tab5/accept_orders" method="POST">
                <input id="transaction_id" type="hidden" name="transaction_id" value="<?= $recentOrders['TRANSACTION_ID'] ?>">
                <button type="submit"><img src="../assets/img/tab5/Shop Manager/green-true.png" style="width:25px"></button>
              </form>
              <button onclick="openModal()"><img src="../assets/img/tab5/Shop Manager/red-false.png" style="width:23px; margin-top: 10px;"></button>
            <?php endif; ?>
          </div>
        </div>
      </div>
        <div class="orders-button">
          <div class="container">
            <div class="row">  
              <div class="col-3 col-md-3 col-lg-3"></div>
              <div class="col-9 col-md-9 col-lg-9">
                <p class="orders-small-text float-end"><span data-translate="tab5yourorders-9">Order No.</span> <?= $recentOrders['TRANSACTION_ID'] ?></p>
              </div>
            </div>
          </div>
        </a>
      </div>

    <?php endforeach; ?>

  <?php else: ?>
        
    <p class="text-center small-text mt-5" data-translate="tab5yourorders-10">Anda belum memiliki pesanan masuk.</p>

  <?php endif; ?>

  </div>

  <!-- SECTION COMPLETED ORDERS -->

  <div class="section-completed-orders">
  <?php if (count($completedOrders) > 0) : ?>

    <!-- FOR LOOP SORTING DIFFERENT PURCHASE BUT SAME F_PIN -->

    <?php

    $arrayResult = array();
    foreach ($completedOrders as $singleOrder){

        if(!isset($arrayResult[$singleOrder['TRANSACTION_ID']])){
          $current = new stdClass();
          $current->TRANSACTION_ID = $singleOrder['TRANSACTION_ID'];
          $current->PRICE = $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
          $current->AMOUNT = $singleOrder['AMOUNT'];
          $current->F_PIN = $singleOrder['FPIN'];
          $current->FIRST_NAME = $singleOrder['FIRST_NAME'];
          $current->THUMB_ID = $singleOrder['THUMB_ID'];
          $current->CREATED_AT = $singleOrder['CREATED_AT'];
          $current->P_CODE = $singleOrder['P_CODE'];
          $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
        }else{
          $current = $arrayResult[$singleOrder['TRANSACTION_ID']];
          $current->PRICE += $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
          $current->AMOUNT += $singleOrder['AMOUNT'];
          $current->THUMB_ID .= "|".$singleOrder['THUMB_ID'];
          $current->P_CODE = $current->P_CODE."|".$singleOrder['P_CODE'];
          $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
        }
    }
    $arrayResult = array_values($arrayResult);

    ?>

    <!-- FOR LOOP PURCHASE SORTERED BY F_PIN -->

    <?php foreach($arrayResult as $orderData) : 

      $recentOrders = json_decode(json_encode($orderData), true);
        
      $total_price = $recentOrders["PRICE"];
      $order_date = date_create($recentOrders["CREATED_AT"]); ?>

      <a href="tab5-order-details.php?transaction_id=<?= $recentOrders['TRANSACTION_ID'] ?>">
      <div class="container orders-list">
        <div class="row orders-header">
          <div class="col-10 col-md-10 col-xl-11">
            <div class="your-orders-title"><b><?= $recentOrders['FIRST_NAME'] ?></b></div>
          </div>
          <div class="col-2 col-md-2 col-xl-1">
            <span class="float-end badge rounded-pill orange-badge text-light" style="background-color: darkgreen">
              <span class="small-text" data-translate="tab5yourorders-7">Completed</span>
            </span>
          </div>
        </div>
        <div class="row">
          <div class="col-3 col-md-3 col-xl-2">

            <?php $product_image = explode('|', $recentOrders['THUMB_ID']); ?>

            <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

            <?php $i = 0; ?>

            <?php if (substr($product_image[$i], -3) == "mp4"): ?>

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

              <img src="../images/<?= $product_image[$i] ?>" class="orders-thumbnail galimage" style="border-radius: 10px">

            <?php endif; ?>

            <?php $total_sub_items = explode("|", $recentOrders['P_CODE']); ?>

            <?php if (count($total_sub_items)>1): ?>

            <div style="position: relative">
              <span class="orders-thumbnail" style="position: absolute; background-color:  #000000; opacity: 0.6; margin-top: -75px"></span>
              <p style="position: absolute; margin-top: -50px; margin-left: 25px; color: #FFFFFF">+<?= count($total_sub_items)-1 ?></p>
            </div>
            
            <?php endif; ?>

          </div>
          <div class="col-9 col-md-9 col-xl-10">
            <div class="orders-text" style="font-weight:600"><?= date_format($order_date,"F d, Y"); ?></div>
            <div class="orders-text" style="font-weight:600">Rp <?= number_format($total_price+$delivery,0,",",",") ?></div>
            <div class="orders-text" style="font-weight:600"><?= $recentOrders['AMOUNT'] ?> <span data-translate="tab5yourorders-8">Items</span></div>
          </div>
        </div>
      </div>
        <div class="orders-button">
          <div class="container">
            <div class="row">  
              <div class="col-3 col-md-3 col-lg-3"></div>
              <div class="col-9 col-md-9 col-lg-9">
                <p class="orders-small-text float-end"><span data-translate="tab5yourorders-9">Order No. </span><?= $recentOrders['TRANSACTION_ID'] ?></p>
              </div>
            </div>
          </div>
        </a>
      </div>

    <?php endforeach; ?>

  <?php else: ?>
        
    <p class="text-center small-text mt-5" data-translate="tab5yourorders-11">Anda belum memiliki pesanan yang telah selesai.</p>

  <?php endif; ?>

  </div>

  <!-- DECLINE MODAL -->

  <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
      <div class="modal-content" style="height: 100%">
      <div class="row d-flex justify-content-center">
        <hr class="shop-modal-line">
      </div>
      <div class="modal-body">
        <div class="container small-text">
          <p style="font-size: 12px" data-translate="tab5yourorders-18">Decline Reason: </p>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option1" checked>
            <label class="form-check-label" for="option1" style="margin-left: 5px" data-translate="tab5yourorders-12">
              Out of Stock
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option2">
            <label class="form-check-label" for="option2" style="margin-left: 5px" data-translate="tab5yourorders-13">
              Buyer requested due to change of mind
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option3">
            <label class="form-check-label" for="option3" style="margin-left: 5px" data-translate="tab5yourorders-14">
              Buyer requested due to change in payment method/product
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option4">
            <label class="form-check-label" for="option4" style="margin-left: 5px" data-translate="tab5yourorders-15">
              Buyer unreachable for order validation
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option5">
            <label class="form-check-label" for="option5" style="margin-left: 5px" data-translate="tab5yourorders-16">
              Buyer shipping address is incorrect/incomplete
            </label>
          </div>
          <div class="btn-continue-ads text-center" onclick="declineOrders()" data-translate="tab5yourorders-17">Decline Orders</div>
        </div>
      </div>
    </div>
  </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

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

  // SCRIPT NAV SWITCH

  $(".section-completed-orders").hide();

  $(".orders-nav-single-right").click(function(){
    $(".section-completed-orders").show();
    $(".section-open-orders").hide();
    $(".orders-nav-single-right").css({"border-bottom": "2px solid #000000"});
    $(".orders-nav-single-left").css({"border-bottom": "none"});

    position = 1;
    localStorage.setItem("position", position);

  });

  $(".orders-nav-single-left").click(function(){
    $(".section-completed-orders").hide();
    $(".section-open-orders").show();
    $(".orders-nav-single-left").css({"border-bottom": "2px solid #000000"});
    $(".orders-nav-single-right").css({"border-bottom": "none"});

    position = 0;
    localStorage.setItem("position", position);

  });

  if (localStorage.getItem('position') == 1){
    $('.section-completed-orders').show();
    $('.section-open-orders').hide();
    $('.orders-nav-single-right').css({'border-bottom': '2px solid #000000'});
    $('.orders-nav-single-left').css({'border-bottom': 'none'});
  };

  // SCRIPT SEARCH

  $('#searchBar').attr('style','display:none !important');

  $(".search-purple-right").click(function(){
    $('.navbar-title-2').hide();
    $('#searchBar').attr('style','display:block !important');
  });

  // FOR SHOW SEARCH BAR AGAIN WHILE REFRESHED

  <?php

  if (isset($_GET['query'])){
    echo("
    $('.navbar-title-2').hide();
    $('#searchBar').attr('style','display:block !important');

    $('#query').val(localStorage.getItem('search_keyword'));
    $('#delete-query').removeClass('d-none');

    if(localStorage.getItem('position') == 1){
      $('.section-completed-orders').show();
      $('.section-open-orders').hide();
      $('.orders-nav-single-right').css({'border-bottom': '2px solid #000000'});
      $('.orders-nav-single-left').css({'border-bottom': 'none'});
    }"); 
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
    window.location = 'tab5-your-orders.php';
  })

  $('#query').keyup(function (){

    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })
  
  // OPEN MODAL AND DISABLE A HREF

  function openModal(){
    $('#declineModal').modal("show");
    
    $("a").click(function() {
      return false;
    });
  }

  // WHEN CLOSE MODAL ACTIVE A HREF

  $('#declineModal').on('hidden.bs.modal', function () {
    $("a").prop("onclick", null).off("click");
  })

  // FUNCTION DECLINE ORDERS

  function declineOrders(){

    var formData = new FormData();
    var transaction_id = $('#transaction_id').val();

    formData.append('transaction_id', transaction_id);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function (){

      if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
        console.log(xmlHttp.responseText);
        if(xmlHttp.responseText=="Berhasil"){
        window.location.href = "/nexilis/pages/tab5-your-orders";
        }else{
          console.log("Gagal nih");
        }
      }
    }

    xmlHttp.open("post", "../logics/tab5/decline_orders");
    xmlHttp.send(formData);
  }

  // GO BACK BUTTON

  function goBack(){
  if (window.Android){
    window.Android.closeView();
  }else{
    window.history.back();
  }
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