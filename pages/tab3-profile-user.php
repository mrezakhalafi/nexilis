<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// if (!isset($_GET['store_id'])) {
//     die();
// }

$dbconn = paliolite();
$store_id = $_GET['store_id'];
$l_pin = $_GET['store_id'];
// $sql_query = "SELECT s.*, ssa.*, be.ID as BE_ID FROM SHOP s LEFT JOIN SHOP_SHIPPING_ADDRESS ssa ON s.CODE = ssa.STORE_CODE LEFT JOIN BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID WHERE s.CODE = '$store_id'";
// SELECT 
//   s.*, 
//   ssa.*, 
//   be.ID as BE_ID 
// FROM 
//   SHOP s 
//   LEFT JOIN SHOP_SHIPPING_ADDRESS ssa ON s.CODE = ssa.STORE_CODE 
//   LEFT JOIN BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID 
// WHERE 
//   s.CODE = '$store_id'
$sql_query = "
SELECT 
CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS NAME,
'' AS LINK,
(
    SELECT 
    COUNT(fl.L_PIN)
    FROM 
    FOLLOW_LIST fl
    WHERE
    fl.L_PIN = '$store_id'
) AS TOTAL_FOLLOWER,
(
    SELECT 
    COUNT(fl.F_PIN)
    FROM 
    FOLLOW_LIST fl
    WHERE
    fl.F_PIN = '$store_id'
) AS TOTAL_FOLLOWING,
ul.QUOTE AS DESCRIPTION,
ul.IMAGE AS THUMB_ID,
NULL AS CATEGORY,
ul.F_PIN AS CODE,
ul.BE AS BE_ID,
NULL AS ADDRESS
FROM USER_LIST ul
WHERE ul.F_PIN = '$store_id'
";
$query = $dbconn->prepare($sql_query);

// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result();
$query->close();

$store = array();
while ($group = $groups->fetch_assoc()) {
    $store[] = $group;
};

// get store products
$query = $dbconn->prepare("SELECT CODE, NAME, THUMB_ID FROM PRODUCT WHERE SHOP_CODE = '$store_id' AND IS_DELETED = 0");
$query->execute();
$images  = $query->get_result();
$query->close();

$products = array();
while ($image = $images->fetch_assoc()) {
    $p_id = $image['CODE'];
    $name = $image['NAME'];
    $image = explode('|', $image['THUMB_ID'])[0];
    $products[] = [$name, $image, $p_id];
};

$store_img = explode('|', $store[0]["THUMB_ID"]);
if ($store_img[0] != '') {
    $store_thumb_id = "https://newuniverse.io/filepalio/image/" . $store_img[0];
} else {
    $store_thumb_id = '/nexilis/assets/img/ic_person_boy.png';
}
// $store_thumb_id = "https://qmera.io/filepalio/image/" . $store_img[0];
$store_name = $store[0]["NAME"];
$store_link = $store[0]["LINK"];
$store_follower = $store[0]["TOTAL_FOLLOWER"];
$store_desc = $store[0]["DESCRIPTION"];
$store_category = $store[0]["CATEGORY"];
$store_code = $store[0]["CODE"];
$store_be = $store[0]["BE_ID"];
$follow_count = $store[0]["TOTAL_FOLLOWER"];
$following_count = $store[0]["TOTAL_FOLLOWING"];
$store_address = "";
if ($store["ADDRESS"] != null) {
    $store_address = $store["ADDRESS"];
}
// if ($store["CITY"] != null) {
//     $store_address .= ', ' . $store["CITY"];
// }
// if ($store["PROVINCE"] != null) {
//     $store_address .= ', ' . $store["PROVINCE"];
// }


// $query = $dbconn->prepare("SELECT COUNT(*) AS CNT FROM SHOP_FOLLOW WHERE STORE_CODE = '$store_id'");
// $query->execute();
// $shop_follow = $query->get_result()->fetch_assoc();
// $query->close();



// get store follow status
if (isset($_GET['f_pin']) && isset($_GET['store_id'])) {
    $f_pin = $_GET['f_pin'];
    $store_code = $_GET['store_id'];
}

