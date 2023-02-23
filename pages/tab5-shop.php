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

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_4.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

<!-- NAVBAR -->

<nav class="navbar navbar-light" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>">
  <div class="container">
    <a href="tab5-main.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
    </a>
    <div class="navbar-brand pt-2 tab5-navbar">
        <img src="../assets/img/tab5/store-black.png" class="store-icon">
        <span class="navbar-shop-text" data-translate="tab5shop-1" style="color: #000000">Shop</span>
    </div>
 </div>
</nav>

<!-- SECTION OPEN YOUR SHOP -->

<div class="section-shop container text-center" style="margin-bottom: 100px">
    <img class="shop-icon" src="../assets/img/tab5/Store-(Orange).png">
    <h3 class="shop-title"><b data-translate="tab5shop-2">Open Your Shop on Nex-Shop</b></h3>
    <div class="shop-sub-text small-text">
        <span class="text-purple" data-translate="tab5shop-3">Showcase Your Products</span>
        <p data-translate="tab5shop-4">Allow people to early shop with your branded photos and videos across Nex-Shop</p>
    </div>
    <div class="shop-sub-text small-text">
    <span class="text-purple" data-translate="tab5shop-6">Engage with Customers</span>
        <p data-translate="tab5shop-4">Allow people to early shop with your branded photos and videos across Nex-Shop</p>
    </div>
    <div class="shop-sub-text small-text">
    <span class="text-purple" data-translate="tab5shop-7">Easily Track Your Sales</span>
        <p data-translate="tab5shop-4">Allow people to early shop with your branded photos and videos across Nex-Shop</p>
    </div>
    <a href="tab5-open-shop.php"><button class="btn-get-started" data-translate="tab5shop-5">Get Started</button></a>
</div>

<!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

    if ((localStorage.lang) == 1){
      $('#shop_title').attr('placeholder', 'Nama Toko');
      $('#shop_desc').attr('placeholder', 'Deskripsi Toko');
    }
  
    $('body').show();
  });
  
</script>

</html>