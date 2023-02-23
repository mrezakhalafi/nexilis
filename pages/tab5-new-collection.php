<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

if (!isset($_GET['f_pin'])) {
    die();
}

$dbconn = paliolite();

$f_pin = $_GET["f_pin"];

// FETCH PURCHASES
$query = $dbconn->prepare("SELECT p.*, s.NAME AS MERCHANT_NAME, pr.CODE AS PRODUCT_CODE, pr.NAME AS PRODUCT_NAME, pr.THUMB_ID
FROM PURCHASE p
LEFT JOIN SHOP s ON p.MERCHANT_ID = s.CODE
LEFT JOIN PRODUCT pr ON p.PRODUCT_ID = pr.CODE
WHERE p.FPIN = '$f_pin' AND pr.CODE IS NOT NULL AND s.IS_QIOSK = 1");
$query->execute();
$results = $query->get_result();
$query->close();

$collections = array();
while ($result = $results->fetch_assoc()) {
    $collections[] = $result;
};

$query = $dbconn->prepare("SELECT p.*, s.NAME AS MERCHANT_NAME, po.POST_ID AS PRODUCT_CODE, po.TITLE AS PRODUCT_NAME, po.THUMB_ID
FROM PURCHASE p
LEFT JOIN SHOP s ON p.MERCHANT_ID = s.CODE
LEFT JOIN POST po ON p.POST_ID = po.POST_ID
WHERE p.FPIN = '$f_pin' AND s.IS_QIOSK = 1");
$query->execute();
$results_a = $query->get_result();
$query->close();

while ($result = $results_a->fetch_assoc()) {
    $collections[] = $result;
};

// fetch wishlist
$query = $dbconn->prepare("SELECT w.*, pr.PRICE, s.NAME AS MERCHANT_NAME, pr.CODE AS PRODUCT_CODE, pr.NAME AS PRODUCT_NAME, pr.THUMB_ID
FROM WISHLIST_PRODUCT w
LEFT JOIN PRODUCT pr ON w.PRODUCT_CODE = pr.CODE
LEFT JOIN SHOP s ON pr.SHOP_CODE = s.CODE
WHERE w.FPIN = '$f_pin' AND w.IS_POST = 0 AND pr.CODE IS NOT NULL AND s.IS_QIOSK = 1");
$query->execute();
$results = $query->get_result();
$query->close();

$collections_wishlist = array();
while ($result = $results->fetch_assoc()) {
    $collections_wishlist[] = $result;
};

$query = $dbconn->prepare("SELECT w.*, po.PRICING_MONEY AS PRICE, s.NAME AS MERCHANT_NAME, po.POST_ID AS PRODUCT_CODE, po.TITLE AS PRODUCT_NAME, po.THUMB_ID
FROM WISHLIST_PRODUCT w
LEFT JOIN POST po ON w.PRODUCT_CODE = po.POST_ID
LEFT JOIN SHOP s ON po.MERCHANT = s.CODE
WHERE w.FPIN = '$f_pin' AND w.IS_POST = 1");
$query->execute();
$results_b = $query->get_result();
$query->close();

while ($result = $results_b->fetch_assoc()) {
    $collections_wishlist[] = $result;
};

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab5-new-collection-style.css?random=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <!-- <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script> -->
    <script type="text/javascript" src="../assets/js/profile-shop.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <?php
    $rand_bg = rand(1, 18) . ".png";
    ?>

    <style>
        body {
            background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>');
            background-size: 100% auto;
            background-repeat: repeat-y;
            margin-bottom: 70px;
        }

        #header {
            background: <?= $setting['COLOR_PALETTE']; ?>
        }

        form#searchFilterForm-a {
            border: 1px solid #c9c9c9;
            background-color: rgba(255, 255, 255, .55);
        }

        input#query {
            background-color: rgba(255, 255, 255, 0);
        }

        #btn-create-collection {
            bottom: 70px;
        }
    </style>


</head>