$query_one = $dbconn->prepare("SELECT COUNT(*) as CNT FROM SHOP_FOLLOW WHERE F_PIN = ? AND STORE_CODE = ?");
$query_one->bind_param("ss", $f_pin, $store_code);
$query_one->execute();
$is_follow = $query_one->get_result()->fetch_assoc();
$query_one->close();

$follow_sts = $is_follow['CNT'];

// setting
$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

// CHECK BLOCKED MERCHANT

$query = $dbconn->prepare("SELECT * FROM BLOCK_USER WHERE F_PIN = '".$f_pin."' AND L_PIN = '".$l_pin."'");
$query->execute();
$checkBlock = $query->get_result()->fetch_assoc();
$query->close();

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab3-profile-style.css?random=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <!-- <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script> -->
    <script type="text/javascript" src="../assets/js/profile-shop.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="../assets/js/tab5-collection.js"></script>
    <script src="../assets/js/update_counter.js"></script>

    <?php
    $rand_bg = rand(1, 10) . ".png";
    ?>

    <style>
        body {
            background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>');
            background-size: 100% auto;
            background-repeat: repeat-y;
        }

        #header {
            background: <?= $setting['COLOR_PALETTE']; ?>;
        }

        form#searchFilterForm-a {
            border: 1px solid #c9c9c9;
            background-color: rgba(255, 255, 255, .55);
        }

        input#query {
            background-color: rgba(255, 255, 255, 0);
        }
    </style>

</head>

