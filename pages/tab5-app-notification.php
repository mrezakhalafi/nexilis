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

<style>

    /* FOR OFF SWITCH BORDER */

    #flexSwitchCheckChecked{
        border: 1px solid #000000 !important;
    }

</style>

<body class="bg-white-background" style="display:none">
    <img src="../assets/img/lbackground_1.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

    <!-- NAVBAR -->

    <nav class="navbar navbar-light navbar-shop-manager">
        <div class="container">
            <a href="tab5-shop-manager.php">
                <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
            </a>
            <p class="navbar-title-2" data-translate="tab5appnotification-1">App Notifications</p>
            <div class="navbar-brand pt-2 navbar-brand-slot">
                <img src="" class="navbar-img-slot">
            </div>
        </div>
    </nav>

    <!-- SECTION APP NOTIFICATION-->

    <div class="single-notification">
        <div class="container">
            <p class="small-text text-purple"data-translate="tab5appnotification-2">Notify Me When</p>
        </div>
    </div>
    <div class="single-notification">
        <div class="container">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9">
                    <p class="small-text" data-translate="tab5appnotification-3">Someone has sent me a message</p>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-notification">
        <div class="container">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9">
                    <p class="small-text" data-translate="tab5appnotification-4">Someone buys something from my shop</p>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-notification">
        <div class="container">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9">
                    <p class="small-text" data-translate="tab5appnotification-5">Someone favorites my listing</p>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-notification">
        <div class="container">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9">
                    <p class="small-text" data-translate="tab5appnotification-6">Someone follows my shop</p>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-notification">
        <div class="container">
            <div class="row">
                <div class="col-9 col-md-9 col-lg-9">
                    <p class="small-text" data-translate="tab5appnotification-7">Qiosk launches a new feature</p>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center fixed-bottom">
        <a href="../pages/tab5-shop-manager.php">
            <button class="btn-app-notification" style="border:none" data-translate="tab5appnotification-8">Save Changes</button>
        </a>
    </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
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

    // ANDROID FUNCTION SETTINGS

    function sentMesssage(){
        Android.sentMessage();
    }

    function buysSomething(){
        Android.buysSomething();
    }

    function favoritesListing(){
        Android.favoritesListing();
    }

    function followsShop(){
        Android.followsShop();
    }

    function newFeature(){
        Android.newFeature();
    }

    $(".btn-app-notification").click(function() {
        sentMesssage();
        buysSomething();
        favoritesListing();
        followsShop();
        newFeature();
    });

</script>
</html>