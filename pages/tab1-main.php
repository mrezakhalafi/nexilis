<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$dbconn = paliolite();
if (isset($_GET['f_pin'])) {

  $f_pin = $_GET['f_pin'];

  $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
  $que = $dbconn->prepare($queryCheckFpin);
  $que->execute();
  $getFpin = $que->get_result()->fetch_assoc();
  $que->close();

  $_GET["f_pin"] = $getFpin["F_PIN"];
  $_SESSION["f_pin"] = $getFpin["F_PIN"];
  $f_pin = $_SESSION["f_pin"];
}

// echo 'bo ' . $f_pin;

// $_SESSION['f_pin'] = $f_pin;



$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

$dbNewNus = newnus();



if (isset($_GET['p'])) {
  $api_key = $_GET['p'];
}

$bg_url = "";

if (isset($f_pin) && $f_pin != "") {
  // $sqlBE = "SELECT be.COMPANY_ID
  // FROM BUSINESS_ENTITY be
  // LEFT JOIN USER_LIST ul ON ul.BE = be.ID
  // WHERE ul.F_PIN = '$f_pin'";
  $sqlBE = "SELECT BE AS BE_ID, IS_CHANGED_PROFILE AS ICP FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($api_key) && $api_key != "") {
  // $sqlBE = "SELECT be.COMPANY_ID
  // FROM BUSINESS_ENTITY be
  // WHERE be.API = '$api_key'";
  $sqlBE = "SELECT ID AS BE_ID FROM BUSINESS_ENTITY WHERE API = '$api_key'";
}

$queBEID = $dbconn->prepare($sqlBE);
$queBEID->execute();
$resBEID = $queBEID->get_result()->fetch_assoc();
$queBEID->close();

$be = $resBEID["BE_ID"];

// $sqlBG = "SELECT `VALUE` FROM `PREFS_MAB` WHERE `BE` = $be AND `KEY` = 'app_builder_background'";
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

// else {
//   $rand_bg = rand(1, 18) . ".png";
//   $bg_url = "../assets/img/lbackground_" . $rand_bg;
// }

// fetch GIFs


// echo "<pre>";
// print_r($gif_arr);
// echo "</pre>";

$sqlGIF = "SELECT BE_ID, COUNT(BE_ID) AS COUNT_BE FROM XPORA_GIF WHERE BE_ID = 0 OR BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
$queGIF = $dbconn->prepare($sqlGIF);
$queGIF->execute();
$resGIF = $queGIF->get_result()->fetch_assoc();
$queGIF->close();

$countGIF = $resGIF["COUNT_BE"];
$be_id = $resGIF["BE_ID"];

// CHECK CATEGORY
$sqlCategory = "SELECT COUNT(*) AS CNT_CATEGORY FROM NEXILIS_CATEGORY WHERE BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
$queCategory = $dbconn->prepare($sqlCategory);
$queCategory->execute();
$resCategory = $queCategory->get_result()->fetch_assoc();
$queCategory->close();

$isCatExist = $resCategory['CNT_CATEGORY'] > 0;

?>

<!doctype html>
<html lang="en">

