<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$dbNewNus = newnus();

session_start();

$f_pin = $_REQUEST['f_pin'];

// NEW F_PIN SET METHOD

$queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
$query = $dbconn->prepare($queryCheckFpin);
$query->execute();
$getNewFPIN = $query->get_result()->fetch_assoc();
$query->close();

$f_pin = $getNewFPIN['F_PIN'];
$_SESSION['f_pin'] = $f_pin;

// print_r($f_pin);

if (isset($_GET['p'])) {
    $be_id = $_GET['p'];
}

if (isset($_GET['p'])) {
    $api_key = $_GET['p'];
}

$bg_url = "";

if (isset($f_pin) && $f_pin != "") {
    $sqlBE = "SELECT BE AS BE_ID FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($api_key) && $api_key != "") {
    $sqlBE = "SELECT ID AS BE_ID FROM BUSINESS_ENTITY WHERE API = '$api_key'";
}

$queBEID = $dbconn->prepare($sqlBE);
$queBEID->execute();
$resBEID = $queBEID->get_result()->fetch_assoc();
$queBEID->close();
$be = $resBEID["BE_ID"];
// echo $be;

$sqlAPI = 'SELECT API FROM BUSINESS_ENTITY WHERE ID = ' . $be;
// echo $sqlAPI;
$queBEID = $dbconn->prepare($sqlAPI);
$queBEID->execute();
$resBEID = $queBEID->get_result()->fetch_assoc();
$queBEID->close();
$api = $resBEID["API"];

$sqlOne = "
SELECT ci.COMPANY_LOGO 
FROM COMPANY_INFO ci
LEFT JOIN COMPANY c ON c.ID = ci.COMPANY
WHERE c.API_KEY = '$api'";

// echo $sqlOne;

$query = $dbNewNus->prepare($sqlOne);
$query->execute();
$geoloc = $query->get_result()->fetch_assoc();
$c_id = $geoloc['COMPANY_LOGO'];
$query->close();

if ($c_id == null || $c_id == "") {
    $allPostsLogo = "../assets/img/pb_powered_nexilis.png";
} else {
    $allPostsLogo = '/dashboardv2/uploads/logo/' . $c_id;
    // $allPostsLogo = '/filepalio/image/' . $getImage["IMAGE"];
}

$sqlBG = "SELECT p.VALUE FROM `PREFS_MAB` p 
            LEFT JOIN USER_LIST ul ON ul.BE = p.BE AND IFNULL(p.ACCESS_CATEGORY, 0) = (SELECT IFNULL(ACCESS_CATEGORY, 0) FROM USER_LIST WHERE F_PIN = '$f_pin')
            WHERE p.KEY = 'app_builder_background'
            AND ul.BE = $be
            AND ul.F_PIN = '$f_pin'";
$queBG = $dbconn->prepare($sqlBG);
$queBG->execute();
$resBG = $queBG->get_result()->fetch_assoc();
$queBG->close();
// echo $sqlBG;

$bg_str = $resBG["VALUE"];