<body>
    <div id="header" class="container-fluid sticky-top">
        <div class="col-12">
            <div class="row align-items-center" style="padding: 10px 0 10px 0;">
                <div class="col-1">
                    <img src="../assets/img/icons/Back-(White).png" style="width:30px" onclick="goBack();">
                </div>
                <div id="searchFilter-a" class="col-10 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
                    <form id="searchFilterForm-a" action="search-result" method=GET style="width: 90%;">
                        <!-- <div class="d-flex align-items-center div-search"> -->
                        <?php
                        $query = "";
                        if (isset($_REQUEST['query'])) {
                            $query = $_REQUEST['query'];
                        }
                        ?>
                        <input id="query" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
                        <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
                        <img id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png">
                        <!-- </div> -->
                    </form>
                </div>
                <a class="col-1" href="cart.php?v=<?= time(); ?>">
                    <div class="position-relative me-2">
                        <img class="float-end" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
                        <span id="counter-here"></span>
                    </div>
                </a>
                <div class="col-1 d-none">
                    <a href="notifications.php">
                        <div class="position-relative me-2">
                            <img class="float-end" src="../assets/img/icons/Shop Manager/App-Notification-(white).png" style="width:30px">
                            <span id='counter-notifs'></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-0" id="main-container">
        <div class="container small-text pt-4" id="border-cok">
            <div class="row mb-3">
                <div class="col-3 d-flex align-items-center justify-content-center">
                    <img class="logo-merchant" src="<?php echo $store_thumb_id; ?>">
                </div>
                <div class="col-5 d-flex align-items-center p-0">
                    <div class="col-12">
                        <div class="row">
                            <!-- <div class="col-1 p-0"></div> -->
                            <div class="col-auto p-0 pe-2 align-self-center" style="border-right: 1px solid black; max-width: 75% !important;">
                                <b><?php echo $store_name; ?></b>
                            </div>
                            <div class="col-3 p-0 ps-1 align-self-center">
                                <a href="tab3-profile-rating?store_id=<?= $store_id ?>&f_pin=<?= $f_pin ?>">
                                    <img class="me-1" src="../assets/img/icons/wishlist-yellow.png" height="17px" style="vertical-align:bottom;"><b>5.0</b>
                                </a>
                            </div>
                        </div>
                        <div class="row"><?php echo $store_address; ?></div>
                        <div class="row">
                            <div class="col p-0"><span id="follower-count" style="color: #000000;"><?php echo $follow_count; ?></span> <span id="amt-followers" style="font-weight:bold;">Followers</span></div>
                            <div class="col p-0"><span style="color: #000000;"><?php echo $following_count; ?></span> <span id="amt-following" style="font-weight:bold;">Following</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-end">
                    <div class="px-3">
                        <div id="btn-follow" class="row px-3 py-1 mb-2 justify-content-center" style="width: 80px; background-color: #000000; border-radius: 5px; color: white;">
                            <script>
                                if (localStorage.lang == 0) {
                                    <?php if ($follow_sts == 0) { ?>
                                        document.getElementById("btn-follow").innerText = "Follow";
                                    <?php } else { ?>
                                        document.getElementById("btn-follow").innerText = "Unfollow";
                                    <?php } ?>
                                } else {
                                    <?php if ($follow_sts == 0) { ?>
                                        document.getElementById("btn-follow").innerText = "Ikuti";
                                    <?php } else { ?>
                                        document.getElementById("btn-follow").innerText = "Berhenti Mengikuti";
                                    <?php } ?>
                                }
                            </script>
                        </div>
                        <!-- <div class="row px-3 py-0 promo-button" style="width: 80px;"><span class="py-1 px-0 promo-text">Promo</span></div> -->
                        <!-- BUTTON REPORT -->

                        <?php if (!$checkBlock): ?>

                            <div id="dropdownReport" class="row bg-danger px-3 py-1 mb-2 justify-content-center" style="width: 80px; border-radius: 5px; color: white;" data-bs-toggle="dropdown">Report</div>
                            <ul class="dropdown-menu shadow-lg" style="min-width: auto !important; position: absolute; border: 1px solid black; z-index: 1000" aria-labelledby="dropdownMenuLanguage">
                                <li id="report_user" onclick="reportUser('<?= $store_id ?>')" ><a class="dropdown-item" data-translate="tab5listing-10" data-bs-toggle="modal" data-bs-target="#modal-category">Report User</a></li>
                                <li id="block_user" onclick="blockUser('<?= $store_id ?>')"><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5listing-11" data-bs-toggle="modal" data-bs-target="#modal-block-success">Block User</button></li>
                            </ul>

                        <?php else: ?>

                            <div id="dropdownReport" class="row bg-danger px-3 py-1 mb-2 justify-content-center" style="width: 80px; border-radius: 5px; color: white;">Unblock</div>

                        <?php endif; ?>

                        <!-- MODAL REPORT USER-->
                        <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category2" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

                                    <div class="row gx-0">
                                        <div class="col-12">
                                            <div class="col-12 mb-3 text-center">
                                                <h5>Why you want to report this user?</h5>
                                            </div>
                                            <div class="col-12" style="float: left; font-size: 16px">
                                                <ul>
                                                    <form action="/action_page.php">

                                                        <?php 

                                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                                        $query->execute();
                                                        $category = $query->get_result();
                                                        $query->close();

                                                        foreach($category as $c): ?>

                                                        <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?> >
                                                        <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                            <?= $c['CATEGORY'] ?>
                                                        </label>
                                                        </div>
                                                        
                                                        
                                                        <?php endforeach;

                                                        ?>

                                                        <!-- <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="report_category" id="report_category1" value="0">
                                                            <label class="form-check-label" for="report_category1">
                                                            It's a scam
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="report_category" id="report_category2" value="1">
                                                            <label class="form-check-label" for="report_category2">
                                                            Nudity or sexual activity
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="2">
                                                            <label class="form-check-label" for="report_category3">
                                                            Hate speech or symbols
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="report_category" id="report_category3" value="3">
                                                            <label class="form-check-label" for="report_category3">
                                                            Bullying or harassment
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="report_category" id="report_category4" value="4">
                                                            <label class="form-check-label" for="report_category4">
                                                            Violence or dangerous organization
                                                            </label>
                                                        </div> -->

                                                        <div class="row mt-3">
                                                            <div class="col-12 d-flex justify-content-center">
                                                                <button class="btn btn-dark" type="button" onclick="reportUserSubmit()" data-bs-target="#modal-report-success">Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END OF MODAL REPORT USER -->
                        
                        <!-- REPORT MODAL SUBMIT -->
                        <div class="modal fade" id="modal-report-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                    <p style="font-size: 16px">Report submited.</p>
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button class="btn btn-dark" type="button" onclick="reloadPages()">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END OF REPORT MODAL SUBMIT -->
                        <!-- BLOCK MODAL SUBMIT -->
                        <div class="modal fade" id="modal-block-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                    <p style="font-size: 16px">You blocked this person.</p>
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button class="btn btn-dark" type="button" onclick="reloadPages()">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END OF BLOCK MODAL SUBMIT -->


                    </div>
                </div>
            </div>
            <div class="container px-4 mb-3">
                <div class="col-12">
                    <div class="row"><?php echo $store_name; ?></div>
                    <div class="row"><?php echo $store_desc; ?></div>
                    <div class="row"><a class="px-0" onclick="insertViewsWebsite()" href="<?php echo $store_link; ?>" style="color: #6292c6;"><?php echo $store_link; ?></a></div>
                </div>
            </div>
            <div class="row mb-1">
                <ul class="nav nav-pills nav-fill px-0">
                    <li class="nav-item">
                        <a id="posts-tab" class="nav-link active" href="#" onclick="changeProfileTab('posts');">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a id="shop-tab" class="nav-link" href="#" onclick="changeProfileTab('shop');">Shop</a>
                    </li>
                </ul>
            </div>
            <div id="posts" class="col-12 p-0 tab-content <?php echo $checkBlock ? "d-none" : "" ?>">
                <div class="row" style="margin-bottom: 1px;">
                    <div id="loading">
                        <div class="col-sm mt-2">
                            <h5 class="prod-name" style="text-align:center;">Loading...</h5>
                        </div>
                    </div>
                    <div id="content-grid" class="grid-stack grid-stack-3 p-0">
                    </div>
                </div>
            </div>
            <div id="shop" class="col-12 p-0">
                <div class="row" style="margin-bottom: 1px;">
                        <div class="col-sm mt-2">
                            <h5 class="no-prod" style="text-align:center;"></h5>
                        </div>
                </div>
                <?php

                //for each porduct
                // echo '<div class="row" style="margin-bottom: 1px;">';
                // $i = 0;

                // $image_type_arr = array("jpg", "jpeg", "png", "webp");
                // $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');

                // foreach ($products as $p) {

                //     echo '<div class="col-6 d-flex align-items-center p-0 position-relative">';
                //     // echo '<a href="tab1-addtocart.php">';
                //     echo '<a id="prod-' . $p[2] . '">';

                //     // check file type 
                //     $thumb_ext = pathinfo($p[1], PATHINFO_EXTENSION);
                //     $image_name = str_replace($thumb_ext, "", $p[1]);
                //     if (substr($p[1],0,4) != 'http') {
                //         $img_src = '/nexilis/images/' . $p[1];
                //     } else {
                //         $img_src = $p[1];
                //     }
                //     if (in_array($thumb_ext, $image_type_arr)) {
                //         echo '<img src="' . $img_src . '" class="rounded" width="99%" alt="' . $p[0] . '">';
                //     } else if (in_array($thumb_ext, $video_type_arr)) {
                //         echo '<video muted loop autoplay poster="' . $image_name . 'webp" width="99%"><source src="' . $img_src . '"></video>';
                //     }

                //     // echo '<img src="' . $p[1] . '" width="99%" alt="' . $p[0] . '">';
                //     echo '</a>';
                //     echo '<img class="position-absolute bottom-0 end-0 m-2" onclick="addToCart(\'' . $p[2] . '\');" src="../assets/img/icons/Add-to-Cart.png" width="20%">';
                //     // echo '</a>';
                //     echo '</div>';

                //     $i++;
                //     if ($i % 2 == 0 && $i != count($products)) {
                //         echo '</div><div class="row" style="margin-bottom: 1px;">';
                //     }
                // }
                // echo "</div>";

                ?>
            </div>
        </div>
    </div>

    <!-- show product modal -->
    <div class="modal fade" id="modal-product" tabindex="-1" aria-labelledby="modal-product" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body p-0"></div>
                <div class="modal-footer justify-content-start"></div>
            </div>
        </div>
    </div>
    <!-- show product modal -->

    <!-- follow store modal -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog justify-content-center modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="my-auto">
                        <h6><span class="text_welcome">Welcome to </span><?php echo $store_name; ?> <span class="official_text">Official Store!</span></h6>
                        <p class="welcome_desc">Follow our store to get the latest news, updates, and amazing offers.</p>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button id="modal-follow-btn" type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Follow
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->

    <!-- addtocart modal -->
    <div class="modal fade" id="modal-addtocart" tabindex="-1" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-add-body">
                </div>
            </div>
        </div>
    </div>
    <!-- addtocart modal -->

    <!-- add to cart success modal -->
    <div class="modal fade" id="addtocart-success" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Product added to cart!</h6>
                </div>
                <div class="modal-footer">
                    <button id="addtocart-success-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- add to cart success modal -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