<head>
  <title>Timeline</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/clean-switch.css" />
  <link href="../assets/css/jquery-ui.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/tab3-style-min.css?v=<?php echo time(); ?>" />
  <!-- <link rel="stylesheet" href="../assets/css/roboto.css" /> -->
  <link rel="stylesheet" href="../assets/css/tab1-style-min.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />
  <script src="../assets/js/xendit.min.js"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jQueryRotate.js"></script>
  <script src="../assets/js/jquery.validate.js"></script>
  <script src="../assets/js/isInViewport.min.js"></script>
  <script src="../assets/js/jquery-ui.min.js"></script>
  <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
  <script src="https://apis.google.com/js/api.js" defer></script>
  <script>
    var BE_ID = "<?= $be ?>";
    console.log(BE_ID, typeof(BE_ID));

    var isChangedProfile = "<?= $resBEID["ICP"] ?>";
  </script>
  <?php
  // $rand_bg = rand(1, 18) . ".png";
  ?>

  <style>
    * {
      -webkit-touch-callout: none !important;
    }

    body {
      position: relative;
    }

    body.modal-open {
      overflow: hidden;
    }

    body::after {
      content: "";
      background-image: url('<?php echo $bg_url; ?>');
      background-size: 100% auto;
      background-repeat: repeat-y;
      opacity: .5;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      position: fixed;
      z-index: -1;
    }

    #header-layout {
      background: <?= $setting['COLOR_PALETTE']; ?>;
      z-index: 99;
      transition: top 0.4s ease-in-out;
    }

    #header-layout #header {}

    #story-container {
      background: transparent;
    }

    form#searchFilterForm-a {
      border: 1px solid #c9c9c9;
      background-color: rgba(255, 255, 255, .55);
      width: 100%;
    }

    #searchFilter-a {
      margin-left: 0;
    }

    input#query {
      background-color: rgba(255, 255, 255, 0);
    }

    .float {
      position: fixed;
      width: 52px;
      height: 52px;
      bottom: 75px;
      right: 10%;
      background-color: rgba(0, 0, 0, .65);
      color: #FFF;
      border-radius: 50px;
      text-align: center;
      box-shadow: 2px 2px 3px #999;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .my-float {}

    .post-status.dropdown-toggle::after {
      display: none;
    }

    .dropdown ul li {}

    #pbr-timeline {
      max-width: 100%;
      overflow-x: hidden;
    }

    .addcart-wishlist.logo {
      object-fit: cover;
    }

    div#loading-overlay {
      display: table;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 200;
    }

    div#loading-overlay>div {
      display: table-cell;
      width: 100%;
      height: 100%;
      background: white;
      opacity: 1.5;
      text-align: center;
      vertical-align: middle;
    }

    .timeline#pbr-timeline {}

    #modal-addtocart .modal-dialog {
      top: 0;
    }

    .comments.is-deleted {
      display: none;
    }

    #modal-consent-newpost .modal-content {
      border: 0;
      border-radius: 13px;
    }

    #modal-consent-newpost .modal-body p {
      color: gray;
      margin-bottom: 0;
    }

    #modal-consent-newpost .modal-footer {
      border-top: 0;
    }

    #modal-consent-newpost .modal-footer button {
      background-color: transparent !important;
      color: darkturquoise;
      font-weight: bold;
      font-size: .85rem;
    }

    .timeline-main .video-wrap video {
      max-height: 600px;
      object-fit: cover;
    }

    <?php $rand_pos = rand(0, 1); ?>#gif-container {
      position: fixed;
      z-index: 9999;
    }

    #gif-container.left {
      left: 20px;
      right: auto;
    }

    #gif-container.right {
      right: 20px;
      left: auto;
    }

    #gif-container.top {
      top: 30px;
      bottom: auto;
      left: 20px;
    }

    #gif-container.bottom {
      bottom: 140px;
      top: auto;
      right: 20px;
    }

    .gifs img {
      height: 170px;
      width: 115px;
    }

    #categoryFilter-body ul {
      list-style: none;
      padding: 0;
      margin: 0;
      font-size: 14px;
    }

    #categoryFilter-body input[type="checkbox"] {
      margin: 5px;
    }

    #categoryFilter-body ul ul {
      margin: 0 0 0 15px;
    }

    .timeline-image .carousel-inner,
    .timeline-image .carousel-item.active,
    .timeline-image .carousel-item-wrap,
    .timeline-image .video-wrap {
      overflow: visible !important;
    }
  </style>
</head>

