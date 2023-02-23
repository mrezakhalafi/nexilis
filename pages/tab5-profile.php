<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

	// GET F_PIN FROM ANDROID OR SESSION OR COOKIE

	if (isset($_GET['f_pin'])){
		$id_user = $_GET['f_pin'];
	}else if(isset($_SESSION['user_f_pin'])) {
		$id_user = $_SESSION["user_f_pin"];
	}else if(isset($_COOKIE['id_user'])){
		$id_user = $_COOKIE['id_user'];
	}

	// CHECK USER ID

	if (!isset($id_user)) {
		die("ID User Tidak Diset.");
	}

	// SELECT USER & SET TO SESSION

	$id_visit = $_GET['id_visit'];
	$id_shop = $_SESSION['id_shop'];

	$query = $dbconn->prepare("SELECT * FROM USER_LIST LEFT JOIN USER_LIST_EXTENDED ON USER_LIST.F_PIN =
								USER_LIST_EXTENDED.F_PIN WHERE USER_LIST.F_PIN = '$id_visit'");
	$query->execute();
	$user = $query->get_result()->fetch_assoc();
	$query->close();

	// SELECT USER FOLLOWING (SHOP)

	$query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW WHERE F_PIN = '$id_visit'");
	$query->execute();
	$user_following_a = $query->get_result();
	$query->close();

	// SELECT USER COLLECTION

	$query = $dbconn->prepare("SELECT * FROM COLLECTION WHERE F_PIN = '".$id_visit."' ORDER BY 
								COLLECTION.ID");
	$query->execute();
	$user_collection = $query->get_result();
	$query->close();

	// PHP DATE AGO

	function timeAgo($time_ago){

		$time_ago = strtotime($time_ago);
		$cur_time = time();
		$time_elapsed = $cur_time-$time_ago;
		$seconds = $time_elapsed;
		$minutes = round($time_elapsed/60);
		$hours = round($time_elapsed/3600);
		$days = round($time_elapsed/86400);
		$weeks = round($time_elapsed/604800);
		$months = round($time_elapsed/2600640);
		$years = round($time_elapsed/31207680);

		if ($seconds <= 60){
			return "<script>
						if (localStorage.lang == 0){ 
							document.write('just now'); 
						}else if(localStorage.lang == 1){
							document.write('baru saja');
						}
					</script>";
		}else if($minutes <= 60){
			if ($minutes == 1){
				return "<script>
							if (localStorage.lang == 0){ 
								document.write('one minute ago'); 
							}else if(localStorage.lang == 1){
								document.write('satu menit yang lalu');
							}
						</script>";
			}else{
				return "$minutes "."<script>
										if (localStorage.lang == 0){ 
											document.write('minute ago'); 
										}else if(localStorage.lang == 1){
											document.write('menit yang lalu');
										}
									</script>";
			}
		}else if($hours <= 24){
			if ($hours == 1){
				return "<script>
							if (localStorage.lang == 0){ 
								document.write('an hour ago'); 
							}else if(localStorage.lang == 1){
								document.write('satu jam yang lalu');
							}
						</script>";
			}else{
				return "$hours "."<script>
									if (localStorage.lang == 0){ 
										document.write('hours ago'); 
									}else if(localStorage.lang == 1){
										document.write('jam yang lalu');
									}
								</script>";
			}
		}else if($days <= 7){
			if ($days == 1){
				return "<script>
							if (localStorage.lang == 0){ 
								document.write('yesterday'); 
							}else if(localStorage.lang == 1){
								document.write('kemarin');
							}
						</script>";
			}else{
				return "$days "."<script>
									if (localStorage.lang == 0){ 
										document.write('days ago'); 
									}else if(localStorage.lang == 1){
										document.write('hari yang lalu');
									}
								</script>";
			}
		}else if($weeks <= 4.3){
			if ($weeks == 1){
				return "<script>
							if (localStorage.lang == 0){ 
								document.write('a week ago'); 
							}else if(localStorage.lang == 1){
								document.write('seminggu yang lalu');
							}
						</script>";
			}else{
				return "$weeks "."<script>
									if (localStorage.lang == 0){ 
										document.write('weeks ago'); 
									}else if(localStorage.lang == 1){
										document.write('minggu yang lalu');
									}
								</script>";
			}
		}else if($months <= 12){
			if ($months == 1){
				return "<script>
							if (localStorage.lang == 0){ 
								document.write('a month ago'); 
							}else if(localStorage.lang == 1){
								document.write('sebulan yang lalu');
							}
						</script>";
			}else{
				return "$months "."<script>
										if (localStorage.lang == 0){ 
											document.write('a months ago'); 
										}else if(localStorage.lang == 1){
											document.write('bulan yang lalu');
										}
									</script>";
			}
		}else{
			if ($years == 1){
				return "<script>
							if (localStorage.lang == 0){ 
								document.write('a year ago'); 
							}else if(localStorage.lang == 1){
								document.write('setahun yang lalu');
							}
						</script>";
			}else{
				return "$years "."<script>
									if (localStorage.lang == 0){ 
										document.write('years ago'); 
									}else if(localStorage.lang == 1){
										document.write('tahun yang lalu');
									}
								</script>";
			}
		}
	}

	// FOR RENDERING OFFLINE MODE

	header("Cache-Control: Public");

	// SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

	// COUNT USER VISIT FOLLOWERS (SHOP)

	$query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE F_PIN = '$id_visit'");
	$query->execute();
	$user_followers_a = $query->get_result();
	$query->close();

	// SELECT USER - SHOP FOLLOW (VERSI SHOP KE USER)

	// $query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE F_PIN = '$id_visit'
	// AND STORE_CODE = '$id_shop'");
	// $query->execute();
	// $follback_check = $query->get_result()->fetch_assoc();
	// $query->close();

	// SELECT USER - SHOP FOLLOW (VERSI USER KE USER)

	$query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '$id_user'
	AND L_PIN = '$id_visit'");
	$query->execute();
	$follback_check = $query->get_result()->fetch_assoc();
	$query->close();

	// SELECT USER HIGHLIGHT

	$query = $dbconn->prepare("SELECT * FROM USER_HIGHLIGHT WHERE F_PIN = '".$id_visit."' AND IS_QIOSK = 1");
	$query->execute();
	$user_highlight = $query->get_result();
	$query->close();

	// COUNT USER FOLLOWERS (USER)

	$query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE L_PIN = '$id_visit'");
	$query->execute();
	$user_followers_b = $query->get_result();
	$query->close();

	// COUNT USER FOLLOWING (USER)

	$query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '$id_visit'");
	$query->execute();
	$user_following_b = $query->get_result();
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
	<link href="../assets/css/tab5-collection-style.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" id="main-menu" style="display:none">
	<img src="../assets/img/lbackground_1.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

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
	</style>

	<!-- NAVBAR -->

	<div id="header" class="container-fluid">
		<div class="col-12 col-md-12 col-lg-12">
			<div class="row align-items-center" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; padding: 10px 0 30px 0; padding-bottom: 65px">
				<div class="col-1 col-md-12 col-lg-12">
					
					<?php if ($_GET['src'] == "cc"): ?>
						<img src="../assets/img/icons/Back-(White).png" onclick="goBackTwice()" style="width:30px">
					<?php else: ?>
						<!-- <a href="tab5-followers.php"> -->
						<img src="../assets/img/icons/Back-(White).png" onclick="goBack()" style="width:30px">
						<!-- </a> -->
					<?php endif; ?>

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

	<!-- SECTION PROFILE -->

	<div class="section-profile" style="border-top-right-radius: 35px; background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>">
		
		<div class="container" id="profile-menu">

			<!-- <script>
				if (localStorage.lang == 1){
					document.getElementById("profile-menu").style.visibility = "hidden";
				}
			</script> -->
	
			<?php if ($user) : ?>

				<div class="row mb-3">
					<div class="col-3 col-md-3 col-lg-3" onclick="profileAndroid()">

						<!-- IF USER DOESN'T HAVE PICTURE USE DEFAULT -->

						<?php if ($user["IMAGE"]): ?>
							<img src="https://newuniverse.io/filepalio/image/<?= $user['IMAGE'] ?>" class="ava-profile">
						<?php else: ?>
							<img src="../assets/img/tab5/no-avatar.jpg" class="ava-profile">
						<?php endif; ?>

					</div>
					<div class="col-6 col-md-6 col-lg-6" style="margin-left: -5px; margin-right: 5px">
						<div class="row profile-name" onclick="profileAndroid()" style="font-size: 12px; margin-bottom: 4px">
							<b><?= $user['FIRST_NAME'] . " " . $user['LAST_NAME'] ?></b>
						</div>
						<img src="../assets/img/icons/Delivery-Address-black.png" alt="" srcset="" height="12px;" style="position: absolute; margin-left: -15px; margin-top: 2px">
						
						<?php if ($user['ADDRESS']): ?>
							<div class="row small-text" style="font-size:10px; margin-left:0px"><?= $user['ADDRESS'] ?></div>
						<?php else: ?>
							<div class="row small-text" style="font-size:10px; margin-left:0px">Jakarta, Indonesia</div>
						<?php endif; ?>

						<div style="margin-top: 3px; margin-left:-3px">
							<span class="small-text followers-slot">
								<span class="text-purple small-text follow-number">
									<b><?= mysqli_num_rows($user_followers_a) + mysqli_num_rows($user_followers_b) ?></b>
								</span>
								<b data-translate="tab5main-2">Followers</b>
							</span>
							<span class="small-text">
								<span class="text-purple small-text follow-number">
									<b><?= mysqli_num_rows($user_following_a) + mysqli_num_rows($user_following_b) ?></b>
								</span>
								<b data-translate="tab5main-3">Following</b>
							</span>
						</div>
					</div>
					<div class="col-3 col-md-3 col-lg-3">

						<!-- VERSI SHOP FOLLOW USER -->

						<!-- <?php if (isset($_SESSION['id_shop'])): ?>
							<?php if (!isset($follback_check)): ?>
								<form action="../logics/tab5/shop_following?src=profile" method="POST">
									<input type="hidden" name="f_pin" value="<?= $id_visit ?>">
									<input type="hidden" name="store_code" value="<?= $id_shop ?>">
									<button class="account-button small-text fw-bold" data-translate="tab5main-8">Follow</button>
								</form>
							<?php else: ?>
								<form action="../logics/tab5/shop_unfollow?src=profile" method="POST">
									<input type="hidden" name="f_pin" value="<?= $id_visit ?>">
									<input type="hidden" name="store_code" value="<?= $id_shop ?>">
									<button class="account-button small-text fw-bold" data-translate="tab5main-9">Unfollow</button>
								</form>
							<?php endif; ?>
						<?php endif; ?> -->

						<!-- VERSI USER FOLLOW USER -->

						<?php if (!isset($follback_check)): ?>
							<form action="../logics/tab5/user_following?src=profile" method="POST">
								<input type="hidden" name="f_pin" value="<?= $id_user ?>">
								<input type="hidden" name="l_pin" value="<?= $id_visit ?>">
								<button class="account-button small-text fw-bold" data-translate="tab5main-8">Follow</button>
							</form>
						<?php else: ?>
							<form action="../logics/tab5/user_unfollow?src=profile" method="POST">
								<input type="hidden" name="f_pin" value="<?= $id_user ?>">
								<input type="hidden" name="l_pin" value="<?= $id_visit ?>">
								<button class="account-button small-text fw-bold" style="background-color: white; color: #000000" data-translate="tab5main-9">Unfollow</button>
							</form>
						<?php endif; ?>

						<button class="account-button small-text fw-bold" style="background-color: white; color: #000000" data-translate="tab5main-11" onclick="Android.openMessage('<?= $id_visit ?>')">Message</button>

					</div>
				</div>

				<!-- User Tidak Ada (Data Dummy) -->

			<?php else: ?>

				<div class="row mb-3">
					<div class="col-3 col-md-3 col-lg-3">
						<img src="../assets/img/tab5/no-avatar.jpg" class="ava-profile">
					</div>
					<div class="col-9 col-md-9 col-lg-9">
						<div class="row profile-name">
							<b>ID User Dummy (ID = <?= $id_visit ?>)</b>
						</div>
						<div class="row small-text">Jakarta, Indonesia</div>
						<div style="margin-top: 3px">
							<span class="small-text followers-slot">
								<span class="text-purple small-text follow-number">
									<b>0</b>
								</span>
								<b>Followers</b>
							</span>
							<span class="small-text">
								<span class="text-purple small-text follow-number">
									<b>0</b>
								</span>
								<b>Following</b>
							</span>
						</div>
					</div>
				</div>

			<?php endif; ?>

			<div class="row small-text">

				<?php if ($user["QUOTE"]): ?>
					<div class="user-desc" style="width:80%">
						<?= $user['QUOTE'] ?>
					</div>
				<?php else: ?>
					<div class="user-desc" style="width:80%">
						Description of this user has not been set.
					</div>
				<?php endif; ?>

				<?php if ($user["WEB"]): ?>
					<div class="user-website mb-3" style="color:#378ff3; margin-top: 1px">
						<?= $user['WEB'] ?>
					</div>
				<?php else: ?>
					<div class="user-website mb-3" style="color:#378ff3">
						www.yourprofilewebsite.com
					</div>
				<?php endif; ?>

			</div>
		</div>

		<div class="row small-text gx-0" style="background-color: transparent;">
			<div class="container">
				<ul class="nav nav-tabs horizontal-slide gx-0" style="border-bottom: none">

					<!-- LOOP 10 SLOT STORY -->

					<?php foreach ($user_highlight as $highlight):

					// SELECT USER HIGHLIGHT DETAILS

					$query = $dbconn->prepare("SELECT * FROM USER_HIGHLIGHT_DETAILS WHERE HIGHLIGHT_CODE = '".$highlight['CODE']."'");
					$query->execute();
					$highlight_details = $query->get_result();
					$query->close();  

					while ($new_highlight_details = $highlight_details->fetch_assoc()){
						$highlight_sub[] = $new_highlight_details;
					};

					// SEND TO JS FUNCTION
					$thumb_id_array = array();
					$text_array = array();
					$mute_array = array();
					$product_code_array = array();

					foreach ($highlight_sub as $hs){
						array_push($thumb_id_array, $hs['THUMB_ID']);
						array_push($text_array, $hs['TEXT']);
						array_push($mute_array, $hs['MUTE']);
						array_push($product_code_array, $hs['PRODUCT_CODE']);
					}

					?>

					<?php $thumbnail = explode('|', $thumb_id_array[0]);
						 $thumb_id = implode(",", $thumb_id_array);
						 $text = implode(",", $text_array);
						 $mute = implode(",", $mute_array);
						 $product_code = implode(",", $product_code_array); ?>

					<?php if (substr($thumbnail[0], -3) == "mp4"): ?>

						<li class="nav-item text-center" style="margin-right: 10px">
							<video src="../images/<?= $thumbnail[0] ?>#t=0.5" style="background-color: #C3C3C3; height: 60px; width: 60px; border-radius: 50%; object-fit: cover; object-position: center" 
							onclick="openHighlight('<?= $thumb_id ?>','<?= $highlight['TITLE'] ?>','<?= $product_code ?>',
							'<?= $text ?>','<?= $mute ?>')"></video><br />
							
							<span class="small-text"><?= $highlight['TITLE'] ?></span>
						</li>

					<?php else: ?>

						<li class="nav-item text-center" style="margin-right: 10px">
							<img src="../images/<?= $thumbnail[0] ?>" style="background-color: #C3C3C3; height: 60px; width: 60px; border-radius: 50%; margin-bottom: 4px; object-fit: cover; object-position: center" 
							onclick="openHighlight('<?= $thumb_id ?>','<?= $highlight['TITLE'] ?>',
							'<?= $product_code ?>','<?= $text ?>', '<?= $mute ?>')"><br />
							
							<span class="small-text"><?= $highlight['TITLE'] ?></span>
						</li>

					<?php endif;

					$thumb_id_array = null;
					$text_array = null;
					$mute_array = null;
					$product_code_array = null;
					$new_highlight_details = null;
					$highlight_sub = null;

					endforeach; ?>

				</ul>
			</div>
		</div>
	</div>

	<!-- SECTION COLLECTION -->

	<div class="section-collection" id="collection-menu">

		<!-- <script>
			if (localStorage.lang == 1){
				document.getElementById("collection-menu").style.visibility = "hidden";
			}
		</script> -->

		<div class="container">
			<div class="row small-text mb-3 mt-3">
				<div class="col-12 col-md-12 col-lg-12">
					<b data-translate="tab5main-4">All Collection</b>
					<b> (<?= mysqli_num_rows($user_collection) ?>)</b>
				</div>
			</div>

			<div class="row gx-3">

				<!-- IF USER HAVE COLLECTION -->

				<?php if (mysqli_num_rows($user_collection) > 0) : ?>

					<?php foreach ($user_collection as $key => $collection) : ?>

						<!-- SELECT SUB COLLECTION ITEMS -->

						<?php
						$query = $dbconn->prepare("SELECT * FROM COLLECTION_PRODUCT JOIN PRODUCT ON 
										COLLECTION_PRODUCT.PRODUCT_CODE	= PRODUCT.CODE WHERE 
										COLLECTION_PRODUCT.COLLECTION_CODE = '" . 
										$collection['COLLECTION_CODE'] . "'");
						$query->execute();
						$sub_collection = $query->get_result()->fetch_assoc();
						$query->close();

						// FOR LOAD FROM POST TABLE (FOR IMAGE)

						if ($sub_collection['IS_POST'] == 1){

							$query = $dbconn->prepare("SELECT * FROM COLLECTION_PRODUCT JOIN POST ON 
														COLLECTION_PRODUCT.PRODUCT_CODE	= POST.POST_ID WHERE 
														COLLECTION_PRODUCT.COLLECTION_CODE = '" . 
							$collection['COLLECTION_CODE'] . "'");
							$query->execute();
							$sub_collection = $query->get_result()->fetch_assoc();
							$query->close();

						}

						// COUNT SUB COLLECTION ITEMS

						$query = $dbconn->prepare("SELECT * FROM COLLECTION_PRODUCT WHERE 
													COLLECTION_PRODUCT.COLLECTION_CODE = 
													'" . $collection['COLLECTION_CODE'] . "'");
						$query->execute();
						$count_items = $query->get_result();
						$query->close();
						?>

						<!-- LOOP FIRST AND SECOND WILL BE BIG SIZE ELSE WILL BE SMALL SIZE -->

						<?php if ($key < 2) : ?>

							<div class="big-collection">

								<?php $product_image = explode('|', $sub_collection['THUMB_ID']); ?>

								<a href="tab5-collection.php?collection_code=<?= $sub_collection['COLLECTION_CODE'] ?>&id_visit=<?= $id_visit ?>">

									<!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

									<?php $i = 0; ?>

									<?php if (substr($product_image[$i], -3) == "mp4") : ?>

										<!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

										<?php 
										while (substr($product_image[$i], -3) == "mp4"):
											$product_image_video = $product_image[$i+1];
											$i++;
										endwhile; 
										?>

										<!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

										<?php if ($product_image_video): ?>
											<img src="../images/<?= $product_image_video ?>" class="single-big-collection" style="border-radius: 10px">
										<?php else: ?>
											<video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="single-big-collection"></video>
										<?php endif; ?>

									<?php else : ?>

										<img src="../images/<?= $product_image[$i] ?>" class="single-big-collection" style="border-radius: 10px">

									<?php endif; ?>

								</a>

								<div class="big-collection-desc">
									<div class="smallest-text"><?= mysqli_num_rows($count_items) ?> <span data-translate="tab5main-6">Items | Updated</span> <?= timeAgo($collection['CREATED_AT']) ?></div>
										<div>
											<b><?= $collection['NAME'] ?></b>
										</div>
									<div class="small-text"><?= $collection['DESCRIPTION'] ?></div>
								</div>
							</div>

						<?php elseif ($key > 1) : ?>

							<div class="col-6 col-md6 col-lg-6">
								<div class="small-collection">

									<?php $product_image = explode('|', $sub_collection['THUMB_ID']); ?>

									<a href="tab5-collection.php?collection_code=<?= $sub_collection['COLLECTION_CODE'] ?>&id_visit=<?= $id_visit ?>">
									
									<!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

									<?php $i = 0; ?>

									<?php if (substr($product_image[$i], -3) == "mp4") : ?>

										<!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

										<?php 
										while (substr($product_image[$i], -3) == "mp4"):
											$product_image_video = $product_image[$i+1];
											$i++;
										endwhile; 
										?>

										<!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

										<?php if ($product_image_video): ?>
											<img src="../images/<?= $product_image_video ?>" class="single-big-collection" style="border-radius: 10px">
										<?php else: ?>
											<video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="single-small-collection"></video>
										<?php endif; ?>

									<?php else : ?>

										<img src="../images/<?= $product_image[$i] ?>" class="single-small-collection" style="border-radius: 10px">

									<?php endif; ?>
									</a>
									<div class="small-collection-desc">
										<b><?= $collection['NAME'] ?></b>
									</div>
								</div>
							</div>

						<?php endif; ?>
					<?php endforeach; ?>
				<?php else : ?>

					<p class="text-center small-text mt-3 text-grey" data-translate="tab5main-12">This person dont have a collection yet.</p>

				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- HIGHLIGHT MODAL -->

	<div class="modal fade" id="highlightModal" tabindex="-1" aria-labelledby="highlightModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">
				
				<span class="loading_div row gx-0">
					<!-- <div style="width: 50%; height: 7px; background-color: grey; border-radius: 3%">
						<div id="highlight_loading" style="width: 0%; height: 7px; border-radius: 3%; background-color: #FFFFFF"></div>
					</div> -->
				</span>

				<div class="d-flex justify-content-center">
					<p id="highlight_title" style="color: #FFFFFF; position: absolute; margin-top: 15px; z-index: 9999"></p>
				</div>
				<div class="text-center" style="position: absolute; margin-top: 20px; ">
					<a href="tab5-main.php" style="margin-left: 10px; position: absolute;">
						<img src="../assets/img/tab5/Back-(White).png" class="navbar-back-white">
					</a>
				</div>

				<div id="carouselExampleControls" style="height:100%" class="carousel slide" data-bs-ride="carousel" data-bs-interval="60000">
					<div class="carousel-inner" data-bs-dismiss="modal" style="height:100%" >
						<!-- <div class="carousel-item active" style="height:100%">
							<img id="image_highlight_show_0" class="d-block w-100" alt="">
						</div> -->
					</div>
					<div class="btn-image">
						<button class="carousel-control-prev" id="btnLeft" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</button>
						<button class="carousel-control-next" id="btnRight" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</button>
					</div>
				</div>

				<!-- <img src="" id="image_highlight_show_0" style="height:100%; object-fit: cover"> -->
				<p id="text" style="padding: 20px; text-align: center; font-size: 25px; margin-bottom: 175px; width: 100%; height: 150px; bottom: 0; position: fixed; border: none; outline: none; background-color: transparent; color: white"></p>
				
				<!-- SECTION TAGGED PRODUCT -->

				<div class="row" id="tagged_product" style="position: absolute; left: 0; bottom: 0; margin-bottom: 30px; margin-left: 10px">
				<!-- <div class="row" style="margin-bottom: 20px">
						<div class="col-1 col-md-2 col-lg-2">
							<img src="../assets/img/tab5/Tagged-Product(white).png" style="background-color: #FFA03E; width:20px; height: 20px; padding: 3px; margin-top: 4px; position: absolute; border-radius: 20px;">
						</div>
						<div class="col-11 col-md-10 col-lg-10">
							<span class="small-text" style="color: #FFFFFF" id="shop_name"><b>Shop Name</b></span>
						</div>
					</div>
					<div class="col-3 col-md-4 col-lg-4">
						<img src="../assets/img/tab5/Shop Manager/noimage-large.jpg" id="product_image" style="width: 70px; height: 70px; object-fit: cover; object-position: center">
					</div>
					<div class="col-9 col-md-8 col-lg-8" style="margin-top: -8px">
						<span id="product_name" class="small-text" style="color: #FFFFFF">Shop Product</span>
						<p class="small-text" id="product_price" style="color: #FFFFFF; margin-top: 15px">Shop Price</p>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="../assets/js/update_counter.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage() {

			var lang = localStorage.lang;
			change_lang(lang);
			$('body').show();
		}

		changeLanguage();

		if (localStorage.lang == 1) {
			$('#query').attr('placeholder', 'Pencarian');
			// document.getElementById("profile-menu").style.visibility = "visible";
			// document.getElementById("collection-menu").style.visibility = "visible";
		}else if(localStorage.lang == 0){
			$('#query').attr('placeholder', 'Search');
		}
	});

	// HIGHLIGHT FUNCTION MODAL

	var is_video;
	var position = 0;
	var time_photo = 5000;
	var time_video = 1000;
	var total_time = 0;
	var already_pass = false;

	function openHighlight(thumb_id, title, product_code, text, mute){

		if (window.Android){
			Android.openFullscreen();
		}

		// alert(product_code);

		var thumb_id_split = thumb_id.split(",");

		if (mute.length > 0){
			var mute = mute.split(",");
		}

		if (text.length > 0){
			var text = text.split(",");
		}

		if (product_code.length > 0){
			var product_code = product_code.split(",");
		}

		var thumb_id_length = thumb_id.split(",").length;

		console.log("Position :"+position);

		if (thumb_id_length <= 1){
			$('.carousel-control-next-icon').hide();
			$('.carousel-control-prev-icon').hide();
		}else{
			$('.carousel-control-next-icon').show();
			$('.carousel-control-prev-icon').show();
		}

		var product_html_array = [];
		var slide_html_array = [];

		for (var i=0; i < thumb_id_length; i++){

			var html = `<div style="width: `+100/thumb_id_length+`%; height: 7px; background-color: grey; border-radius: 3%">
							<div id="highlight_loading_`+i+`" class="loading" style="width: 0%; height: 7px; border-radius: 3%; background-color: #FFFFFF"></div>
						</div>`;

			$('.loading_div').append(html);

			if ((thumb_id_split[i]).slice(-3) == 'mp4'){

				is_video = 1;

				if (i<1){
					if (mute==1){
						var html = `<div class="carousel-item active" style="height: 100%">
										<video preload='metadata' muted id="image_highlight_show_`+i+`" src='../images/`+thumb_id_split[i]+`' loop style="height:100%; object-fit: cover" class="d-block w-100" src="..." alt=""></video>
									</div>`;
					}else{
						var html = `<div class="carousel-item active" style="height: 100%">
										<video preload='metadata' id="image_highlight_show_`+i+`" src='../images/`+thumb_id_split[i]+`' loop style="height:100%; object-fit: cover" class="d-block w-100" src="..." alt=""></video>
									</div>`;
					}
				}else{
					if (mute==1){
						var html = `<div class="carousel-item" style="height: 100%">
										<video preload='metadata' muted id="image_highlight_show_`+i+`" src='../images/`+thumb_id_split[i]+`' loop style="height:100%; object-fit: cover" class="d-block w-100" src="..." alt=""></video>
									</div>`
					}else{
						var html = `<div class="carousel-item" style="height: 100%">
										<video preload='metadata' id="image_highlight_show_`+i+`" src='../images/`+thumb_id_split[i]+`' loop style="height:100%; object-fit: cover" class="d-block w-100" src="..." alt=""></video>
									</div>`;
					}
				}

				$('.carousel-inner').append(html);

			}else{

				is_video = 0;

				if (i<1){
					var html = `<div class="carousel-item active" style="height: 100%">
									<img id="image_highlight_show_`+i+`" src='../images/`+thumb_id_split[i]+`' style="height:100%; object-fit: cover" class="d-block w-100" src="..." alt="">
								</div>`

				}else{
					var html = `<div class="carousel-item" style="height: 100%">
									<img id="image_highlight_show_`+i+`" src='../images/`+thumb_id_split[i]+`' style="height:100%; object-fit: cover" class="d-block w-100" src="..." alt="">
								</div>`
				}

				$('.carousel-inner').append(html);

			}
		}

		// SELECT TAGGED PRODUCT FROM XHTTPREQUEST

		if (product_code != "" && product_code != 'undefined' && product_code != null){

			if (product_code[0] != "" && product_code[0] != 'undefined' && product_code[0] != null){
				getProduct(product_code, 0, 0);
			}else{
				slide_html_array.push("");
				getProduct(product_code, 1, 0);
			}
		}

		function getProduct(product_code, slide_position, product_position){

			if (product_code[slide_position] != "" && product_code[slide_position] != 'undefined' && product_code[slide_position] != null){

				console.log(product_code[slide_position].split("|"));
				var slide_product_split = product_code[slide_position].split("|");
				var single_product_split = slide_product_split[product_position];

				// console.log(slide_product_split.length);

				var formData = new FormData();
				formData.append('product_code', single_product_split);
			
				let xmlHttp = new XMLHttpRequest();
				xmlHttp.onreadystatechange = function (){

					if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
						var objResult = JSON.parse(xmlHttp.responseText);

						$("#tagged_product").html("");

						Object.keys(objResult).forEach(function (item) {

							// IF IMAGE WAS VIDEO MOVE IT TO GET IMAGE

							var product_image = objResult[item]['THUMB_ID'].split("|");
							var i = 0;

							if ((product_image[i]).slice(-3) == 'mp4'){

								while (i < product_image.length){

									// WHEN GET PICTURES ON CENTER [ EX 1,3 VIDEO, 2 PICTURE, BREAK AT 2 ]

									if ((product_image[i]).slice(-3) == 'mp4'){
										var product_image_video = product_image[(i+1)];
									}else{
										break;
									}
									
									i++;
								}

								// IF GET PICTURE TAG = IMG

								if (product_image_video){

									product_image_video = product_image_video;
									var ext = '<img';
									var ext2 = '';

								// IF THERE IS NO PICTURE ON PRODUCT TAG = VIDEO
									
								}else{

									product_image_video = product_image[0];
									var ext = '<video';
									var ext2 = '</video>';
								}
							}else{

								product_image_video = product_image[0];
								var ext = '<img';
								var ext2 = '';
							}

							// SET TAGGED PRODUCT TO HIGHLIGHT

							var html = `<div class="row" style="margin-bottom: 20px">
											<div class="col-1 col-md-2 col-lg-2">
												<img src="../assets/img/tab5/Tagged-Product(white).png" style="background-color: #FFA03E; width:20px; height: 20px; padding: 3px; margin-top: 4px; position: absolute; border-radius: 20px;">
											</div>
											<div class="col-11 col-md-10 col-lg-10">
												<span class="small-text" style="color: #FFFFFF; padding-left: 15px" id="shop_name"><b>`+objResult[item]['SNAME']+`</b></span>
											</div>
										</div>
										<div class="col-3 col-md-4 col-lg-4" style="margin-bottom: 15px">
											`+ext+` src="../images/`+product_image_video+`" id="product_image" style="width: 70px; height: 70px; object-fit: cover; object-position: center">`+ext2+`
										</div>
										<div class="col-9 col-md-8 col-lg-8" style="margin-top: -8px; margin-bottom: 15px">
											<span id="product_name" class="small-text" style="color: #FFFFFF">`+objResult[item]['NAME']+`</span>
											<p class="small-text" id="product_price" style="color: #FFFFFF; margin-top: 15px">Rp `+Number(objResult[item]['PRICE']).toLocaleString()+`</p>
										</div>`;

							product_html_array.push(html);

							product_position = product_position + 1;

							if (product_position < slide_product_split.length){
								getProduct(product_code, slide_position, product_position);
							}else{
								
								slide_html_array.push(product_html_array);
								console.log("PUSH");

								slide_position = slide_position + 1;

								if (slide_position < product_code.length){

									product_html_array = [];
									getProduct(product_code, slide_position, 0);

								}else{
									console.log(slide_html_array);
									$("#tagged_product").append(slide_html_array[0]);
								}
							}
						});
					}
				}

				xmlHttp.open("post", "../logics/tab5/get_tagged_highlight");
				xmlHttp.send(formData);	
			}else{
				slide_html_array.push("");

				if (slide_position < product_code.length){
					slide_position = slide_position + 1;
					getProduct(product_code, slide_position, 0);
				}else{
					$("#tagged_product").append(slide_html_array[0]);
				}
			}
		}

		$('#text').text(text[0]);

		// RUN AUTO SLIDE FUNCTION

		if ((thumb_id_split[0]).slice(-3) != 'mp4'){
			functionPhoto(0);
		}else{
			functionVideo(0);
		}

		function functionPhoto(i){

			$('#highlight_loading_'+i).animate({
				width: '100%'
			}, time_photo);

			if ($('#image_highlight_show_'+(i+1)).is('img')){

				loadingNewSlide = setTimeout(function(){ 

					$('#carouselExampleControls').carousel(i+1); 
					position = position + 1;
					functionPhoto(i+1);
					$('#text').text(text[position]);
					$("#tagged_product").html("");
					$("#tagged_product").append(slide_html_array[position]);

				}, time_photo);

			}else if($('#image_highlight_show_'+(i+1)).is('video')){

				loadingNewSlide = setTimeout(function(){ 

					$('#carouselExampleControls').carousel(i+1); 
					position = position + 1;
					already_pass = true;

					functionVideo(i+1);
					$('#text').text(text[position]);
					$("#tagged_product").html("");
					$("#tagged_product").append(slide_html_array[position]);

				}, time_photo);

			}else{

				loadingNewSlide = setTimeout(function(){ 
					$('#highlightModal').modal('hide'); 
				}, time_photo);
			}
		}

		function functionVideo(i){
			var video_duration = document.getElementById('image_highlight_show_'+i);

			if(already_pass == false){
				already_pass = true;

				video_duration.onloadedmetadata = function() {

					video_duration = video_duration.duration;
					// alert("Video ke :"+i+" Waktunya :"+video_duration);

					$('#image_highlight_show_'+i).get(0).play();

					$('#highlight_loading_'+i).animate({
						width: '100%'
					}, (video_duration*time_video));

					if ($('#image_highlight_show_'+(i+1)).is('img')){

						loadingNewSlide = setTimeout(function(){ 

							$('#carouselExampleControls').carousel(i+1);
							position = position + 1; 
							functionPhoto(i+1);
							$('#text').text(text[position]);
							$("#tagged_product").html("");
							$("#tagged_product").append(slide_html_array[position]);

						}, (video_duration*time_video));

					}else if($('#image_highlight_show_'+(i+1)).is('video')){

						loadingNewSlide = setTimeout(function(){ 

							$('#carouselExampleControls').carousel(i+1); 
							position = position + 1; 
							functionVideo(i+1);
							$('#text').text(text[position]);
							$("#tagged_product").html("");
							$("#tagged_product").append(slide_html_array[position]);

						}, (video_duration*time_video));

					}else{

						loadingNewSlide = setTimeout(function(){ 
							$('#highlightModal').modal('hide'); 
						}, (video_duration*time_video));
					}
				}
			}else{
				video_duration = video_duration.duration;
				// alert("Video ke :"+i+" Waktunya :"+video_duration);

				$('#image_highlight_show_'+i).get(0).play();

				$('#highlight_loading_'+i).animate({
					width: '100%'
				}, (video_duration*time_video));

				if ($('#image_highlight_show_'+(i+1)).is('img')){

					loadingNewSlide = setTimeout(function(){ 

						$('#carouselExampleControls').carousel(i+1); 
						position = position + 1; 
						functionPhoto(i+1);
						$('#text').text(text[position]);
						$("#tagged_product").html("");
						$("#tagged_product").append(slide_html_array[position]);

					}, (video_duration*time_video));

				}else if($('#image_highlight_show_'+(i+1)).is('video')){

					loadingNewSlide = setTimeout(function(){ 

						$('#carouselExampleControls').carousel(i+1); 
						position = position + 1; 
						functionVideo(i+1);
						$('#text').text(text[position]);
						$("#tagged_product").html("");
						$("#tagged_product").append(slide_html_array[position]);

					}, (video_duration*time_video));

				}else{

					loadingNewSlide = setTimeout(function(){ 
						$('#highlightModal').modal('hide'); 
					}, (video_duration*time_video));
				}
			}
		}

		// ON BUTTON IMAGE CLICK

		$("#btnRight").off().click(function() {

		position = position + 1;
		$('#text').text("");
		$('#text').text(text[position]);
		$("#tagged_product").html("");
		$("#tagged_product").append(slide_html_array[position]);

		$('#highlight_loading_'+(position-1)).stop();
		$('#highlight_loading_'+(position-1)).css('width','100%');

		if (is_video == 1 && $('#image_highlight_show_'+position).is('img') || $('#image_highlight_show_'+position).is('video')){
			var vid = document.getElementById('image_highlight_show_'+position);
			vid.currentTime = 0;
		}

		if (position >= thumb_id_length){
			$('#highlightModal').modal('hide');
		}

		clearTimeout(loadingNewSlide);

		if ($('#image_highlight_show_'+position).is('img')){
			functionPhoto(position);
		}else if($('#image_highlight_show_'+position).is('video')){
			already_pass = true;
			functionVideo(position);
		}

		console.log("Position:" + position);

		});

		$("#btnLeft").off().click(function() {

			position = position - 1;
			$('#text').text("");
			$('#text').text(text[position]);
			$("#tagged_product").html("");
			$("#tagged_product").append(slide_html_array[position]);

			$('#highlight_loading_'+position).stop();
			$('#highlight_loading_'+position).css('width','0%');
			$('#highlight_loading_'+(position+1)).stop();
			$('#highlight_loading_'+(position+1)).css('width','0%');

			if (is_video == 1 && position >= 0){
				var vid = document.getElementById('image_highlight_show_'+position);
				vid.currentTime = 0;
			}

			if (position < 0){
				$('#highlightModal').modal('hide');
			}

			clearTimeout(loadingNewSlide);

			if ($('#image_highlight_show_'+position).is('img')){
				functionPhoto(position);
			}else if($('#image_highlight_show_'+position).is('video')){
				functionVideo(position);
			}

			console.log("Position:" + position);

		});

		// SET FROM JS TO HTML

		$('#highlight_title').text(title);
		$('#highlightModal').modal('show');

	}

	// ON CLOSE HIGHLIGHT

	$('#highlightModal').on('hidden.bs.modal', function () {

		if (window.Android){
			Android.closeFullscreen();
		}
		
		$('#highlight_loading_0').stop();
		$("#highlight_loading_0").css("width","0%");
		
		clearTimeout(loadingNewSlide);

		if (is_video == 1){
			$('#image_highlight_show_0').get(0).pause();
		}

		// CLEAR HTML AND RESET HTML WHILE CLOSE MODAL

		$('#tagged_product').html("");
		$('.loading_div').html("");
		$('.carousel-inner').html("");
		$('#text').text("");
		already_pass = false;

		position = 0;
		total_time = 0;

		// RESET CAROUSEL TO 0

		$('#carouselExampleControls').carousel(0); 
		
		console.log("Position:" + position);
		
	})

	// FUNCTION VOICE SEARCH

	function voiceSearch(){
		Android.toggleVoiceSearch();
	}

	function submitVoiceSearch(searchQuery){
		$('#query').val(searchQuery);
		$('#delete-query').removeClass('d-none');
	}

	// GO BACK BUTTON

	function goBack() {
    	window.history.back();
  	}

	function goBackTwice() {
    	window.history.go(-3);
  	}

	// FUNCTION SAVE SEARCH

	$('#query').on('change', function(){
		localStorage.setItem("search_keyword", this.value);
	});

	// FUNCTION X ON SEARCH

	$("#delete-query").click(function (){
		$('#query').val('');
		$('#delete-query').addClass('d-none');
	})

	$('#query').keyup(function (){
		console.log('is typing: ' + $(this).val());

		if ($(this).val() != '') {
		$('#delete-query').removeClass('d-none');
		} else {
		$('#delete-query').addClass('d-none');
		}
	})
	
</script>
</html>