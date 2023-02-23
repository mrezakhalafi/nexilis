<?php

  // KONEKSI

  include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
  $dbconn = paliolite();
  session_start();

  // ID SHOP GET

  if (!isset($_SESSION['id_shop'])){
    $id_shop = $_GET['id'];
    $_SESSION['id_shop'] = $id_shop;
  }else{
    $id_shop = $_SESSION["id_shop"];
  }

  // CHECK SHOP ID

  if (!isset($id_shop)) {
    die("ID Shop Tidak Diset.");
  }

  // SELECT ADDRESS FROM SHOP SHIPPING
  
  $query = $dbconn->prepare("SELECT * FROM SHOP_SHIPPING_ADDRESS LEFT JOIN SHOP ON SHOP_SHIPPING_ADDRESS.STORE_CODE 
                            = SHOP.CODE WHERE SHOP.CODE = '".$id_shop."'");
  $query->execute();
  $shippingAddress = $query->get_result()->fetch_assoc();
  $query->close();

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
  <img src="../assets/img/lbackground_17.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5shipping-1">Shipping</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION SHIPPING -->

  <div class="section-shipping">
    <div class="container">
      <div class="row">
        <b class="order-details-title" data-translate="tab5shipping-2">Available for deposit</b>
        <div class="row">
          <div class="col-9 col-md-9 col-lg-9">
            <b class="small-text">
              <?= $shippingAddress['NAME'] ?>
            </b>
            <div class="small-text text-grey">
              <?php if (isset($shippingAddress['ADDRESS'])): ?>  
                <?= $shippingAddress['ADDRESS'] ?>
              <?php else: ?>
                Belum Diatur / Not Set
              <?php endif; ?>
            </div>
            <div class="small-text text-grey">
              <?php if (isset($shippingAddress['PHONE_NUMBER'])): ?>  
                <?= $shippingAddress['PHONE_NUMBER'] ?>
              <?php else: ?>
                Belum Diatur / Not Set
              <?php endif; ?>
            </div>
          </div>
          <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
            <a href="tab5-change-address-shop.php">
              <p class="small-text text-orange" data-translate="tab5shipping-3">Change</p>
            </a>
          </div>
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
    $('body').show();
  });

</script>
</html>