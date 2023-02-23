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

	// SELECT SHOP MANAGER DATA

	$query = $dbconn->prepare("SELECT * FROM SHOP WHERE CODE = '$id_shop'");
	$query->execute();
	$shop_data = $query->get_result()->fetch_assoc();
	$query->close();

	$_SESSION['id_shop'] = $id_shop;
	setcookie("id_shop", $id_shop, 2147483647);

	// SELECT SHOP FOLLOWERS

	$query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW WHERE STORE_CODE = '$id_shop'");
	$query->execute();
	$shop_followers = $query->get_result();
	$query->close();

	// SELECT SHOP ORDERS & REVENUE

	$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop'");
	$query->execute();
	$orders = $query->get_result();
	$query->close();

	$totalRevenue = 0;
	$totalOrders = 0;

	while ($ordersRevenue = $orders->fetch_assoc()){
		$totalRevenue += $ordersRevenue["PRICE"] * $ordersRevenue["AMOUNT"];
		$totalOrders += 1;
	};

	// COUNT ORDER WITH NO DUPLICATE USER

	$query = $dbconn->prepare("SELECT COUNT(*) FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' 
								GROUP BY TRANSACTION_ID");
	$query->execute();
	$count = $query->get_result();
	$query->close();

	// COUNT ORDER WITH NO DUPLICATE USER

	$query = $dbconn->prepare("SELECT COUNT(*) FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' 
								AND CREATED_AT LIKE '%".date('Y-m-d')."%' GROUP BY TRANSACTION_ID");
	$query->execute();
	$count_today = $query->get_result();
	$query->close();

	// FOR ORDER SINCE YESTERDAY

	require '../logics/tab5/orders-revenue.php';
	require '../logics/tab5/followers.php';

	// SELECT SHOP VIEWS

	$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE = '$id_shop'");
	$query->execute();
	$shop_views = $query->get_result();
	$query->close();

	$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE = '$id_shop'");
	$query->execute();
	$shop_website = $query->get_result();
	$query->close();

	$views = mysqli_num_rows($shop_views) + mysqli_num_rows($shop_website);

	require '../logics/tab5/views.php';
	require '../logics/tab5/view_website.php';

	$tomorrow_view = $day_website1 + $day_views1;

	// SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

	// COUNT SHOP FOLLOWING (USER)

	$query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE STORE_CODE = '$id_shop'");
	$query->execute();
	$shop_following = $query->get_result();
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

<body class="bg-white-background" id="main-menu">
	<img src="../assets/img/lbackground_8.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

	<script>
		if (localStorage.lang == 1) {
			document.getElementById("main-menu").style.visibility = "hidden";
		}
	</script>

	<!-- NAVBAR -->

	<nav class="navbar navbar-light navbar-shop-manager">
		<div class="container">
			<a href="tab5-main.php">
				<img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
			</a>
			<p class="navbar-title-2" data-translate="tab5shopmanager-1">Shop Manager</p>
			<div class="navbar-brand pt-2 navbar-brand-slot-with-margin">
				<img src="../assets/img/tab5/add-black.png" class="navbar-img-add-purple" data-bs-toggle="modal" data-bs-target="#addShopModal">
			</div>
		</div>
	</nav>

	<!-- SECTION SHOP PROFILE -->

	<!-- IF SHOP EXIST -->

	<?php if (isset($shop_data["NAME"])) : ?>

	<div class="section-shop-profile">
		<div class="container">
			<div class="row">
				<div class="col-2 col-md-2 col-lg-2">

					<!-- IF SHOP HAVE IMAGE -->

					<?php if ($shop_data["THUMB_ID"]!="") : ?>
						<img src="../images/<?= $shop_data["THUMB_ID"] ?>" class="ava-shop-profile rounded-circle">
					<?php else: ?>
						<img src="../assets/img/tab5/shop.jpg" class="ava-shop-profile rounded-circle">
					<?php endif; ?>

				</div>
				<div class="col-10 col-md-10 col-lg-10">
					<div class="row">
						<div class="col-10 col-md-10 col-lg-11">
							<span class="small-text">
								<b><?= $shop_data["NAME"] ?></b>
							</span>
						</div>
						<div class="col-2 col-md-2 col-lg-1">
							<a href="tab5-store.php?id=<?= $id_shop ?>" class="d-flex justify-content-end">
								<img src="../assets/img/tab5/Store-(Orange).png" class="shop-icon-small">
								<span class="small-text text-orange" data-translate="tab5shopmanager-2">Storefront</span>
							</a>
						</div>
					</div>
					<span class="small-text shop-manager-section-follow"><span class="text-purple shop-manager-follow">
						<b><?= mysqli_num_rows($shop_followers) ?></span><span data-translate="tab5shopmanager-3"> Followers<span></b>
					</span>
					<span class="small-text"><span class="text-purple shop-manager-follow">
						<b><?= mysqli_num_rows($shop_following) ?></span><span data-translate="tab5shopmanager-4"> Following<span></b>
					</span>
				</div>
			</div>
		</div>
	</div>

	<?php else: ?>

	<div class="section-shop-profile">
		<div class="container">
			<div class="row">
				<div class="col-2 col-md-2 col-lg-2">
					<img src="../assets/img/tab5/shop.jpg" class="ava-shop-profile rounded-circle">
				</div>
				<div class="col-10 col-md-10 col-lg-10">
					<div class="row">
						<div class="col-10 col-md-10 col-lg-11">
							<span class="small-text"><b>Shop Dummy (ID = <?= $id_shop ?>)</b></span>
						</div>
						<div class="col-2 col-md-2 col-lg-1">
							<a href="tab5-store.html" class="d-flex justify-content-end">
								<img src="../assets/img/tab5/Store-(Orange).png" class="shop-icon-small">
								<span class="small-text text-orange">Storefront</span>
							</a>
						</div>
					</div>
					<span class="small-text shop-manager-section-follow"><span class="text-purple shop-manager-follow"><b>135</span> Followers</b></span>
					<span class="small-text"><span class="text-purple shop-manager-follow"><b>20</span> Following</b></span>
				</div>
			</div>
		</div>
	</div>

	<?php endif; ?>

	<!-- SECTION SHOP DATA -->

	<!-- IF SHOP EXIST -->

	<?php if (isset($shop_data)) : ?>

	<div class="section-shop-data">
		<div class="container">
			<div class="row m-2">
				<div class="shop-manager-data col-6 shadow-sm">
					<a href="tab5-orders-revenue.php">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey" data-translate="tab5shopmanager-5">Orders</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/Orders.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple"><?= mysqli_num_rows($count) ?></span>
						</div>
						<span class="shop-data-number-green"><b>+<?= mysqli_num_rows($count_today) ?></b></span>
						<span class="smallest-text text-grey" data-translate="tab5shopmanager-6">Since yesterday</span>
					</a>
				</div>
				<div class="col-6 col-md-6 col-lg-6 shop-manager-data shadow-sm">
					<a href="tab5-orders-revenue.php">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey" data-translate="tab5shopmanager-7">Revenue</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/Revenue.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple"><?= number_format($totalRevenue,0,",",",") ?></span>
						</div>
						<span class="shop-data-number-green">+<b><?= number_format($day_income1,0,",",",") ?></b></span>
						<span class="smallest-text text-grey" data-translate="tab5shopmanager-6">Since yesterday</span>
					</a>
				</div>
				<div class="col-6 col-md-6 col-lg-6 shop-manager-data shadow-sm">
					<a href="tab5-views.php">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey" data-translate="tab5shopmanager-8">Total View</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/views.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple"><?= $views ?></span>
						</div>
						<span class="shop-data-number-green"><b>+<?= $tomorrow_view ?></b></span>
						<span class="smallest-text text-grey" data-translate="tab5shopmanager-6">Since yesterday</span>
					</a>
				</div>
				<div class="col-6 col-md-6 col-lg-6 shop-manager-data shadow-sm">
					<a href="tab5-followers.php?id=<?= $shop_data["CODE"] ?>">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey"data-translate="tab5shopmanager-9">Followers</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/Followers.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple"><?= mysqli_num_rows($shop_followers) ?></span>
						</div>
						<span class="shop-data-number-green"><b>+<?= $day_followers1 ?></b></span>
						<span class="smallest-text text-grey" data-translate="tab5shopmanager-6">Since yesterday</span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php else: ?>

	<div class="section-shop-data">
		<div class="container">
			<div class="row m-2">
				<div class="shop-manager-data col-6 shadow-sm">
					<a href="tab5-orders-revenue.html">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey">Orders</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/Orders.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple">045</span>
						</div>
						<span class="shop-data-number-green"><b>+03</b></span>
						<span class="smallest-text text-grey">Since yesterday</span>
					</a>
				</div>
				<div class="col-6 col-md-6 col-lg-6 shop-manager-data shadow-sm">
					<a href="tab5-orders-revenue.html">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey">Revenue</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/Revenue.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple">965,000</span>
						</div>
						<span class="shop-data-number-green"><b>+70,000</b></span>
						<span class="smallest-text text-grey">Since yesterday</span>
					</a>
				</div>
				<div class="col-6 col-md-6 col-lg-6 shop-manager-data shadow-sm">
					<a href="tab5-views.html">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey">Total View</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/views.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple">893</span>
						</div>
						<span class="shop-data-number-green"><b>+100</b></span>
						<span class="smallest-text text-grey">Since yesterday</span>
					</a>
				</div>
				<div class="col-6 col-md-6 col-lg-6 shop-manager-data shadow-sm">
					<a href="tab5-followers.html">
						<div class="row">
							<div class="col-9 col-md-10 col-lg-11">
								<span class="small-text text-grey">Followers</span>
							</div>
							<div class="col-3 col-md-2 col-lg-1">
								<img src="../assets/img/tab5/Shop Manager/Followers.png" class="shop-manager-icons">
							</div>
						</div>
						<div class="row">
							<span class="shop-data-number-purple">1049</span>
						</div>
						<span class="shop-data-number-green"><b>+101</b></span>
						<span class="smallest-text text-grey">Since yesterday</span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php endif; ?>

	<!-- SECTION SHOP BUTTON -->

	<div class="section-shop-button">
		<div class="container">
			<a href="tab5-your-orders.php?src=sm">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Orders-(Grey).png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5shopmanager-10">Orders</small>
					</div>
				</div>
			</a>
			<a href="tab5-listing.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Listing (Grey).png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5shopmanager-11">Listing</small>
					</div>
				</div>
			</a>
			<a href="tab5-shipping.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Shipping-Settings-(Grey).png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5shopmanager-12">Shipping Settings</small>
					</div>
				</div>
			</a>
			<!-- <a href="tab5-finances.php"> -->
			<a href="">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Finances (Grey).png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small class="text-orange" data-translate="tab5shopmanager-13">Finances</small>
					</div>
				</div>
			</a>
			<div class="row mt-2 mb-2 marketing-submenu">
				<div class="col-1 col-md-1 col-lg-1">
					<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Marketing (Grey).png">
				</div>
				<div class="col-9 col-md-9 col-lg-9">
					<a href="tab5-ads.php">
						<small data-translate="tab5shopmanager-14">Marketing</small>
					</a>
				</div>
				<div class="col-1 col-md-1 col-lg-1">
					<img src="../assets/img/tab5/Down-(Black).png" class="small-arrow">
				</div>
			</div>
			<div class="shop-manager-submenu">
				<a href="tab5-ads.php">
					<div class="row mt-2 mb-2">
						<div class="col-1 col-md-1 col-lg-1"></div>
						<div class="col-10 col-md-10 col-lg-10">
							<small data-translate="tab5shopmanager-15">Qiosk Ads</small>
						</div>
					</div>
				</a>
				<a href="tab5-discount-vouchers.php">
				<div class="row mt-2 mb-2">
						<div class="col-1 col-md-1 col-lg-1"></div>
						<div class="col-10 col-md-10 col-lg-10">
							<small data-translate="tab5shopmanager-16">Discounts & Vouchers</small>
						</div>
					</div>
				</a>
			</div>
			<a href="tab5-shop-settings.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Shop-Settings (Grey).png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5shopmanager-17">Shop Settings</small>
					</div>
				</div>
			</a>
			<a href="tab5-app-notification.php">
				<div class="row mt-2 mb-2">
					<div class="col-1 col-md-1 col-lg-1">
						<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/App-Notification-(Grey).png">
					</div>
					<div class="col-10 col-md-10 col-lg-10">
						<small data-translate="tab5shopmanager-18">App Notifications</small>
					</div>
				</div>
			</a>
			<div class="row mt-2 mb-2">
				<div class="col-1 col-md-1 col-lg-1">
					<img class="shop-manager-small-icon" src="../assets/img/tab5/Help.png">
				</div>
				<div class="col-10 col-md-10 col-lg-10">
					<small data-translate="tab5shopmanager-19">Help</small>
				</div>
			</div>
			<div class="row mt-2 mb-2">
				<div class="col-1 col-md-1 col-lg-1">
					<img class="shop-manager-small-icon" src="../assets/img/tab5/Shop Manager/Sign-Out-(Grey).png">
				</div>
				<div class="col-10 col-md-10 col-lg-10">
					<small data-translate="tab5shopmanager-20">Sign Out</small>
				</div>
			</div>
		</div>
	</div>

	<!-- ADD SHOP MODAL MENU MODAL -->

	<div class="modal fade" id="addShopModal" tabindex="-1" aria-labelledby="addShopModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
		<div class="modal-content">
			<div class="row d-flex justify-content-center">
				<hr class="shop-modal-line">
			</div>
			<div class="modal-body modal-add-shop">
				<div class="row">
					<div class="modal-add-shop-single col-4 col-md-4 col-lg-4 text-center shop-modal-menu-left">
						<a href="tab5-new-post.php">
							<img src="../assets/img/tab5/create-post-black.png" class="add-shop-icon">
							<p class="small-text" data-translate="tab5shopmanager-21">Post</p>
						</a>
					</div>
					<div class="modal-add-shop-single col-4 col-md-4 col-lg-4 text-center shop-modal-menu-center">
						<a href="tab5-live-stream.php">
							<img src="../assets/img/tab5/create-live-stream-black.png" class="add-shop-icon">
							<p class="small-text" data-translate="tab5shopmanager-22">Live Stream</p>
						</a>
					</div>
					<div class="modal-add-shop-single col-4 col-md-4 col-lg-4 text-center shop-modal-menu-right">
						<a href="tab5-upload-listing.php">	
							<img src="../assets/img/tab5/create-listing-black.png" class="add-shop-icon">
							<p class="small-text" data-translate="tab5shopmanager-23">Listing</p>
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

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

		if (localStorage.lang == 1) {
			document.getElementById("main-menu").style.visibility = "visible";
		}
	});

	// SCRIPT TOGGLE MARKETING

	$(".marketing-submenu").click(function(){
		$(".shop-manager-submenu").toggle();
	});

	// RESET QUERY SEARCH

	window.localStorage.removeItem('search_keyword');
	window.localStorage.removeItem('position');

	// RESET LIVE STREAM DATA

 	window.localStorage.removeItem('title_ls');
	window.localStorage.removeItem('thumbnail_ls');
	window.localStorage.removeItem('feature_product_temp');
	window.localStorage.removeItem('feature_product_temp_code');

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

	// RESET UPLOAD POST DATA

	window.localStorage.removeItem('caption_post');
	window.localStorage.removeItem('hashtag_post');
	window.localStorage.removeItem('location_post');
	window.localStorage.removeItem('tagged_post');
	window.localStorage.removeItem('tagged_post_name');
	window.localStorage.removeItem('tagged_media');
	window.localStorage.removeItem('post_check');
	window.localStorage.removeItem('is_paid');

	window.localStorage.removeItem('category_name');
	window.localStorage.removeItem('category_id');
	window.localStorage.removeItem('price');

	// START LIVE STREAM FROM CREATE LIVE STREAMING

	<?php

	$ls_title = $_GET['title'];

	if (isset($_GET['live_streaming'])){
		echo("Android.startLiveStream('".$ls_title."');");

		echo("history.pushState(null, null, '/nexilis/pages/tab5-shop-manager');");
	}

	?>

</script>
</html>