<?php

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

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

    // SELECT USER & SET TO SESSION

    $products = [];
    $collection_code = $_GET['collection_code'];
    $id_shop = $_SESSION['id_shop'];

    // CHECK IF USER ALREADY HAVE SHOP TO FOLLOW USER

	$is_shop = $_SESSION['is_shop'];

    // GET COLLECTION FROM DATABASE

    $query = $dbconn->prepare("SELECT c.ID, c.F_PIN AS COLLECTION_OWNER, u.FIRST_NAME AS OWNER_FIRST_NAME, u.LAST_NAME AS OWNER_LAST_NAME, u.IMAGE AS AVATAR, 
                                c.COLLECTION_CODE, c.NAME AS COLLECTION_NAME, c.DESCRIPTION AS COLLECTION_DESCRIPTION, c.TOTAL_VIEWS, c.CREATED_AT, 
                                p.CODE AS PRODUCT_CODE, p.NAME AS PRODUCT_NAME, p.DESCRIPTION AS PRODUCT_DESCRIPTION, p.THUMB_ID AS PRODUCT_THUMBNAIL, p.PRICE AS PRODUCT_PRICE, 
                                s.NAME AS SHOP_NAME, s.DESCRIPTION AS SHOP_DESCRIPTION, s.THUMB_ID AS SHOP_THUMBNAIL,
                                ule.ADDRESS AS ADDRESS, ule.WEB AS WEB, u.QUOTE AS QUOTE
                                FROM COLLECTION c LEFT JOIN USER_LIST u ON c.F_PIN = u.F_PIN 
                                LEFT JOIN COLLECTION_PRODUCT cp ON c.COLLECTION_CODE = cp.COLLECTION_CODE
                                LEFT JOIN PRODUCT p ON cp.PRODUCT_CODE = p.CODE
                                LEFT JOIN SHOP s ON p.SHOP_CODE = s.CODE
                                LEFT JOIN USER_LIST_EXTENDED ule ON u.F_PIN = ule.F_PIN 
                                WHERE c.COLLECTION_CODE = '$collection_code' AND cp.IS_POST = 0");
    $query->execute();
    $productsPurchase = $query->get_result();
    $query->close();

    while ($row = $productsPurchase->fetch_array(MYSQLI_ASSOC))
    {
        $products[] = $row;
    }

    $query = $dbconn->prepare("SELECT c.ID, c.F_PIN AS COLLECTION_OWNER, u.FIRST_NAME AS OWNER_FIRST_NAME, u.LAST_NAME AS OWNER_LAST_NAME, u.IMAGE AS AVATAR, 
                                c.COLLECTION_CODE, c.NAME AS COLLECTION_NAME, c.DESCRIPTION AS COLLECTION_DESCRIPTION, c.TOTAL_VIEWS, c.CREATED_AT, 
                                p.POST_ID AS PRODUCT_CODE, p.TITLE AS PRODUCT_NAME, p.DESCRIPTION AS PRODUCT_DESCRIPTION, p.THUMB_ID AS PRODUCT_THUMBNAIL, p.PRICING_MONEY AS PRODUCT_PRICE, 
                                s.NAME AS SHOP_NAME, s.DESCRIPTION AS SHOP_DESCRIPTION, s.THUMB_ID AS SHOP_THUMBNAIL,
                                ule.ADDRESS AS ADDRESS, ule.WEB AS WEB, u.QUOTE AS QUOTE
                                FROM COLLECTION c LEFT JOIN USER_LIST u ON c.F_PIN = u.F_PIN 
                                LEFT JOIN COLLECTION_PRODUCT cp ON c.COLLECTION_CODE = cp.COLLECTION_CODE
                                LEFT JOIN POST p ON cp.PRODUCT_CODE = p.POST_ID
                                LEFT JOIN SHOP s ON p.MERCHANT = s.CODE
                                LEFT JOIN USER_LIST_EXTENDED ule ON u.F_PIN = ule.F_PIN 
                                WHERE c.COLLECTION_CODE = '$collection_code' AND cp.IS_POST = 1");
    $query->execute();
    $productsPost = $query->get_result();
    $query->close();

    while ($row = $productsPost->fetch_array(MYSQLI_ASSOC))
    {
        $products[] = $row;
    }

    // CONVERT OBJECT TO ARRAY

    $collection_products = array();

    $i = 0;
    while ($i < count($products)){
        $owner = "{$products[$i]['OWNER_FIRST_NAME']} {$products[$i]['OWNER_LAST_NAME']}";
        $avatar = "{$products[$i]['AVATAR']}";
        $views = $products[$i]['TOTAL_VIEWS'] ?? 0;
        $created_at = $products[$i]['CREATED_AT'];
        $collection_name = $products[$i]['COLLECTION_NAME'];
        $collection_description = $products[$i]['COLLECTION_DESCRIPTION'];
        $collection_products[] = $products[$i];

        $address = $products[$i]['ADDRESS'];
        $web = $products[$i]['WEB'];
        $quote = $products[$i]['QUOTE'];
        $i++;
    };

    $total = count($collection_products);

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

	// SELECT USER FOLLOWING

	$query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW WHERE F_PIN = '$id_user'");
	$query->execute();
	$user_following_a = $query->get_result();
	$query->close();

    // COUNT USER VISIT FOLLOWERS (SHOP)

	$query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE F_PIN = '$id_user'");
	$query->execute();
	$user_followers_a = $query->get_result();
	$query->close();

	// SELECT USER - SHOP FOLLOW

	$query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE F_PIN = '$id_user'
								AND STORE_CODE = '$id_shop'");
	$query->execute();
	$follback_check = $query->get_result()->fetch_assoc();
	$query->close();

    // SELECT USER ALREADY HAVE SHOP

    $query = $dbconn->prepare("SELECT * FROM SHOP WHERE CREATED_BY = '$id_user'");
    $query->execute();
    $shop_check = $query->get_result()->fetch_assoc();
    $query->close();

    // SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

    // COUNT USER FOLLOWERS (USER)

	$query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE L_PIN = '$id_user'");
	$query->execute();
	$user_followers_b = $query->get_result();
	$query->close();

	// COUNT USER FOLLOWING (USER)

	$query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST WHERE F_PIN = '$id_user'");
	$query->execute();
	$user_following_b = $query->get_result();
	$query->close();


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Nexilis Sports</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <link href="../assets/css/tab5-style.css" rel="stylesheet">
    <link href="../assets/css/tab5-collection-style.css?random=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    
    <style>
/* FOR VERTICAL SCROLL NOT SHOWED UP */

::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        /* FOR RED DOT FORM */

        .form-group {
            position: relative;
        }

        .form-control:focus {
            background-color: #FAFAFF;
            box-shadow: none;
        }

        .palceholder {
            position: absolute;
            color: #797979;
            display: none;
        }

        .star {
            color: red;
            margin-left: -3px;
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
</head>

<body style="background: none; display:none">
    <img src="../assets/img/lbackground_1.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

    <div id="header" class="container-fluid">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="row align-items-center"  style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; padding: 10px 0 30px 0; padding-bottom: 55px">
                <div class="col-1 col-md-12 col-lg-12">
                    <a href="tab5-main">
                        <img src="../assets/img/icons/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div id="searchFilter-a" class="col-10 col-md-10 col-lg-10 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
                    <form autocomplete="off" id="searchFilterForm-a" action="search-result" method="GET" style="width: 95%;">

                        <?php
                            $query = "";
                            if (isset($_REQUEST['query'])) {
                                $query = $_REQUEST['query'];
                            }
                        ?>

                        <input id="query" placeholder="Search" type="text" class="search-query" name="query" value="<?= $query; ?>">
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
    <div class="container-fluid px-0" style="border-top-right-radius: 35px; background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; margin-bottom: 0px !important; z-index: 9999 !important; margin-top: -35px !important">
        <div class="container small-text pt-3 pb-3" style="border-top-right-radius: 35px;">
            <div class="row mb-3">
                <div class="col-3">
                    <?php if ($avatar): ?>
                        <img class="ava-profile" style="margin-top: 4px; margin-left: 9px" src="https://newuniverse.io/filepalio/image/<?= $avatar; ?>">
                    <?php else: ?>
                        <img class="ava-profile" style="margin-top: 4px; margin-left: 9px" src="../assets/img/tab5/no-avatar.jpg">
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <div class="col-12">
                        <div class="row py-1">
                            <div class="col p-0" style="font-size: 12px"><b><?= $owner; ?></b></div>
                        </div>
                        <div class="row">
                            <div class="col-1 p-0">
                                <img src="../assets/img/icons/Delivery-Address-black.png" alt="" srcset="" height="10px;">
                            </div>
                            
                            <?php if (isset($address)): ?>
                                <div class="col-11 p-0 text-black" style="font-size: 11px"><?= $address ?></div>
                            <?php else: ?>
                                <div class="col-11 p-0 text-black" style="font-size: 11px">Jakarta, Indonesia</div>
                            <?php endif; ?>
                            
                        </div>
                        <div style="margin-top: 5px;">
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
                </div>
                <div class="col-3">
                    <div class="px-1" style="margin-left: -8px; margin-top: 5px">

                    <a href="tab5.php">
                        <button class="account-button small-text fw-bold" data-translate="tab5collectionself-1">Account</button>
                    </a>

                    <?php if (isset($shop_check)): ?>
                        <a href="tab5-shop-manager.php?id=<?= $shop_check["CODE"] ?>">
                            <button class="tokoq-button small-text fw-bold" style="font-size: 11px">Nex-Shop</button>
                        </a>
                    <?php else: ?>
                        <a href="tab5-shop.php">
                            <button class="tokoq-button small-text fw-bold" style="font-size: 11px">Nex-Shop</button>
                        </a>
                    <?php endif; ?>

                    </div>
                </div>
            </div>
            <div class="container px-4 pt-1">
                <div class="col-12">

                    <?php if (isset($quote)): ?>
                        <div class="row"><?= $quote ?></div>
                    <?php else: ?>
                        <div class="row">Description of this user has not been set.</div>
                    <?php endif; ?>


                    <?php if (isset($web)): ?>
                        <div class="row"><a class="px-0" href="<?= $web ?>" style="color: #378ff3;"><?= $web ?></a></div>
                    <?php else: ?>
                        <div class="row"><a class="px-0" href="" style="color: #378ff3;">www.yourprofilewebsite.com</a></div>
                    <?php endif; ?>            
                        
                </div>
            </div>
        </div>
        <div class="pb-1">
            <div class="row gx-0">
            
            <?php

                $product_image = explode("|", $collection_products[0]['PRODUCT_THUMBNAIL']); ?>

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
                        <img src="../images/<?= $product_image_video ?>" alt="" srcset="" height="150px" style="object-fit: cover; border-radius: 10px">
                    <?php else: ?>
                        <video src="../images/<?= $product_image[0] ?>#t=0.5" type="video/mp4" alt="" srcset="" height="150px" style="object-fit: cover; border-radius: 10px"></video>
                    <?php endif; ?>

                <?php else: ?>

                    <img src="../images/<?= $product_image[$i] ?>" alt="" srcset="" height="150px" style="object-fit: cover; border-radius: 10px">

                <?php endif; 

                ?>
                
            </div>
            <div class="container small-text px-4">
                <div class="row">
                    <div class="col mt-2">
                        <?= $total; ?> <span data-translate="tab5collectionself-4">Items | Updated</span> <?= timeAgo($created_at) ?><img src="../assets/img/icons/eyes-black.png" width="15px" style="margin-left: 10px" alt=""> <?= $views; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10 mt-3">
                        <h5 class="fw-bold" id="title-text"><?= $collection_name; ?></h5>
                    </div>
                    <!-- <div onclick="editCollection();" class="col-2 mt-3 d-flex align-items-center justify-content-end">
                        <img src="../assets/img/icons/More.png" alt="" width="30px">
                    </div> -->
                    <div class="col-2 col-md-2 col-lg-2 mt-3 d-flex align-items-center justify-content-end" style="margin-top:-5px !important" data-bs-toggle="modal" data-bs-target="#editCollectionModal">
                        <img src="../assets/img/icons/More.png" alt="" width="30px">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <span id="desc-text"><?= $collection_description; ?></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-warning">
                        <img src="../assets/img/icons/Tagged-Product.png" width="15px" alt=""><span data-translate="tab5collectionself-7" style="margin-left:5px">Previousy purchase</span>
                    </div>
                </div>
            </div>
            <div class="container row mt-3">
                <div class="col" id="product-container" style="background-color: transparent">
                    <ul>
                    <li>
                        <a href="tab5-recent-wishlist.php?collection_code=<?= $collection_code ?>">
                            <div class="add-collection" style="position: relative">
                                <img src="../assets/img/tab5/Add-(Grey).png" class="add-collection-grey">
                                <span class="add-collection-text" data-translate="tab5collectionself-5">Add</span>
                            </div>
                        </a>
                    </li>

                        <?php

                        foreach ($collection_products as $c) {

                            $product_image = explode('|', $c['PRODUCT_THUMBNAIL']); ?>

                        <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

                        <?php $i = 0;

                        if (substr($product_image[$i], -3) == "mp4"):

                            while (substr($product_image[$i], -3) == "mp4"):
                            $product_image_video = $product_image[$i+1];
                            $i++;
                            endwhile;
            
                            // IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL
            
                            if ($product_image_video):
                            $product_image_video = $product_image_video;
                            $ext = "img style='object-fit:cover'";
                            
                            else:
                            $product_image_video = $product_image[0];
                            $ext = "video style='
                                        background: #f0f0f0;
                                        border-radius: 50%;
                                        width: 100%;
                                        height: 100%;
                                        object-fit:cover'";
                            endif;

                        else:

                            $product_image_video = $product_image[$i];
                            $ext = "img style='object-fit:cover'";

                        endif;

                        // IF MORE THAN 3 WORD SPLIT IT WITH ...

                        if (str_word_count($c['PRODUCT_NAME']) == 1){
                            if (strlen($c['PRODUCT_NAME']) > 9){
                                $product_name = substr_replace($c['PRODUCT_NAME'], "...", 9);                       
                            }else{
                                $product_name = $c['PRODUCT_NAME'];
                            }
                        }else{
                            if (strlen($c['PRODUCT_NAME']) > 14){
                                $product_name = substr_replace($c['PRODUCT_NAME'], "...", 14);                       
                            }else{
                                $product_name = $c['PRODUCT_NAME'];
                            }
                        }

                            $items = '<li onclick="showAddModal(\'' . $c['PRODUCT_CODE'] . '\');" id="all-store" class="has-story">' .
                                '<div class="story">' .
                                '<'.$ext.' style="object-fit: cover; object-position: center" src="../images/' . $product_image_video . '">' .
                                '</div>' .
                                '<span>' . $product_name . '</span>' .
                                '<p style="white-space: nowrap;">Rp ' . number_format($c['PRODUCT_PRICE'], 0, ",", ",") . '</p>' .
                                '</li>';

                            echo $items;
                        };

                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-changes" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-changes-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog animate-bottom" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
                </div>
            </div>
        </div>
    </div>

    <!-- ADD TO CART SUCCESS MODAL -->

    <div class="modal fade" id="addtocart-success" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content m-2">
                <div class="modal-body" style="max-height: 100px;">
                    <h6>Product added to cart!</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT COLLECTION MODAL -->

    <div class="modal fade" id="editCollectionModal" tabindex="-1" aria-labelledby="editCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="height: 100%">
                <div class="row d-flex justify-content-center">
                    <hr class="shop-modal-line">
                </div>
                <div class="modal-body">
                    <div class="live-stream-title" style="border-bottom: none !important">
                        <div class="form-group">
                            <div class="palceholder live-stream-title-input">
                                <label for="name" class="title_placeholder">Collection Title</label>
                                <span class="star">*</span>
                            </div>
                            <input type="text" class="form-control live-stream-title-input" id="title" name="edit_title" value="<?= $collection_name ?>" required>
                        </div>
                    </div>
                    <div class="live-stream-desc">
                        <div class="form-group">
                            <div class="palceholder live-stream-desc-input">
                                <label for="desc">Short Description (Optional)</label>
                            </div>
                            <textarea class="upload-listing-input form-control" id="desc" rows="3" maxlength="200" name="edit_desc"><?= $collection_description ?></textarea>
                            <div class="d-flex justify-content-end">
                                <span id="counter-word" class="smallest-text text-grey">0</span><span class="smallest-text text-grey">/200</span>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="collection_id" value="<?= $collection_code ?>">
                <div class="d-flex justify-content-center">
                    <button class="btn-edit-collection" data-translate="tab5collectionself-6" onclick="changeData()" data-bs-dismiss="modal">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../assets/js/update_counter.js"></script>
<script src="../assets/js/tab5-collection.js?random=<?= time(); ?>"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

    //  SCRIPT CHANGE LANGUAGE

    $(document).ready(function(){
        function changeLanguage(){

            var lang = localStorage.lang;
            change_lang(lang);

        }

        changeLanguage();

        if (localStorage.lang == 1){
            $('.add-collection-text').css('margin-left', '20px');
            $('#query').attr('placeholder', 'Pencarian');
        } else if (localStorage.lang == 0) {
            $('#query').attr('placeholder', 'Search');
        }

        $('body').show();
    });

    document.addEventListener("DOMContentLoaded", function(event){
        let headerHeight = document.getElementById('header').offsetHeight;
        document.querySelector('#modal-addtocart .modal-content').style.height = `${screen.height - headerHeight}px`;
    });
    
    // SCRIPT FOR RED DOT FORM

    $('.palceholder').click(function(){
        $(this).siblings('textarea').focus();
    });

    $('.form-control').focus(function(){
        $(this).siblings('.palceholder').hide();
    });

    $('.form-control').blur(function(){
        var $this = $(this);
        if ($this.val().length == 0){
            $(this).siblings('.palceholder').show();
        }
    });

    $('.form-control').blur();

    // SEND EDIT COLLECTION FORM VIA XHTTP

    function changeData(){

        var collection_id = $('#collection_id').val();
        var title = $('#title').val();
        var desc = $('#desc').val();

        $('#title-text').text(title);
        $('#desc-text').text(desc);

        var formData = new FormData();

        formData.append('collection_id', collection_id);
        formData.append('name', title);
        formData.append('description', desc);
        formData.append('collection_id', collection_id);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function(){
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == "Berhasil"){
                    console.log("Berhasil");
                } else {
                    console.log("Gagal nih");
                }
            }
        }
        xmlHttp.open("post", "../logics/tab5/edit_collection");
        xmlHttp.send(formData);
        }

    // WORD COUNTER SCRIPT

    var count = $('#desc').val().length;
    $('#counter-word').text(count);

    $('#desc').bind('input propertychange', function(){
        var count = $('#desc').val().length;
        $('#counter-word').text(count);
    });

    // FUNCTION VOICE SEARCH

	function voiceSearch(){
		Android.toggleVoiceSearch();
	}

	function submitVoiceSearch(searchQuery){
        $('#query').val(searchQuery);
        $('#delete-query').removeClass('d-none');
	}

    function addViews(){
        // INSERT INTO TABLE VIEWS

        var formData = new FormData();

        var collection_code = "<?php echo $collection_code; ?>";
        formData.append('collection_code', collection_code);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function (){

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                console.log(xmlHttp.responseText);
                if(xmlHttp.responseText=="Berhasil"){
                    console.log("Berhasil");
                }else{
                    console.log("Gagal nih");
                }
            }
        }

        xmlHttp.open("post", "../logics/tab5/insert_collection_views");
        xmlHttp.send(formData);
    }

    addViews();

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