<script>
    const store_id = "<?php echo $_GET['store_id'] ?>";
    const store_code = "<?php echo $store_code ?>";
    const be_id = "<?php echo $store_be ?>";
    let isFollowed = <?php echo $follow_sts ?>;
    console.log('isfollowed : ' + isFollowed);

    window.onload = function() {
        if (window.Android) {
            window.Android.setButtonTheme('<?php echo $store_category; ?>');
        }
    }

    if (localStorage.lang == 0) {
        $('input#query').attr('placeholder', 'Search');
        $('.prod-name').text("Loading...");
        $('#modal-follow-btn').text('Follow');
        $('.welcome_desc').text('Follow our store to get the latest news, updates, and amazing offers.');
        $('.text_welcome').text('Welcome to ');
        $('.official_text').text('Official Store!');
        $('#no-prod').text('No products to display.');
    } else {
        $('input#query').attr('placeholder', 'Pencarian');
        $('.prod-name').text("Sedang memuat...");
        $('#modal-follow-btn').text('Ikuti');
        $('.welcome_desc').text('Ikuti toko kami untuk mendapatkan berita terbaru, pembaruan, dan penawaran yang menarik.');
        $('.text_welcome').text('Selamat datang di Toko Official ');
        $('.official_text').text('!');
        $('#no-prod').text('Tidak ada produk.');
    }
