<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

	// GET F_PIN FROM ANDROID OR SESSION OR COOKIE

	if (isset($_SESSION['user_f_pin'])){
		$id_user = $_SESSION["user_f_pin"];
	}else if(isset($_GET['f_pin'])) {
		$id_user = $_GET['f_pin'];
	}else if(isset($_COOKIE['id_user'])){
		$id_user = $_COOKIE['id_user'];
	}

	// CHECK USER ID

	if (!isset($id_user)) {
		die("ID User Tidak Diset.");
	}

	// SELECT USER & SET TO SESSION

	$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$id_user'");
	$query->execute();
	$user = $query->get_result()->fetch_assoc();
	$query->close();

	$_SESSION["user_f_pin"] = $user["F_PIN"];
	$_SESSION["user_first_name"] = $user["FIRST_NAME"];
	setcookie("id_user", $id_user, 2147483647);

	// SELECT USER ALREADY HAVE SHOP & SET TO SESSION

	$query = $dbconn->prepare("SELECT * FROM SHOP WHERE CREATED_BY = '$id_user'");
	$query->execute();
	$shop_check = $query->get_result()->fetch_assoc();
	$query->close();

	$_SESSION['id_shop'] = $shop_check['CODE'];

	// SELECT USER FOLLOWING

	$query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW WHERE F_PIN = '$id_user'");
	$query->execute();
	$user_following = $query->get_result()->fetch_assoc();
	$query->close();

	// SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

	// GET USER POINT

	$query = $dbconn->prepare("SELECT * FROM SHOP_REWARD_POINT WHERE F_PIN = '$id_user'");
	$query->execute();
	$user_point = $query->get_result();
	$query->close();

	$point = 0;
	while ($count_point = $user_point->fetch_assoc()){
		$point = $point + $count_point['AMOUNT'];
	};

	// BRONZE POINT (0-100)

	if ($point <= 100){
		if ($point > 50){
			$point_left = 180;
			$point_right = ((180*$point) / 50) - 180;
		}else{
			$point_left = (180*$point) / 50;
			$point_right = 0;
		}
	}

	// SILVER POINT (101-500)

	if ($point > 101 && $point <= 500){
		if ($point > 250){
			$point_left = 180;
			$point_right = ((180*$point) / 250) - 180;
		}else{
			$point_left = ((180*$point) / 250);
			$point_right = 0;
		}
	}

	// GOLD POINT (501-1000)

	if ($point > 501 && $point <= 1000){
		if ($point > 500){
			$point_left = 180;
			$point_right = ((180*$point) / 500) - 180;
		}else{
			$point_left = ((180*$point) / 500);
			$point_right = 0;
		}
	}

	// PLATINUM POINT (1001-5000)

	if ($point > 1000 && $point <= 5000){
		if ($point > 2500){
			$point_left = 180;
			$point_right = ((180*$point) / 2500) - 180;
		}else{
			$point_left = ((180*$point) / 2500);
			$point_right = 0;
		}
	}

?>

<!doctype html>

<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Nexilis</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<link href="../assets/css/tab5-collection-style.css" rel="stylesheet">
	<link href="../assets/css/tab5-style.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>
	
