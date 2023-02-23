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

<body class="bg-white-background">
  <img src="../assets/img/lbackground_7.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">


  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2">Discount & Vouchers</p>
      <div class="navbar-brand pt-2" class="navbar-brand-slot">
          <img src="../assets/img/tab5/search-black.png" class="search-purple-right">
      </div>
    </div>
    </div>
  </nav>

  <!-- SECTION SHOP VOUCHER -->

  <div class="section-shop-nav-switch">
    <div class="row gx-0 text-center">
      <div class="col-6 col-md-6 col-lg-6 store-nav-single-left">
        <p class="small-text"><b>Ongoing</b></p>
      </div>
      <div class="col-6 col-md-6 col-lg-6 store-nav-single-right">
        <p class="small-text"><b>Expired</b></p>
    </div>
  </div>

  <div class="section-discount">
    <p class="text-center small-text mt-5">You have no ongoing discounts.</p>
  </div>

  <div class="row text-center fixed-bottom">
    <button class="btn-live" style="border:none" type="submit">Create New Discount</button>
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
  });

  // NAV SWITCH SCRIPT

  $(".store-nav-single-right").click(function() {
    $(".store-nav-single-right").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-left").css({"border-bottom": "none"});
  });

  $(".store-nav-single-left").click(function() {
    $(".store-nav-single-left").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-right").css({"border-bottom": "none"});
  });

</script>
</html>