</script>
<script type="text/javascript" src="../assets/js/update-score-shop.js?random=<?= time(); ?>"></script>
<script src="../assets/js/profile-shop.js?random=<?= time(); ?>"></script>
<script type="text/javascript" src="../assets/js/script-profile-user.js?random=<?= time(); ?>"></script>

<script src="../assets/js/update_counter.js"></script>

<script>
    if (localStorage.lang == 0) {
        document.getElementById("amt-followers").innerText = "Followers";
        document.getElementById("amt-following").innerText = "Following";
        <?php if ($follow_sts == 0) { ?>
            document.getElementById("btn-follow").innerText = "Follow";
        <?php } else { ?>
            document.getElementById("btn-follow").innerText = "Unfollow";
        <?php } ?>
    } else {
        document.getElementById("amt-followers").innerText = "Pengikut";
        document.getElementById("amt-following").innerText = "Diikuti";
        <?php if ($follow_sts == 0) { ?>
            document.getElementById("btn-follow").innerText = "Ikuti";
        <?php } else { ?>
            document.getElementById("btn-follow").innerText = "Berhenti Mengikuti";
        <?php } ?>
    }

    function insertViewsStore() {
        var formData = new FormData();
        formData.append('f_pin', '<?= $f_pin ?>');
        formData.append('store_id', '<?= $store_id ?>');

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
            }
        }

        xmlHttp.open("post", "../logics/tab5/insert_store_views");
        xmlHttp.send(formData);
    }

    insertViewsStore();

    function insertViewsWebsite() {
        var formData = new FormData();
        formData.append('f_pin', '<?= $f_pin ?>');
        formData.append('store_id', '<?= $store_id ?>');

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
            }
        }

        xmlHttp.open("post", "../logics/tab5/insert_website_views");
        xmlHttp.send(formData);
    }
</script>

</html>