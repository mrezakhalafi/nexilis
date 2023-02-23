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

  // SELECT USER

  $query = $dbconn->prepare("SELECT * FROM SHOP LEFT JOIN SHOP_SHIPPING_ADDRESS ON SHOP.CODE =
                              SHOP_SHIPPING_ADDRESS.STORE_CODE WHERE SHOP.CODE = '$id_shop'");
  $query->execute();
  $shop = $query->get_result()->fetch_assoc();
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

  <title>Nexilis Sports
</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_5.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shipping.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5changeaddress-1">Delivery Address</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION SHIPPING -->

  <form action="../logics/tab5/change_address_shop" method="POST">
    <div class="section-settings-form text-center container">
      <div class="smallest-text float-start mb-3">
      <b data-translate="tab5shopsettings-3">SHIPMENT INFORMATION</b>
      </div> 
      <input type="text" autocomplete="off" class="shop-settings-input" id="name" placeholder="Name" name="name" value="<?= $shop['NAME'] ?>">
      <input type="text" maxlength="14" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off" class="shop-settings-input" id="phone_number" placeholder="Phone Number" name="phone_number" value="<?= $shop['PHONE_NUMBER'] ?>">
      <textarea class="shop-settings-input-desc" id="address" placeholder="Address" name="address" rows="5"><?= $shop['ADDRESS'] ?></textarea>
      
      <div style="border-bottom: 1px solid #d1d5db; margin-top: 15px; margin-bottom: 15px"></div>

      <input type="text" autocomplete="off" class="shop-settings-input" id="city" placeholder="City" name="city" value="<?= $shop['CITY'] ?>">
      <input type="text" autocomplete="off" class="shop-settings-input" id="province" placeholder="Province" name="province" value="<?= $shop['PROVINCE'] ?>">
      <input type="text" maxlength="14" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off" class="shop-settings-input" id="zip_code" placeholder="Zip Code" name="zip_code" value="<?= $shop['ZIP_CODE'] ?>">
      <textarea class="shop-settings-input-desc" id="courier_note" placeholder="Courier Note" name="courier_note" rows="5"><?= $shop['COURIER_NOTE'] ?></textarea>

      <input type="hidden" name="id_shop" value="<?= $id_shop ?>">
    </div>

    <div class="row text-center fixed-bottom">
      <button class="btn-live" style="border:none" type="submit" data-translate="tab5changeaddress-3">Submit</button>
    </div>
  </form>
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