<body class="bg-white-background" style="display:none">
	<img src="../assets/img/lbackground_2.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

	<style>
		/* FOR HTML NOT OFFSIDE */

		html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

		form#searchFilterForm-a { 
            border: 1px solid #c9c9c9;
            background-color: rgba(255,255,255,.5);
            width:100%;
        }

		input{
			background-color: transparent;
		}

		@keyframes loading-1{
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg)
			}

			100% {
				-webkit-transform: rotate(<?= $point_left ?>deg);
				transform: rotate(<?= $point_left ?>deg)
			}
		}

		@keyframes loading-2{
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg)
			}

			100% {
				-webkit-transform: rotate(<?= $point_right ?>deg);
				transform: rotate(<?= $point_right ?>deg)
			}
		}

		.progress:after {
			border: 6px solid #000000 !important;
		}

	</style>

	<!-- NAVBAR -->

	<div id="header" class="container-fluid sticky-top">
		<div class="col-12 col-md-12 col-lg-12">
			<div class="row align-items-center" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; padding: 10px 0 30px 0;">
				<div class="col-1 col-md-1 col-lg-1">
					<a href="tab5-main.php">
						<img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
					</a>
				</div>
				<div id="searchFilter-a" class="col-10 col-md-10 col-lg-10 d-flex align-items-center justify-content-center text-white">
                    <form autocomplete="off" id="searchFilterForm-a" action="search-result" method=GET>
                    	<input id="query" placeholder="Search" type="text" class="search-query" name="query" value="">
                        <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
                        <img id="voice-search" onclick="voiceSearch()" src="../assets/img/icons/Voice-Command-(Black).png">
                    </form>
                </div>
				<a class="col-1 col-md-1 col-lg-1" href="cart.php?v=<?= time(); ?>">
					<div class="position-relative">
						<img class="float-end" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
						<span id="counter-here"></span>
					</div>
				</a>
				<!-- <div class="col-1 col-md-1 col-lg-1">
					<a href="notifications.php">
					<div class="position-relative">
                        <img class="float-end" src="../assets/img/icons/Shop Manager/App-Notification-(white).png" style="width:30px">
                        <span id='counter-notifs'></span>
                    </div>
					</a>
				</div> -->
			</div>
		</div>
	</div>

	<!-- SECTION LOYALTY -->

	<div class="section-loyalty">
		<div class="loyalty-balance" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>">
			<div class="row">
				<div class="col-5 col-md-4 col-lg-4 d-flex justify-content-center">
					<p class="small-text" style="margin-left: -20%; color: #000000" data-translate="tab5-1">Loyalty Balance<b></b></p>
				</div>
				<div class="col-5 col-md-5 col-lg-7"></div>
				<div class="col-2 col-md-3 col-lg-1">
					<img class="section-menu-icon" src="../assets/img/tab5/Help-black.png">
				</div>
			</div>
			<div class="row">
				<div class="col-3 col-md-3 col-lg-3 medal-section">
					<div class="row d-flex justify-content-center">
						<div class="progress orange">
							<span class="progress-left">
								<span class="progress-bar"></span>
							</span>
							<span class="progress-right">
								<span class="progress-bar"></span>
							</span>
							<img class="image-medal" src="../assets/img/tab5/Medal-(White).png">
						</div>
					</div>
				</div>
				<div class="col-9 col-md-9 col-lg-9">
					<div class="row" style="padding-bottom: 14px">
						<div class="col-8 col-md-9 col-lg-10" style="color: #000000">
							<span class="loyalty-number"><b><?= $point ?></b></span>
							<span class="small-text">pts</span>
						</div>
						<div class="col-4 col-md-3 col-lg-2">

							<?php if ($point <= 100): ?>
								<span class="badge rounded-pill gold-badge text-light" style="background-color: #CD7F32">
									<b>Bronze</b>
								</span>
							<?php elseif($point > 100 && $point <= 500): ?>
								<span class="badge rounded-pill gold-badge text-light" style="background-color: #C0C0C0">
									<b>Silver</b>
								</span>
							<?php elseif($point > 500 && $point <= 1000): ?>
								<span class="badge rounded-pill gold-badge text-light">
									<b>Gold</b>
								</span>									
							<?php elseif($point > 1000 && $point <= 5000): ?>
								<span class="badge rounded-pill gold-badge text-light" style="background-color: #000000">
									<b>Platinum</b>
								</span>
							<?php endif; ?>

						</div>
						<hr size="4" class="white-underline" style="color: #000000">
						
						<?php if ($point <= 100): ?>
							<p class="earn-point-text"><span data-translate="tab5-2" style="color: #000000">Earn</span><span style="color: #000000"><?= (100-$point) ?></span><span data-translate="tab5-15" style="color: #000000">points more to reach silver</span></p>
						<?php elseif($point > 100 && $point <= 500): ?>
							<p class="earn-point-text"><span data-translate="tab5-2" style="color: #000000">Earn</span><span style="color: #000000"><?= (500-$point) ?></span><span data-translate="tab5-16" style="color: #000000">points more to reach gold</span></p>
						<?php elseif($point > 500 && $point <= 1000): ?>
							<p class="earn-point-text"><span data-translate="tab5-2" style="color: #000000">Earn</span><span style="color: #000000"><?= (1000-$point) ?></span><span data-translate="tab5-17" style="color: #000000">points more to reach platinum</span></p>							
						<?php elseif($point > 1000 && $point <= 5000): ?>
							<p class="earn-point-text"><span data-translate="tab5-2" style="color: #000000">Earn</span><span style="color: #000000"><?= (5000-$point) ?></span><span data-translate="tab5-18" style="color: #000000">points more to reach VIP</span></p>
						<?php endif; ?>	
					
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- SECTION MAIN BUTTON -->

	<div class="section-button" style="border-top: 1px solid #d1d5db">
		<div class="row">
			<div class="col-3 col-md-3 col-lg-3 text-center">
				<a href="tab5-orders.php">
					<img class="button-icon" src="../assets/img/tab5/orders-black.png">
					<p class="small-text" data-translate="tab5-3">Orders</p>
				</a>
			</div>
			<div class="col-3 col-md-3 col-lg-3 text-center">
				<a href="tab5-coupons.php">
					<img class="button-icon text-center" src="../assets/img/tab5/coupons-black.png">
					<p class="small-text" data-translate="tab5-5">Coupons</p>
				</a>
			</div>
			<div class="col-3 col-md-3 col-lg-3 text-center">
				<a href="tab5-wishlist.php">
					<img class="button-icon text-center" src="../assets/img/tab5/wishlist-black.png">
					<p class="small-text" data-translate="tab5-4">Wishlist</p>
				</a>
			</div>
			<div class="col-3 col-md-3 col-lg-3 text-center">
				<a href="tab5-following.php">
					<img class="button-icon" src="../assets/img/tab5/following-black.png">
					<p class="small-text" data-translate="tab5-6">Following</p>
				</a>
			</div>
		</div>
	</div>

	<!-- SECTION MENU -->

	<div class="section-menu">
		<div class="container">
			<div class="row mt-2 mb-2">
				<div class="col-1 col-md-1 col-lg-1">
					<img class="section-menu-icon" src="../assets/img/tab5/reviews.png" style="width:14px; margin-left: 3px">
				</div>
				<div class="col-10 col-md-1 col-lg-10">
					<small data-translate="tab5-14">Reviews</small>
				</div>
			</div>
			<div class="row mt-2 mb-2">
				<div class="col-1 col-md-1 col-lg-1">
					<img class="section-menu-icon" src="../assets/img/tab5/Payment.png">
				</div>
				<div class="col-10 col-md-1 col-lg-10">
					<small data-translate="tab5-7">Payment</small>
				</div>
			</div>
			<a href="tab5-delivery-address.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="section-menu-icon" src="../assets/img/tab5/Delivery-Address.png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5-8">Delivery Address</small>
					</div>
				</div>
			</a>
			<div class="row mt-2 mb-2">
				<div class="col-1 col-md-1 col-lg-1">
					<img class="section-menu-icon" src="../assets/img/tab5/Language.png">
				</div>
				<div class="col-6 col-md-6 col-lg-6">
					<small data-translate="tab5-9">Language</small>
				</div>
				<div class="col-5 col-md-5 col-lg-5 d-flex justify-content-end">
					<div class="dropdown">
						<button class="dropdown-toggle" type="button" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: transparent; color: #8a8a8a">
							English
						</button>
						<ul class="dropdown-menu" style=" min-width: auto !important" aria-labelledby="dropdownMenuLanguage">
							<li><a onclick="changeLanguageSelect(0)" class="dropdown-item">English</a></li>
							<li><a onclick="changeLanguageSelect(1)" class="dropdown-item">Indonesia</a></li>
						</ul>
					</div>
					<span class="text-grey language-arrow">></span>
				</div>
			</div>
		</div>
	</div>

	<div class="section-menu-2" style="margin-bottom: 40px">
		<div class="container">
			<a href="../pages/tab5-notifications.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="section-menu-icon" src="../assets/img/tab5/Notification.png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5-10">Notifications</small>
					</div>
				</div>
			</a>
			<a href="../pages/tab5-privacy-policy.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="section-menu-icon" src="../assets/img/tab5/Privacy-Policy.png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5-11">Privacy Policy</small>
					</div>
				</div>
			</a>
			<a href="../pages/tab5-security.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="section-menu-icon" src="../assets/img/tab5/Security.png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5-12">Security</small>
					</div>
				</div>
			</a>
			<a href="../pages/tab5-settings.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="section-menu-icon" src="../assets/img/tab5/settings-grey.png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5-19">Settings</small>
					</div>
				</div>
			</a>
			<a href="../pages/tab5-help.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="section-menu-icon" src="../assets/img/tab5/Help.png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5-13">Help</small>
					</div>
				</div>
			</a>
		</div>
	</div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>
