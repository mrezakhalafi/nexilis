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
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_14.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; border-bottom: 1px solid #d1d5db">
    <div class="container">

        <?php if($_GET['source'] == "lv"): ?>
          <a href="tab5-live-stream.php">
              <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
          </a>
        <?php else: ?>
          <a href="tab5.php">
              <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
          </a>
        <?php endif; ?>

    <p class="navbar-title" data-translate="tab5coupons-1" style="color: #000000">Coupons</p>
    <div class="navbar-brand pt-2 navbar-brand-slot">
      <img src="" class="navbar-img-slot">
    </div>
  </div>
  </nav>

  <!-- SECTION SHOP VOUCHER -->

  <div class="section-shop-voucher container">
    <span class="voucher-text" data-translate="tab5coupons-2">Shop Vouchers</span>
    <div class="row gx-0">
      <div class="col-4 col-md-2 col-lg-2">
        <div class="single-voucher">
          <img src="../assets/img/tab5/Featured-Voucher-(Orange)-2.png" class="voucher-image">
            <b class="voucher-title">All table & chairs.</b>
            <p class="voucher-discount"><b>25% OFF</b></p>
            <p class="voucher-days" data-translate="tab5coupons-3">5 days left</p>
            <b class="voucher-claimed" data-translate="tab5coupons-7">CLAIMED</b>
        </div>
      </div>
      <div class="col-4 col-md-2 col-lg-2">
        <div class="single-voucher">
          <img src="../assets/img/tab5/Featured-Voucher-(Orange)-2.png" class="voucher-image">
            <b class="voucher-title">All table & chairs.</b>
            <p class="voucher-discount"><b>25% OFF</b></p>
            <p class="voucher-days" data-translate="tab5coupons-3">5 days left</p>
            <b class="voucher-claimed" data-translate="tab5coupons-7">CLAIMED</b>
        </div>
      </div>
      <div class="col-4 col-md-2 col-lg-2">
        <div class="single-voucher">
          <img src="../assets/img/tab5/Featured-Voucher-(Orange)-2.png" class="voucher-image">
            <b class="voucher-title">All table & chairs.</b>
            <p class="voucher-discount"><b>25% OFF</b></p>
            <p class="voucher-days" data-translate="tab5coupons-3">5 days left</p>
            <b class="voucher-claimed" data-translate="tab5coupons-7">CLAIMED</b>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION FREE DELIVERY -->

  <div class="section-free-delivery container">
    <span class="voucher-text" data-translate="tab5coupons-4">Free Delivery</span>
    <div class="row gx-0">
      <div class="col-6 col-md-4 col-lg-3 col-xl-2">
        <div class="single-free-delivery">
          <img src="../assets/img/tab5/Free-Shipping-Voucher-3.png" class="voucher-image">
          <b class="free-delivery-title">FREE SHIPPING</b>
          <p class="free-delivery-desc" data-translate="tab5coupons-5">On all order above</p>
          <p class="free-delivery-discount">Rp.100.000</p>
          <p class="free-delivery-days" data-translate="tab5coupons-6">Expiring in 5 days</p>
          <b class="free-delivery-claimed" data-translate="tab5coupons-7">CLAIMED</b>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-3 col-xl-2">
        <div class="single-free-delivery">
          <img src="../assets/img/tab5/Free-Shipping-Voucher-3.png" class="voucher-image">
          <b class="free-delivery-title">FREE SHIPPING</b>
          <p class="free-delivery-desc" data-translate="tab5coupons-5">On all order above</p>
          <p class="free-delivery-discount">Rp.100.000</p>
          <p class="free-delivery-days" data-translate="tab5coupons-6">Expiring in 5 days</p>
          <b class="free-delivery-claimed" data-translate="tab5coupons-7">CLAIMED</b>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-3 col-xl-2">
        <div class="single-free-delivery">
          <img src="../assets/img/tab5/Free-Shipping-Voucher-3.png" class="voucher-image">
          <b class="free-delivery-title">FREE SHIPPING</b>
          <p class="free-delivery-desc" data-translate="tab5coupons-5">On all order above</p>
          <p class="free-delivery-discount">Rp.100.000</p>
          <p class="free-delivery-days" data-translate="tab5coupons-6">Expiring in 5 days</p>
          <b class="free-delivery-claimed" data-translate="tab5coupons-7">CLAIMED</b>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-3 col-xl-2">
        <div class="single-free-delivery">
          <img src="../assets/img/tab5/Free-Shipping-Voucher-3.png" class="voucher-image">
          <b class="free-delivery-title">FREE SHIPPING</b>
          <p class="free-delivery-desc" data-translate="tab5coupons-5">On all order above</p>
          <p class="free-delivery-discount">Rp.100.000</p>
          <p class="free-delivery-days" data-translate="tab5coupons-6">Expiring in 5 days</p>
          <b class="free-delivery-claimed" data-translate="tab5coupons-7">CLAIMED</b>
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

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

    if (localStorage.lang == 1){
      $('.free-delivery-desc').css('margin-left','6px');
      $('.free-delivery-desc').css('font-size','8px');
      $('.free-delivery-days').css('font-size','7px');
      $('.free-delivery-days').css('margin-left','11px');
      $('.free-delivery-discount').css('margin-left','2px');
      $('.free-delivery-title').css('margin-left','2px');
      $('.free-delivery-claimed').css('margin-top','-6px');
    }
    $('body').show();
  });
  
</script>
</html>