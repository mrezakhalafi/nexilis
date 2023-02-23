<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
  session_start();

  // ID SHOP GET

	if (isset($_GET['id'])){
		$id_shop = $_GET['id'];
	}else if(isset($_SESSION['id_shop'])){
		$id_shop = $_SESSION["id_shop"];
	}else if(isset($_COOKIE['id_shop'])){
		$id_shop = $_COOKIE['id_shop'];
	}

	// CHECK SHOP ID

	if (!isset($id_shop)) {
		die("ID Shop Tidak Diset.");
	}

	//SELECT SHOP DATA ACTIVE

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $query = $_GET['query'];

    $query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' 
                              AND NAME LIKE '%$query%' AND IS_SHOW = 1 AND IS_DELETED = 0
                              ORDER BY CREATED_DATE DESC");
    $query->execute();
    $listing_data = $query->get_result();
    $query->close();

  }else{

    $query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 1 
                                AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
    $query->execute();
    $listing_data = $query->get_result();
    $query->close();
  }

  //SELECT SHOP DATA ARCHIVED

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $query = $_GET['query'];

    $query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' 
                                AND PRODUCT.NAME LIKE '%$query%' AND IS_SHOW = 0 AND IS_DELETED = 0
                                ORDER BY CREATED_DATE DESC");
    $query->execute();
    $archived_data = $query->get_result();
    $query->close();
  
  }else{

	$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 0 
                              AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
	$query->execute();
	$archived_data = $query->get_result();
	$query->close();

  }

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
  <img src="../assets/img/lbackground_13.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2 navbar-listing-title" data-translate="tab5listing-1">Your Listing</p>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="tab5-listing" method="GET" style="width: 95%; border: 1px solid #d1d5db !important">

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
      <div class="navbar-brand pt-2 navbar-brand-listing">
        <a href="tab5-upload-listing.php">
          <img src="../assets/img/tab5/add-black.png" class="navbar-listing-add">
        </a>
        <img src="../assets/img/tab5/search-black.png" class="navbar-listing-search">
      </div>
  </div>
  </nav>

  <!-- SECTION LISTING SWITCH -->

  <div class="section-listing-nav-switch">
    <div class="container">
      <div class="row text-center">
        <div class="col-6 col-md-6 col-lg-6 listing-nav-single-left">
          <p class="small-text">
            <b data-translate="tab5listing-2">Active</b>
          </p>
        </div>
        <div class="col-6 col-md-6 col-lg-6 listing-nav-single-right">
          <p class="small-text">
            <b data-translate="tab5listing-3">Archived</b>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION LISTING PRODUCT -->

  <div class="section-listing-active">
    <div class="container">
      <div class="row gx-0">

        <!-- CHECK IF SHOP HAVE LISTING -->

        <?php if (mysqli_num_rows($listing_data)>0) : ?>

          <!-- LOOP SHOP LISTING -->

          <?php foreach ($listing_data as $resultListing) : ?>

          <div class="col-6 col-md-6 col-md-6 col-lg-6 shadow-sm col-listing-product">
            <div class="single-listing-product">
              <label for="myCheck<?= $resultListing['ID'] ?>">
                <div class="form-check form-check-listing" style="padding-left: 0 !important">
                  <input class="form-check-input check-form-input-listing" type="checkbox" data-code-product="<?= $resultListing['CODE'] ?>" value="" id="myCheck<?= $resultListing['ID'] ?>">
                  
                  <?php $product_image = explode('|', $resultListing['THUMB_ID']); ?>
                  
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
                      <img src="../images/<?= $product_image_video ?>" class="listing-image" style="border-radius: 10px">
                    <?php else: ?>
                      <video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="listing-image"></video>
                    <?php endif; ?>

                  <?php else: ?>

                    <img src="../images/<?= $product_image[$i] ?>" class="listing-image" style="border-radius: 10px">
                  
                  <?php endif; ?>

                  <img src="../assets/img/tab5/Settings-(White).png" class="listing-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                  
                  <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                    <li><a href="tab5-edit-listing.php?id=<?= $resultListing['CODE'] ?>" class="dropdown-item" data-translate="tab5listing-10">Edit</a></li>
                    
                    <form action="../logics/tab5/delete_listing.php?id=<?= $resultListing['CODE'] ?>" method="POST">  
                      <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5listing-11">Delete</button></li>
                    </form>
                  
                  </ul>
                  
                  <div class="row listing-product-desc">
                    <span class="small-text listing-product-title">
                      <b><?= $resultListing["NAME"] ?></b>
                    </span>
                    <div class="row listing-product-desc-row">
                      <div class="col-6 col-md-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                        <span class="smallest-text text-grey">
                          <?= $resultListing["QUANTITY"] ?> <span data-translate="tab5listing-4">in stock</span>
                        </span>
                      </div>
                      <div class="col-6 col-md-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                        <span class="smallest-text text-grey">
                          Rp <?= number_format($resultListing['PRICE'],0,",",",") ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </label>
            </div>
          </div>

          <?php endforeach; ?>
        <?php else: ?>

        <p class="text-center small-text mt-5" data-translate="tab5listing-5">Anda belum memiliki produk.</p>

        <?php endif; ?>

        </div>
      </div> 
    </div>
  </div>

  <div class="section-listing-archived">
    <div class="container">
      <div class="row gx-0">

      <!-- CHECK IF SHOP HAVE LISTING -->

      <?php if (mysqli_num_rows($archived_data)>0) : ?>

        <!-- LOOP SHOP LISTING -->

        <?php foreach ($archived_data as $archivedListing) : ?>

        <div class="col-6 col-md-6 col-lg-6 shadow-sm col-listing-product">
          <div class="single-listing-product">
            <label for="myCheck<?= $archivedListing['ID'] ?>">
              <div class="form-check form-check-listing" style="padding-left: 0 !important">
                <input class="form-check-input check-form-input-listing" type="checkbox" value="" data-code-product="<?= $archivedListing['CODE'] ?>" id="myCheck<?= $archivedListing['ID'] ?>">
                
                <?php $product_image = explode('|', $archivedListing['THUMB_ID']); ?>
                
                <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

                <?php $i = 0; ?>

                <?php if (substr($product_image[$i], -3) == "mp4"): ?>

                  <img src="../images/<?= $product_image[$i+1] ?>" class="listing-image" style="border-radius: 10px">

                <?php else: ?>

                  <img src="../images/<?= $product_image[$i] ?>" class="listing-image" style="border-radius: 10px">
                
                <?php endif; ?>             
                
                <img src="../assets/img/tab5/Settings-(White).png" class="listing-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                
                <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                  <li><a href="tab5-edit-listing.php?id=<?= $archivedListing['CODE'] ?>" class="dropdown-item">Edit</a></li>
                  <li><a onclick="deleteListing()" style="color:brown" class="dropdown-item">Delete</a></li>
                </ul>
                
                <div class="row listing-product-desc">
                  <span class="small-text listing-product-title">
                    <b><?= $archivedListing["NAME"] ?></b>
                  </span>
                  <div class="row listing-product-desc-row">
                    <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                      <span class="smallest-text text-grey"><?= $archivedListing["QUANTITY"] ?> <span  data-translate="tab5listing-4">in stock</span></span>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                      <span class="smallest-text text-grey">
                        Rp <?= number_format($archivedListing['PRICE'],0,",",",") ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </label>
          </div>
        </div>
        
        <?php endforeach; ?>
        <?php else: ?>

        <p class="text-center small-text mt-5" data-translate="tab5listing-6">Tidak ada produk yang di-arhived.</p>

        <?php endif; ?>
      
      </div>
    </div>
  </div>

  <input type="hidden" class="checked_product" id="checked_product" name="checked_product" value="">
 
  <!-- Modal -->

  <!-- Dinyalakan Jika Ada Data Backend -->

  <div class="modal fade" id="congratsListingModal" tabindex="-1" aria-labelledby="successListingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
      <div class="modal-content" style="height: 100%">
      <div class="row d-flex justify-content-center">
        <hr class="shop-modal-line">
      </div>
      <div class="modal-body text-center">
        <img src="../assets/img/tab5/Congratulations-Success.png" class="success-ads-image">
        <div class="text-purple"><b data-translate="tab5listing-7">Success!</b></div>
        <div class="small-text text-grey" data-translate="tab5listing-8">Your listing has been successfuly updated.</div>
        <div class="btn-continue-ads" data-bs-dismiss="modal" data-translate="tab5listing-9">Continue</div>
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

    if (localStorage.lang == 1){
      $('#query').attr('placeholder','Pencarian...');
    }

    $('body').show();
  });
  
  // NAV SWITCH SCRIPT

  $(".section-listing-archived").hide();

  $(".listing-nav-single-right").click(function() {
    $(".section-listing-archived").show();
    $(".section-listing-active").hide();
    $(".listing-nav-single-right").css({"border-bottom": "2px solid #000000"});
    $(".listing-nav-single-left").css({"border-bottom": "none"});

    position = 1;
    localStorage.setItem("position", position);
  });

  $(".listing-nav-single-left").click(function() {
    $(".section-listing-archived").hide();
    $(".section-listing-active").show();
    $(".listing-nav-single-left").css({"border-bottom": "2px solid #000000"});
    $(".listing-nav-single-right").css({"border-bottom": "none"});

    position = 0;
    localStorage.setItem("position", position);
  });

  if (localStorage.getItem('position') == 1){
    $('.section-listing-archived').show();
    $('.section-listing-active').hide();
    $('.listing-nav-single-right').css({'border-bottom': '2px solid #000000'});
    $('.listing-nav-single-left').css({'border-bottom': 'none'});
  };

  // SHOW MODAL AFTER SUCCESS UPLOAD LISTING

  <?php

    if ($_GET['success'] =='true'){

    echo('$(function() {
      $("#congratsListingModal").modal("show");
      });');
    }

    // FOR NOT DUPLICATE MODAL

    echo "history.pushState(null, null, '/nexilis/pages/tab5-listing');";

  ?>

  // SCRIPT SEARCH

  $('#searchBar').attr('style','display:none !important');

  $(".navbar-listing-search").click(function() {
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

      if (localStorage.getItem('position') == 1){
        $('.section-listing-archived').show();
        $('.section-listing-active').hide();
        $('.listing-nav-single-right').css({'border-bottom': '2px solid #000000'});
        $('.listing-nav-single-left').css({'border-bottom': 'none'});
      }");
    }

  ?>

  // FUNCTION SAVE SEARCH

  $('#query').on('change', function() {
    localStorage.setItem("search_keyword", this.value);
  });
  
  // FUNCTION X ON SEARCH

  $("#delete-query").click(function () {
    $('#query').val('');
    // localStorage.setItem("search_keyword", "");
    // $('#delete-query').addClass('d-none');
    window.location = 'tab5-listing.php'
  })

  $('#query').keyup(function () {

    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })

  // RESET UPLOAD LISTING DATA

	window.localStorage.removeItem('title_listing');
	window.localStorage.removeItem('desc_listing');
	window.localStorage.removeItem('price_listing');
	window.localStorage.removeItem('stock_listing');
	window.localStorage.removeItem('weight_listing');
	window.localStorage.removeItem('category_listing_name');
	window.localStorage.removeItem('category_listing_id');
	window.localStorage.removeItem('variation_listing_temp');
	window.localStorage.removeItem('variation_listing_code');
  window.localStorage.removeItem('get_listing_media_temp_1');

  // CHECKBOX TAGGED PRODUCT

  $(document).on("change",":checkbox", function(e){

    var array_checked = $('.checked_product').val();

    if (this.checked){
      $('.checked_product').val($(this).val());

      if (array_checked.length>0){
        array_checked = array_checked + "|" + $(this).data("code-product");
      }else{
        array_checked = $(this).data("code-product");
      }

      $('.checked_product').val(array_checked);

      console.log(array_checked);

      // SAVE TO LOCAL STORAGE

      localStorage.setItem('tagged_product', array_checked);

    }else{

      var array_checked = $('.checked_product').val();

      if (array_checked.includes("|")){

        var a = array_checked.replace("|"+$(this).data("code-product"),"");
      
        var b = a.replace($(this).data("code-product")+"|","");

        $(".checked_product").val(b);   
      }else{
        array_checked = array_checked.replace($(this).data("code-product"),"");

        $('.checked_product').val(array_checked);
      }

      var array_checked = $('.checked_product').val();

      $('.checked_product').val(array_checked);

      console.log(array_checked);

      // SAVE TO LOCAL STORAGE

      localStorage.setItem('tagged_product', array_checked);
      
    }
  });

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