<body>
    <div id="header" class="container-fluid sticky-top">
        <div class="col-12">
            <div class="row align-items-center" style="padding: 10px 0 25px 0;">
                <div class="col-1">
                    <img src="../assets/img/icons/Back-(White).png" style="width:30px" onclick="window.history.back();">
                </div>
                <div id="searchFilter-a" class="col-10 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
                    <form id="searchFilterForm-a" action="search-result" method=GET style="width: 90%;">
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
                    <div class="position-relative">
                        <img class="float-end me-2" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
                        <span id="counter-here"></span>
                    </div>
                </a>
                <!-- <div class="col-1">
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

    <div class="container-fluid pt-3 main-container" style="background-color:transparent;">

        <!-- TITLE -->
        <div class="row mt-3 mb-5">
            <div class="col-12 text-center" id="new-collection-title">
                <h5>Add New Collection</h5>
            </div>
        </div>


        <!-- FORM -->
        <div class="row">
            <div class="col-12">
                <input type="text" name="collection_name" id="collection-name" class="form-control mb-3" placeholder="Collection Title" required>
                <div class="textarea-wrap">
                    <textarea class="form-control" maxlength="200" placeholder="Short description (Optional)" name="collection_desc" id="collection-desc"></textarea>
                    <span id="text-length-counter">200/200</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="collection-visibility" id="collection-private" checked>
                    <label class="form-check-label" for="collection-private" id="collection-private-label">
                        Private
                    </label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="collection-visibility" id="collection-public">
                    <label class="form-check-label" for="collection-public" id="collection-public-label">
                        Public
                    </label>
                </div>
            </div>
        </div>
    </div>

    
    <div class="container-fluid pt-3">

    <div class="row mt-3">
            <div class="col-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="list-purchases" id="list-purchases" name="source-list" checked>
                    <label class="form-check-label" for="list-purchases" id="list-purchases-label">
                        Purchases
                    </label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="source-list" id="list-wishlist" value="list-wishlist">
                    <label class="form-check-label" for="list-wishlist" id="list-wishlist-label">
                        Wishlist
                    </label>
                </div>
            </div>
        </div>

    </div>


    <!-- RECENT PURCHASES -->
    <div class="container-fluid" id="recent-purchases">

        <?php foreach ($collections as $col) { ?>
            <div class="row my-2 collection-row ps-2 slick-row">
                <div class="col-3 my-3 collections-left d-flex justify-content-center align-items-center">
                    <?php

                    if ($col['THUMB_ID'] != null) {

                        // echo 'not null';

                        $thumb_arr = explode('|', $col['THUMB_ID']);
                        // var_dump($thumb_arr);

                        $thumb_ext = pathinfo($thumb_arr[0], PATHINFO_EXTENSION);
                        // echo $thumb_ext;

                        $thumb = str_replace("http://202.158.33.26","",$thumb_arr[0]);

                        if (strpos($thumb, "/nexilis/images/") === false) {
                            $thumb = "/nexilis/images/" . $thumb;
                        }

                        $image_type_arr = array("jpg", "jpeg", "png", "webp");
                        $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');

                        if (in_array($thumb_ext, $image_type_arr)) {
                            // echo 'img';
                            echo '<img class="collections-img rounded" src="' . $thumb . '">';
                        } else if (in_array($thumb_ext, $video_type_arr)) {
                            // echo 'video';
                            $image_name = str_replace($thumb_ext, "", $thumb_arr[0]);
                            echo '<video autoplay muted class="collections-img rounded" preload="metadata" poster="' . $image_name . 'webp">';
                            echo '<source src="' . $thumb . '" type="video/' . $thumb_ext . '">';
                            echo '</video>';
                        }
                    } else {
                        echo '<img class="collections-img" src="../assets/img/placeholder.png">';
                    }

                    ?>
                </div>
                <div class="col-7 my-3 collections-right">
                    <div class="row">
                        <div class="col-12 views d-flex align-items-center">
                            <img class="me-1" src="../assets/img/icons/Verified.png" style="height:10px; width: auto">
                            <?= $col['MERCHANT_NAME'] ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="collection-title">
                                <?= $col['PRODUCT_NAME'] ?>
                            </h6>
                            <p class="collection-desc">
                                <?php echo "Rp " . number_format($col['PRICE']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="row collection-stats">
                        <div class="col-12">
                            <span>
                                <?php echo "Purchased on " . date("d/m/Y", strtotime($col['CREATED_AT'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php
                    if ($col['PRODUCT_ID'] != null) {
                        $rowId = $col['TRANSACTION_ID'] . '-' . $col['PRODUCT_ID'] . '-0';
                    } else if ($col['POST_ID'] != null) {
                        $rowId = $col['TRANSACTION_ID'] . '-' . $col['POST_ID'] . '-1';
                    }
                ?>
                <div class="col-2 toggle-status d-flex justify-content-center align-items-center" id="<?= $rowId ?>" onclick="toggleCollection('<?= $rowId ?>')">
                    <img class="me-1" src="../assets/img/icons/Add-(Purple).png" style="height:25px; width: auto">
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- wishlist div -->
    <div class="container-fluid d-none" id="wishlist" style="background-color:white;">

        <?php foreach ($collections_wishlist as $col) { ?>
            <div class="row my-2 collection-row ps-2 slick-row">
                <div class="col-3 my-3 collections-left d-flex justify-content-center align-items-center">
                    <?php

                    if ($col['THUMB_ID'] != null) {

                        // echo 'not null';

                        $thumb_arr = explode('|', $col['THUMB_ID']);
                        // var_dump($thumb_arr);

                        $thumb_ext = pathinfo($thumb_arr[0], PATHINFO_EXTENSION);
                        // echo $thumb_ext;

                        $thumb = str_replace("http://202.158.33.26","",$thumb_arr[0]);

                        if (strpos($thumb, "/nexilis/images/") === false) {
                            $thumb = "/nexilis/images/" . $thumb;
                        }

                        $image_type_arr = array("jpg", "jpeg", "png", "webp");
                        $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');

                        if (in_array($thumb_ext, $image_type_arr)) {
                            // echo 'img';
                            echo '<img class="collections-img rounded" src="' . $thumb . '">';
                        } else if (in_array($thumb_ext, $video_type_arr)) {
                            // echo 'video';
                            $image_name = str_replace($thumb_ext, "", $thumb_arr[0]);
                            echo '<video autoplay muted class="collections-img rounded" preload="metadata" poster="' . $image_name . 'webp">';
                            echo '<source src="' . $thumb . '" type="video/' . $thumb_ext . '">';
                            echo '</video>';
                        }
                    } else {
                        echo '<img class="collections-img" src="../assets/img/placeholder.png">';
                    }

                    ?>
                </div>
                <div class="col-7 my-3 collections-right">
                    <div class="row">
                        <div class="col-12 views d-flex align-items-center">
                            <img class="me-1" src="../assets/img/icons/Verified.png" style="height:10px; width: auto">
                            <?= $col['MERCHANT_NAME'] ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="collection-title">
                                <?= $col['PRODUCT_NAME'] ?>
                            </h6>
                            <p class="collection-desc">
                                <?php echo "Rp " . number_format($col['PRICE']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="row collection-stats">
                        <div class="col-12">
                            <span>
                                <?php echo "Purchased on " . date("d/m/Y", strtotime($col['CREATED_AT'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-2 toggle-status d-flex justify-content-center align-items-center" id="<?= $col['ID'] ?>-<?= $col['PRODUCT_CODE'] ?>-<?= $col['IS_POST'] ?>" onclick="toggleCollection('<?= $col['ID'] ?>-<?= $col['PRODUCT_CODE'] ?>-<?= $col['IS_POST'] ?>')">
                    <img class="me-1" src="../assets/img/icons/Add-(Purple).png" style="height:25px; width: auto">
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="container-fluid" id="btn-create-collection">
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <button class="btn-next-shop my-3 w-100 px-3 py-2" style="margin-top: 25px;" type="button" id="create-collection">Create Collection</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-collection-success" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Collection has been created!</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="collection-msg" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="../assets/js/update_counter.js"></script>
<script type="text/javascript" src="../assets/js/script-new-collection.js?random=<?= time() ?>"></script>
<script>
if (localStorage.lang == 0) {
        $('input#query').attr('placeholder', 'Search');
    } else {
        $('input#query').attr('placeholder', 'Pencarian');
        $('#new-collection-title h5').text("Buat Koleksi Baru");
        $('input#collection-name').attr('placeholder', 'Nama Koleksi');
        $('textarea#collection-desc').attr('placeholder', 'Deskripsi singkat (opsional)');
        $('#collection-private-label').text("Pribadi");
        $('#collection-public-label').text("Publik");
        $('#list-purchases-label').text("Pembelian");
        $('#list-wishlist-label').text("Wishlist");
        $('button#create-collection').text('Buat Koleksi');
        $('#new-collection-success .modal-body h6').text('Koleksi berhasil dibuat!');
    }
</script>
</html>