<body>
  <!-- <img draggable="false" 
    class="demo-bg"
    alt=""
  > -->
  <div id="loading-overlay" class="d-none">
    <div>
      <img draggable="false" src="../assets/img/nxsport_icons/nx.png" style="width:150px; height:auto;" />
      <br>
      <br>
      <h6><strong>Loading... please wait.</strong></h6>
    </div>
  </div>
  <img draggable="false" id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
  <div class="container-fluid">


    <div id="header-layout" class="sticky-top">
      <div id="story-container">
        <?php require('timeline_story_container_timeline.php'); ?>
      </div>
      <div id="header" class="row justify-content-between">
        <div class="col-9">
          <div id="searchFilter-a" class="col-12 d-flex align-items-center justify-content-center text-white">
            <form id="searchFilterForm-a" method=GET>
              <!-- <div class="d-flex align-items-center div-search"> -->
              <?php
              $query = "";
              if (isset($_REQUEST['query'])) {
                $query = $_REQUEST['query'];
              }
              ?>
              <input id="query" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
              <script>
                if (localStorage.lang == 0) {
                  // $('input#query').attr('placeholder', 'Search');
                  document.getElementById('query').placeholder = "Search";
                } else {
                  document.getElementById('query').placeholder = "Pencarian";
                }
              </script>
              <img draggable="false" class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
              <img draggable="false" id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png" onclick="voiceSearch();">
              <!-- </div> -->

            </form>

          </div>
        </div>
        <div id="gear-div" class="col-3 d-flex align-items-center justify-content-center" style="padding-right: 9px; padding-left: 9px;">
          <img draggable="false" class="header-icon me-1" id="toggle-filter" src="../assets/img/filter-icon-gray.png">
          <a class="me-1" id="to-grid-layout">
            <div class="position-relative">
              <img draggable="false" class="header-icon" src="../assets/img/ic_grid.png">
              <!-- <span id='counter-here'></span> -->
            </div>
          </a>
          <a class="me-3" id="to-list-layout">
            <div class="position-relative">
              <img draggable="false" class="header-icon mx-auto" src="../assets/img/ic_list.png">
              <!-- <span id='counter-notifs'></span> -->
            </div>
          </a>
        </div>
      </div>
      <div id="category-checkbox" class="row d-none" style="color:black;">
        <div id="categoryFilter-body" class="col-12 px-4 py-2">
          <ul id="root-category"></ul>
          <ul id="other-category" class="my-2">
            <li style="display: inline-block">
              <!-- <input type="checkbox" id="filter-verified">
              <label for="filter-verified">Verified</label> -->
              <div class="form-check" style="padding-left: 0px">
                <label class="form-check-label" for="filter-official">
                  <img draggable="false" src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3px">Official
                </label>
                <input class="form-check-input" type="checkbox" checked value="" id="filter-official" style="margin: 5px; margin-top: 3px; accent-color: #58baed; border: 2px solid #58baed; float: right">
              </div>
            </li>
            <li style="display: inline-block">
              <!-- <input type="checkbox" id="filter-verified">
              <label for="filter-verified">Verified</label> -->
              <div class="form-check" style="padding-left: 0px">
                <label class="form-check-label" for="filter-verified">
                  <img draggable="false" src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3px">Verified
                </label>
                <input class="form-check-input" type="checkbox" checked value="" id="filter-verified" style="margin: 5px; margin-top: 3px; accent-color: #58baed; border: 2px solid #58baed; float: right">
              </div>
            </li>
            <li style="display: inline-block">
              <!-- <input type="checkbox" id="filter-friends">
              <label for="filter-friends">Friends</label> -->
              <div class="form-check" style="padding-left: 0px">
                <label class="form-check-label" for="filter-friends">
                  <img draggable="false" src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3px">Friends
                </label>
                <input class="form-check-input" type="checkbox" checked value="" id="filter-friends" style="margin: 5px; margin-top: 3px; accent-color: #58baed; border: 2px solid #58baed; float: right">
              </div>
            </li>
            <li style="display: inline-block">
              <!-- <input type="checkbox" id="filter-friends">
              <label for="filter-friends">Friends</label> -->
              <div class="form-check" style="padding-left: 0px">
                <label class="form-check-label" for="filter-others">
                  <img draggable="false" src="../assets/img/ic_nuc_follow3_check.png" style="width: 14px;height: 14px;margin-left: 5px;margin-right: 5px;margin-top: -3px">Others
                </label>
                <input class="form-check-input" type="checkbox" value="" id="filter-others" style="margin: 5px; margin-top: 3px; accent-color: #58baed; border: 2px solid #58baed; float: right">
              </div>
            </li>
          </ul>
        </div>

      </div>
    </div>



    <div class="timeline" id="pbr-timeline">
      <?php //require('timeline_products.php'); 
      ?>
    </div>
    <div id="loader_message" class=" d-none my-2 justify-content-center align-items-center">
      <div class="spinner-border text-dark" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <a id="to-new-post" class="float">
      <i class="fa fa-plus my-float"></i>
    </a>
  </div>

  <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content animate-bottom">
        <div class="modal-back" data-bs-dismiss="modal" onclick="hideAddToCart()">
          <img draggable="false" src="../assets/img/icons/Back-(White) - Copy.png" />
        </div>
        <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="modal fade" id="modal-categoryFilter" tabindex="-1" role="dialog" aria-labelledby="modal-categoryFilter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Select category
        </div>
        <div class="modal-body" id="categoryFilter-body" style="position: relative;">
          <ul></ul>
        </div>
        <div class="modal-footer">
          <button class="btn btn-dark" type="button" id="submitCategory">Submit</button>
        </div>
      </div>
    </div>
  </div> -->

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

                <form action="/action_page.php">
                  <ul style="padding-left:0">

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

                  </ul>

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
                      <button id="btn-submit-report-content" class="btn btn-dark" type="button" onclick="reportContentSubmit()">Submit</button>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-category2" tabindex="-1" role="dialog" aria-labelledby="modal-category2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content animate-bottom">
        <div class="modal-body p-4" id="modal-add-body" style="position: relative;">

          <div class="row gx-0">
            <div class="col-12">
              <div class="col-12 mb-3 text-center">
                <h5 id="why-report-user">Why you want to report this user?</h5>
              </div>
              <div class="col-12" style="float: left; font-size: 16px">

                <form action="/action_page.php">

                  <ul style="padding-left:0">
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
                  </ul>

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
                      <button id="btn-submit-report-user" class="btn btn-dark" type="button" onclick="reportUserSubmit()">Submit</button>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

  <!-- show product modal -->
  <div class="modal fade" id="modal-product" tabindex="-1" aria-labelledby="modal-product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
        </div>
      </div>
    </div>
  </div>
  <!-- show product modal -->

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

  <!-- delete post -->
  <div class="modal fade" id="delete-post-info" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <!-- <h6>Product added to cart!</h6> -->
        </div>
        <div class="modal-footer">
          <button id="delete-post-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- confirm remove post -->
  <div class="modal fade" id="modal-confirm-remove" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <!-- <h6>Product added to cart!</h6> -->
          <h6>Remove this post?</h6>
        </div>
        <div class="modal-footer">
          <button id="remove-post-accept" type="button" class="btn btn-addcart">Yes</button>
          <button id="remove-post-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <!-- consent new post -->
  <div class="modal fade" id="modal-consent-newpost" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <!-- <h6>Product added to cart!</h6> -->
          <h6>Disclaimer and Consent</h6>
          <p id="consent-newpost"></p>
        </div>
        <div class="modal-footer">
          <button id="consent-newpost-no" type="button" class="btn btn-addcart" data-bs-dismiss="modal" onclick="consentAnswer(0)">NO</button>
          <button id="consent-newpost-yes" type="button" class="btn btn-addcart" onclick="consentAnswer(1)">YES</button>
        </div>
      </div>
    </div>
  </div>

  <!-- check profile fail -->
  <div class="modal fade" id="modal-check-profile-error" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <!-- <h6>Product added to cart!</h6> -->
          <h6>An error occured.</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- post removed -->
  <div class="modal fade" id="modal-post-removed" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <!-- <h6>Product added to cart!</h6> -->
          <h6>Post removed.</h6>
        </div>
        <div class="modal-footer">
          <button id="post-removed-close" type="button" class="btn btn-addcart">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php if ($countGIF > 0) { ?>
    <div id="gif-container">

    </div>

  <?php } ?>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  <!-- <script src="../assets/js/update_counter.js?r=<?= time(); ?>"></script> -->
  <script src="../assets/js/script-filter.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/script-timeline-min.js?random=<?= time(); ?>" defer></script>
  <script src="../assets/js/profile-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>

  <script src="../assets/js/tab5-collection-min.js?r=<?= time(); ?>"></script>
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
  <script>
    // $('#addtocart-success').on('hidden.bs.modal', function() {
    //   location.reload();
    // });

    localStorage.setItem("is_grid", "0");

    if (localStorage.lang == 0) {
      // $('input#query').attr('placeholder', 'Search');
      $('#story-all-posts').text("All Posts");
    } else {
      // $('input#query').attr('placeholder', 'Pencarian');
      $('#story-all-posts').text("Semua Post");
      $('.button_edit').text('Ubah');
      $('.button_delete').text('Hapus');
    }

    function consentAnswer(ans) {
      let f_pin = new URLSearchParams(window.location.search).get("f_pin");

      if (window.Android) {
        f_pin = window.Android.getFPin();
      }

      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          console.log("CONSENT", xmlHttp.responseText);
          $("#modal-consent-newpost").modal("hide");
          if (ans == 1) {
            openNewPost();
          }
        }
      }
      xmlHttp.open("get", "/nexilis/logics/answer_user_consent?f_pin=" + f_pin + "&consent=mab_consent_posting&answer=" + ans);
      xmlHttp.send();
    }

    function checkConsent() {
      let f_pin = new URLSearchParams(window.location.search).get("f_pin");

      if (window.Android) {
        f_pin = window.Android.getFPin();
      }

      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          console.log("CONSENT", xmlHttp.responseText);

          if (xmlHttp.responseText == "" || xmlHttp.responseText == "0") {
            $("#modal-consent-newpost").modal("show");
          } else if (xmlHttp.responseText == "1") {
            openNewPost();
          }
        }
      }
      xmlHttp.open("get", "/nexilis/logics/check_user_consent?f_pin=" + f_pin + "&consent=mab_consent_posting");
      xmlHttp.send();


    }

    function openNewPost(checkIOS = false) {

      // if (consent == "yes") {
      let f_pin = new URLSearchParams(window.location.search).get("f_pin");

      if (window.Android) {
        f_pin = window.Android.getFPin();
      }

      if (f_pin != "") {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onerror = function() {
          $("#modal-check-profile-error").modal("show");
        }
        xmlHttp.onreadystatechange = function() {
          if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log("ICP", xmlHttp.responseText);

            if (xmlHttp.responseText == "1") {
              window.location = "tab5-new-post?f_pin=" + f_pin + "&origin=2";
              localStorage.setItem("is_grid", "0");
              localStorage.setItem('activeQueryMixed', window.location.search);
            } else {
              if (window.Android) {
                if (window.Android.checkProfile()) {
                  window.location = "tab5-new-post?f_pin=" + f_pin + "&origin=2";
                  localStorage.setItem("is_grid", "0");
                  localStorage.setItem('activeQueryMixed', window.location.search);
                }
              } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
                window.webkit.messageHandlers.checkProfile.postMessage({
                  param1: '',
                  param2: 'newpost'
                });
                return;
              }
            }
          }
        }
        xmlHttp.open("get", "/nexilis/logics/check_change_profile?f_pin=" + f_pin);
        xmlHttp.send();
      }
      // }



      // if (window.Android) {
      //   // if (typeof window.Android.checkFeatureAccess === "function" && window.Android.checkFeatureAccess("new_post") && window.Android.checkProfile()) {
      //   //   window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
      //   // }
      //   if (window.Android.checkProfile()) {
      //     window.location = "tab5-new-post?f_pin=" + window.Android.getFPin();
      //   }
      // } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
      //   window.webkit.messageHandlers.checkProfile.postMessage({
      //     param1: '',
      //     param2: 'newpost'
      //   });
      //   return;
      // } else {
      //   let fpin = new URLSearchParams(window.location.search).get("f_pin");
      //   window.location = "tab5-new-post?f_pin=" + fpin;
      // }
      // localStorage.setItem("is_grid", "0");
      // localStorage.setItem('activeQueryMixed', window.location.search);
    }

    $(document).ready(function() {

      $('#to-new-post').click(function() {
        openNewPost();
      })

      localStorage.setItem('origin_page', location.href);

      if (window.Android) {
        window.Android.tabShowHide(true);
      } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.tabShowHide) {
        window.webkit.messageHandlers.tabShowHide.postMessage({
          param1: true,
        });
      }
    })

    if (localStorage.lang == 1) {

      $('#why-report-content').text('Apakah anda yakin untuk melaporkan konten ini?');
      $('#why-report-user').text('Apakah anda yakin untuk melaporkan pengguna ini?');

      $('.report-content-category-en').hide();
      $('.report-user-category-en').hide();

      $('#btn-submit-report-user').text('Laporkan');
      $('#btn-submit-report-content').text('Laporkan');

      $('#report-submited').text('Laporan anda berhasil.');
      $('#report-close').text('Tutup');

      $("#modal-check-profile-error .modal-body h6").text("Terjadi kesalahan.");
      $("#modal-check-profile-error .modal-footer button").text("Tutup");

      $("#modal-consent-newpost .modal-body p").text("Aplikasi kami membutuhkan izin akses ke foto dan video Anda untuk membuat dan mengunggah konten. Aplikasi kami menyimpan file foto dan/atau video yang digunakan dalam pembuatan konten dalam server, tapi tidak mengirimkan atau membagi data tersebut ke pihak ketiga dalam situasi apapun.")

      $("#modal-consent-newpost .modal-footer #consent-newpost-no").text("TIDAK")
      $("#modal-consent-newpost .modal-footer #consent-newpost-yes").text("YA")

    } else {

      $("#modal-consent-newpost .modal-body p").text("Our apps require permission to access your photos and videos to create and upload posts. Our apps store photo and/or video files used in the creation of these contents in its server, but does not share or assign them to third parties under any circumstances.")

      $('.report-content-category-id').hide();
      $('.report-user-category-id').hide();

      $("#modal-consent-newpost .modal-footer #consent-newpost-no").text("NO")
      $("#modal-consent-newpost .modal-footer #consent-newpost-yes").text("YES")

    }
  </script>

  <!-- <script src="../assets/js/paliopay-dictionary.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/paliopay.js?random=<?= time(); ?>"></script> -->
</body>

</html>