<script src="../assets/js/update_counter.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {

		function changeLanguage() {

			var lang = localStorage.lang;
			change_lang(lang);

		}

		changeLanguage();

		if (localStorage.lang == 1) {
			$('#dropdownMenuSelectLanguage').text('Indonesia');
			$('#query').attr('placeholder', 'Pencarian');
		} else if (localStorage.lang == 0) {
			$('#query').attr('placeholder', 'Search');
			$('#dropdownMenuSelectLanguage').text('English');
		}

		$('body').show();
	});

	// CHANGE DROPDOWN AS NAME AS CLICK

	$('.dropdown-item').click(function() {
		$('.dropdown-toggle').text($(this).text());
	});

	// SCRIPT CHANGE LANGUAGE BY SELECT

	function changeLanguageSelect(lang) {

		localStorage.lang = lang;

		if (localStorage.lang == 1) {
			$('#dropdownMenuSelectLanguage').text('Indonesia');
			$('#query').attr('placeholder', 'Pencarian');
		} else if (localStorage.lang == 0) {
			$('#query').attr('placeholder', 'Search');
			$('#dropdownMenuSelectLanguage').text('English');
		}

		change_lang(lang);

		if (lang == 1){
			androidLang = "in";
		}else if(lang == 0){
			androidLang = "en";
		}

		console.log(androidLang);

		if (window.Android){
			Android.changeLang(androidLang);
		}

	}

	// RESET QUERY SEARCH

	window.localStorage.removeItem('search_keyword');

	// FUNCTION VOICE SEARCH

	function voiceSearch(){
		Android.toggleVoiceSearch();
	}

	function submitVoiceSearch(searchQuery){
		$('#query').val(searchQuery);
    	$('#delete-query').removeClass('d-none');
	}

	// FUNCTION SAVE SEARCH

	$('#query').on('change', function(){
		localStorage.setItem("search_keyword", this.value);
	});

	// FUNCTION X ON SEARCH

	$("#delete-query").click(function (){
		$('#query').val('');
		window.location = 'tab5.php';
	})

	$('#query').keyup(function (){
		console.log('is typing: ' + $(this).val());

		if ($(this).val() != '') {
		$('#delete-query').removeClass('d-none');
		} else {
		$('#delete-query').addClass('d-none');
		}
	})

	// OPEN MENU FROM ANDROID

	function settingsAndroid() {
		Android.settingsAndroid();
	}
	
</script>
</html>