// echo $bg_str;
if ($bg_str != null && $bg_str != "") {
    $bg_arr = explode(",", $bg_str);

    // echo "<pre>";
    // print_r($bg_arr);
    // echo "</pre>";

    $rand_bg = $bg_arr[array_rand($bg_arr, 1)];

    $bg_url = "/dashboardv2/uploads/background/" . $rand_bg;
} else if ($bg_str == "" || $bg_str == null) {
    $bg_url = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <title>Tab 1 Video Theme</title>
</head>

<style>
    #scroll-to-top {
        position: fixed;
        width: 60px;
        height: 60px;
        padding: 15px;
        bottom: 75px;
        left: 20px;
        cursor: pointer;
        z-index: 99;
        background-color: #c0c0c0;
        opacity: 0.5;
    }

    body {
        font-family: 'Roboto', sans-serif !important;
    }

    a {
        text-decoration: none !important;
    }

    select::-ms-expand {
        display: none;
    }

    .dropdown-toggle::after {
        content: none !important;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="../assets/css/tab1-video-min.css?random=<?= time(); ?>" />

<body style="visibility:hidden">

    <div id="company-logo-wrap">
        <img id="company-logo" src="<?= $allPostsLogo ?>">
    </div>

    <div id="filter-wrap">
        <img id="toggle-filter" src="../assets/img/jim_settings.png">
    </div>

    <div class="container-fluid">
        <div id="header" class="d-none">

            <div id="searchFilter-a" class="col-10 text-white">
                <form id="searchFilterForm-a" method=GET>
                    <!-- <div class="d-flex align-items-center div-search"> -->
                    <?php
                    $query = "";
                    if (isset($_REQUEST['query'])) {
                        $query = $_REQUEST['query'];
                    }
                    ?>
                    <input id="query" type="text" class="search-query" name="query" value="<?= $query; ?>">
                    <script>

                    </script>
                    <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
                    <img id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png" onclick="voiceSearch();">
                    <!-- </div> -->

                </form>

            </div>

            <div id="category-checkbox" class="row" style="color:black;">
                <div id="categoryFilter-body" class="col-12 px-4 py-2">
                    <ul id="root-category"></ul>
                    <ul id="other-category" class="my-2">
                        <li style="display: inline-block">
                            <!-- <input type="checkbox" id="filter-verified">
                            <label for="filter-verified">Verified</label> -->
                            <div class="form-check" style="padding-left: 0px">
                                <label class="form-check-label" for="filter-verified">
                                    <img src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3.6px">Verified
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="filter-verified" style="margin: 5px; margin-top: 2.5px; accent-color: #58baed; border: 2px solid #58baed; float: right">
                            </div>
                        </li>
                        <li style="display: inline-block">
                            <!-- <input type="checkbox" id="filter-friends">
                            <label for="filter-friends">Friends</label> -->
                            <div class="form-check" style="padding-left: 0px">
                                <label class="form-check-label" for="filter-friends">
                                    <img src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3.6px">Friends
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="filter-friends" style="margin: 5px; margin-top: 2.5px; accent-color: #58baed; border: 2px solid #58baed; float: right">
                            </div>
                        </li>
                        <li style="display: inline-block">
                            <!-- <input type="checkbox" id="filter-friends">
                            <label for="filter-friends">Friends</label> -->
                            <div class="form-check" style="padding-left: 0px">
                                <label class="form-check-label" for="filter-others">
                                    <img src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3.6px">Others
                                </label>
                                <input class="form-check-input" type="checkbox" value="" id="filter-others" style="margin: 5px; margin-top: 2.5px; accent-color: #58baed; border: 2px solid #58baed; float: right">
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div id="section-player">
        <!-- <video id="main-video" controls loop muted src=""></video> -->
        <img id="main-video" />
        <span id="text-recorded-main" class="text-white p-1" style="background-color: #d85446; border-radius: 5px; font-size: 8px; margin-top: 272px; margin-left: -58px; position: absolute">RECORDED</span>
    </div>

    <div id="section-list-video" style="margin-bottom: 80px">
    </div>
    <div class="row p-3 d-none">
        <div class="col-12 text-center">
            <button id="btnLoadMore" style="width: 40%; border: 1px solid black; background-color: white; color: black" class="btn btn-dark mb-5 d-none">Load More</button>
        </div>
    </div>

    <!-- <div class="modal" id="video-pop" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body"> -->
    <div class="video-wrap d-none" id="video-wrap">
        <video src="" id="video-playing"></video>
        <div class="video-close" onclick="event.stopPropagation(); closeVideo();">
            <img src="../assets/img/close-icon.png">
        </div>
        <div class="video-fullscreen" onclick="event.stopPropagation(); toggleFullscreen();">
            <img src="../assets/img/video-fullscreen.png">
        </div>
        <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute();">
            <img src="../assets/img/video_unmute.png">
        </div>
        <div class="video-play d-none"><img src="../assets/img/play-icon.png"></div>
    </div>
    <div class="row p-3">
        <div class="col-12">
            <img id="scroll-to-top" class="rounded-circle d-none" src="../assets/img/ic_collaps_arrow.png" onclick="totopFunction(true)">
        </div>
    </div>
    <!-- </div>
            </div>
        </div>
    </div> -->

    <!-- MODAL REPORT CONTENT -->
    <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="modal-category" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body p-4" id="modal-add-body" style="position: relative;">
                    <div class="row gx-0">
                        <div class="col-12">
                            <div class="col-12 mb-3 text-center">
                                <h5 id="why-report-content">Why you want to report this content?</h5>
                            </div>
                            <div class="col-12" style="float: left; font-size: 16px">
                                <ul>
                                    <form action="/action_page.php">

                                        <?php

                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                        $query->execute();
                                        $categoryEN = $query->get_result();
                                        $query->close();

                                        foreach ($categoryEN as $c) : ?>

                                            <div class="form-check report-user-category-en">
                                                <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY'] ?>
                                                </label>
                                            </div>

                                        <?php endforeach;

                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                        $query->execute();
                                        $categoryID = $query->get_result();
                                        $query->close();

                                        foreach ($categoryID as $c) : ?>

                                            <div class="form-check report-user-category-id">
                                                <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY_ID'] ?>
                                                </label>
                                            </div>


                                        <?php endforeach;

                                        ?>

                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button id="btn-submit-report-content" class="btn btn-dark" type="button" onclick="reportContentSubmit()">Submit</button>
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

    <!-- MODAL REPORT USER-->
    <div class="modal fade" id="modal-category2" tabindex="-1" role="dialog" aria-labelledby="modal-category2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

                    <div class="row gx-0">
                        <div class="col-12">
                            <div class="col-12 mb-3 text-center">
                                <h5 id="why-report-user">Why you want to report this user?</h5>
                            </div>
                            <div class="col-12" style="float: left; font-size: 16px">
                                <ul>
                                    <form action="/action_page.php">

                                        <?php

                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                        $query->execute();
                                        $categoryEN = $query->get_result();
                                        $query->close();

                                        foreach ($categoryEN as $c) : ?>

                                            <div class="form-check report-content-category-en">
                                                <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY'] ?>
                                                </label>
                                            </div>


                                        <?php endforeach;

                                        $query = $dbconn->prepare("SELECT * FROM REPORT_CATEGORY");
                                        $query->execute();
                                        $categoryID = $query->get_result();
                                        $query->close();

                                        foreach ($categoryID as $c) : ?>

                                            <div class="form-check report-content-category-id">
                                                <input class="form-check-input" type="radio" name="report_category" id="report_category<?= $c['ID'] ?>" value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="report_category<?= $c['ID'] ?>">
                                                    <?= $c['CATEGORY_ID'] ?>
                                                </label>
                                            </div>

                                        <?php endforeach;

                                        ?>

                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-center">
                                                <button id="btn-submit-report-user" class="btn btn-dark" type="button" onclick="reportUserSubmit()" data-bs-target="#modal-report-success">Submit</button>
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

    <!-- REPORT MODAL SUBMIT -->
    <div class="modal fade" id="modal-report-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <p id="report-submited" style="font-size: 16px">Report submited.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button id="report-close" class="btn btn-dark" type="button" onclick="reloadPages()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOCK MODAL USER SUBMIT -->
    <div class="modal fade" id="modal-block-success" tabindex="-1" aria-labelledby="modal-report-success" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <p id="blocked-notification" style="font-size: 16px">You blocked this person.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button id="close-blocked" class="btn btn-dark" type="button" onclick="reloadPagesBlock()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOCK MODAL CONTENT SUBMIT -->
    <div class="modal fade" id="modal-block-content-success" tabindex="-1" aria-labelledby="modal-block-content-success" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4 text-center">
                    <p style="font-size: 16px">You blocked this content.</p>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button class="btn btn-dark" type="button" onclick="reloadPagesBlock()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="../assets/js/jquery.validate.js"></script>
<script src="../assets/js/jQueryRotate.js"></script>
<script src="../assets/js/tab1-video-min.js?v=<?= time() ?>"></script>

<script>
    if (localStorage.lang == 1) {

        $('#why-report-content').text('Apakah anda yakin untuk melaporkan konten ini?');
        $('#why-report-user').text('Apakah anda yakin untuk melaporkan pengguna ini?');

        $('.report-content-category-en').hide();
        $('.report-user-category-en').hide();

        $('#btn-submit-report-user').text('Laporkan');
        $('#btn-submit-report-content').text('Laporkan');

        $('#report-submited').text('Laporan anda berhasil.');
        $('#report-close').text('Tutup');

        $('#modal-confirm-remove .modal-body h6').text('Hilangkan post ini?');
        $('#remove-post-accept').text('Ya');
        $('#remove-post-close').text('Batal');

    } else {

        $('.report-content-category-id').hide();
        $('.report-user-category-id').hide();

    }
</script>