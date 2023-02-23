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

<body class="bg-white-background">

<!-- NAVBAR -->

<nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
        <a href="tab5-create-an-ad.php">
            <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
        </a>
        <p class="navbar-title-2" data-translate="tab5adreview-1">Ad Review</p>
        <div class="navbar-brand pt-2 navbar-brand-slot">
            <img src="" class="navbar-img-slot">
        </div>
  </div>
</nav>

<!-- SECTION CREATE AD PHOTO VIDEO -->

<div class="section-create-ad-photo">
    <div class="ad-review-title">
        <div class="container">
            <div class="row gx-0 ad-review-photo-video">
                <p class="small-text">
                    <b data-translate="tab5adreview-2">Photo / Video</b>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="small-text gx-0" style="background-color: #FAFAFF;">
    <div class="container">
        <ul class="nav nav-tabs horizontal-slide gx-0 disable-border-bottom">
            <li class="nav-item">
                <img src="../assets/img/tab5/product-5.jpg" class="ad-review-images">
            </li>
            <li class="nav-item">
                <img src="../assets/img/tab5/product-4.jpg" class="ad-review-images">
            </li>
            <li class="nav-item">
                <img src="../assets/img/tab5/product-3.jpg" class="ad-review-images">
            </li>
        </ul>
    </div>
</div>

<!-- SECTION CREATE AD FORM -->

<div class="section-create-ad-form-confirm">
    <div class="single-ad-confirm-2">
        <div class="container">
            <div class="smallest-text text-grey mb-1" data-translate="tab5adreview-3">Campaign Title</div>
            <div class="small-text">Create a fun and a cozy home!</div>
        </div>
    </div>
    <div class="single-ad-confirm">
        <div class="container">
            <div class="smallest-text text-grey mb-1" data-translate="tab5adreview-4">Target URL</div>
            <div class="small-text">www.johnsmithshop.com</div>
        </div>
    </div>
    <div class="single-ad-confirm">
        <div class="container">
            <div class="smallest-text text-grey mb-1" data-translate="tab5adreview-5">Budget</div>
            <span class="small-text">Rp 500,000</span><span class="text-grey small-text" style="margin-left: 10px;"> <span data-translate="tab5adreview-6">/daily</span></span>
        </div>
    </div>
    <div class="single-ad-confirm">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="smallest-text text-grey mb-1" data-translate="tab5adreview-9">Starting Date</div>
                    <div class="small-text">10 August 2021</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="smallest-text text-grey mb-1" data-translate="tab5adreview-10">Ending Date</div>
                    <div class="small-text">30 August 2021</div>
                </div>      
            </div>
        </div>
    </div>
    <div class="single-ad-confirm">
        <div class="container">
            <div class="smallest-text text-grey mb-1" data-translate="tab5adreview-7">Audience</div>
            <div class="small-text">Indonesia, 13 years old +</div>
        </div>
    </div>
</div>

<div class="row text-center fixed-bottom">
    <a href="tab5-ads.php?success=true">
        <div class="btn-confirm-withdrawal" data-translate="tab5adreview-8">Create Ad</div>
    </a>
</div>

<!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

	});

</script>

</html>
