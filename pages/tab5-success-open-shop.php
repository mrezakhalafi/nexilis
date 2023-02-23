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

  // SELECT SHOP FOR SHOP MANAGER & SET TO SESSION

  $query = $dbconn->prepare("SELECT * FROM SHOP WHERE CREATED_BY = '$id_user'");
  $query->execute();
  $shop_data = $query->get_result()->fetch_assoc();
  $query->close();

  $_SESSION['id_shop'] = $shop_data["CODE"];
	setcookie("id_shop", $shop_data['CODE'], 2147483647);

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

<body style="display:none"> 
  <img src="../assets/img/lbackground_7.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- SECTION SUCCESS OPEN SHOP -->

  <div class="section-success-shop text-center align-middle">
    <img class="success-shop-image" src="../assets/img/tab5/Open-Store-Success.png">
    <p class="success-shop-title text-center" data-translate="tab5successopenshop-1" style="color: #000000"><b>Congratulation on opening your store!</b></p>
    <div class="small-text">
      <span data-translate="tab5successopenshop-2" style="color: #000000">Congratulation! You've succesfully opened your store</span>
    </div> 
    <div class="small-text">
      <span data-translate="tab5successopenshop-3" style="color: #000000">Upload your products and get your first sale!</span>
    </div>
    <a href="tab5-upload-listing.html">
      <button class="btn-upload-listing" data-translate="tab5successopenshop-4" style="color: #FFFFFF; background-color: #000000"><b>Upload Listing</b></button>
    </a>
    <a href="tab5-shop-manager.php?id=<?= $shop_data["CODE"] ?>">
      <p class="small-text mt-3 text-white" data-translate="tab5successopenshop-5" style="color: #000000 !important">View Storefront</p>
    </a>
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
    $('body').show();
	});
  
</script>
</html>