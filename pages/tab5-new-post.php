<?php

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();
session_start();

// ID SHOP GET

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_SESSION['f_pin'])) {
  $id_shop = $_SESSION['f_pin'];
} else {
  $id_shop = $_GET['f_pin'];
  $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
  $query = $dbconn->prepare($queryCheckFpin);
  $query->execute();
  $getNewFPIN = $query->get_result()->fetch_assoc();
  $query->close();

  $id_shop = $getNewFPIN["F_PIN"];
  $_SESSION["f_pin"] = $id_shop;
}

// echo $id_shop;
// CHECK SHOP ID

if (!isset($id_shop)) {
  die("ID Shop Tidak Diset.");
}

if (isset($_GET["origin"])) {
  $origin = $_GET["origin"];
}

// SELECT COLOR CONFIGURATION

$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
$query->execute();
$nexilis_configuration = $query->get_result()->fetch_assoc();
$query->close();

$dbNewNus = newnus();

$bg_url = "";

if (isset($id_shop) && $id_shop != "") {
  $sqlBE = "SELECT ul.BE AS BE_ID, be.COMPANY_ID 
  FROM USER_LIST ul
  LEFT JOIN BUSINESS_ENTITY be ON ul.BE = be.ID
  WHERE ul.F_PIN = '$id_shop'";

  $queBEID = $dbconn->prepare($sqlBE);
  $queBEID->execute();
  $resBEID = $queBEID->get_result()->fetch_assoc();
  $queBEID->close();

  $be = $resBEID["BE_ID"];
  $c_id = $resBEID["COMPANY_ID"];

  $sqlBG = "SELECT `VALUE` FROM `PREFS_MAB` WHERE `BE` = $be AND `KEY` = 'app_builder_background'";
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
}

// OFFICIAL ACC
$querystr = "SELECT ule.OFFICIAL_ACCOUNT 
FROM USER_LIST_EXTENDED ule WHERE F_PIN = '$id_shop'";
$query = $dbconn->prepare($querystr);
$query->execute();
$user_type = $query->get_result()->fetch_assoc();
$query->close();

$ver_rand = time();


// GET CATEGORY OPTION BASED ON TAB DEFAULT CATEGORY 
$sqlWebform = "SELECT APP_URL, APP_URL_DEFAULT, CONTENT_TAB_LAYOUT, CONTENT_TAB_DEFAULT FROM WEBFORM WHERE COMPANY_ID = $c_id AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
$queWebform = $dbNewNus->prepare($sqlWebform);
$queWebform->execute();
$resWebform = $queWebform->get_result()->fetch_assoc();
$queWebform->close();

$defCategory = array();

// print_r($resWebform);
if (isset($origin)) {
  if ($resWebform["APP_URL"] == $origin) {
    if ($resWebform["APP_URL_DEFAULT"] != null && $resWebform["APP_URL_DEFAULT"] != "") {
      $defCategory = explode(",", $resWebform["APP_URL_DEFAULT"]);
    }
  } else if ($resWebform["CONTENT_TAB_LAYOUT"] == $origin) {
    if ($resWebform["CONTENT_TAB_DEFAULT"] != null && $resWebform["CONTENT_TAB_DEFAULT"] != "") {
      $defCategory = explode(",", $resWebform["CONTENT_TAB_DEFAULT"]);
    }
  }
}

// print_r($defCategory);

if (count($defCategory) > 0) {
  $whereArr = array();
  foreach ($defCategory as $cat) {
    array_push($whereArr, "CATEGORY_ID = '$cat'");
  }

  $whereString = implode(" OR ", $whereArr);

  $sqlDefaultCategory = "SELECT * FROM NEXILIS_CATEGORY WHERE " . $whereString;
} else {
  $sqlDefaultCategory = "SELECT * FROM NEXILIS_CATEGORY WHERE BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$id_shop')";
}

// echo $sqlDefaultCategory;

$query = $dbconn->prepare($sqlDefaultCategory);
$query->execute();
$catResult = $query->get_result();
$query->close();



$categoryData = array();
while ($res = $catResult->fetch_assoc()) {
  $categoryData[] = $res;
}
// print_r($catResult);
?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <title>Nexilis</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css?v=<?= $ver_rand ?>" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link href="../assets/css/jquery-ui.css" rel="stylesheet">

  <style>
    /* FOR MODAL CAN BE SLIDER DOWN */

    body {
      min-height: fit-content;
    }

    body::after {
      content: "";
      background-image: url('<?php echo $bg_url; ?>');
      /* background-image: url('/dashboardv2/uploads/background/<?php echo $rand_bg; ?>'); */
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

    body.modal-open {
      position: inherit;
    }

    /* FOR FULL WIDTH MODAL */

    .modal-dialog {
      max-width: 100%;
      margin: 0;
      bottom: 0;
      left: 0;
      right: 0;
      height: 100vh;
      display: flex;
      margin-top: 10px;
    }

    /* .modal-body {
      margin-top: -15px;
      padding-left: 0 !important;
      padding-right: 0 !important;
    } */

    .add-post-input {
      background-color: rgba(250, 250, 255, .8) !important;
      padding: 10px !important;
    }

    #upload-listing-dropdown {
      background-color: rgba(250, 250, 255, .8) !important;
    }

    div.section-post-description {
      padding-bottom: 5rem;
    }

    button#create-post:disabled {
      color: darkgray !important;
    }

    .form-check-input[type=checkbox] {
      border: 1px solid #8d8d8d !important;
    }

    .upload-listing-border {
      width: 125px;
      height: 125px;
    }

    .upload-listing-add {
      position: absolute;
      /* top: 42%; */
      /* left: 35%; */
      width: 30px;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }

    .navbar-back-black {
      width: 20px;
      height: auto;
    }

    .send-post-check {

      width: 25px;
      height: auto;
    }

    .upload-listing-image-slot {
      background-color: transparent;
      border: 0;
    }

    .nav-tabs {
      border: 0;
    }

    .attach-preview {
      object-fit: cover;
      width: 100%;
      height: 100%;
      border-radius: 10px;
      vertical-align: baseline;
    }

    .delete-listing-img {
      width: 30px;
      height: 30px;
      position: absolute;
      /* left: 0; */
      right: 5px;
      top: 5px;
    }

    .btn-addcart {
      background-color: black;
      padding: 5px 10px;
      color: white;
      /* border-radius: 50%; */
    }

    .btn-addcart:hover {
      color: white;
    }
  </style>
</head>

<body style="visibility:hidden;">
  <!-- <img src="<?php echo $bg_url; ?>" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999"> -->
  <div id="header"></div>

  <!-- NAVBAR -->

  <!-- <form method="POST"> -->
  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a onclick="goBack()">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" id="title-newpost"></p>
      <script>
        if (localStorage.lang == 0) {
          // $('#title-newpost').text('New Post');
          document.getElementById('title-newpost').innerHTML = 'New Post';
        } else {
          // $('#title-newpost').text('Buat Postingan');
          document.getElementById('title-newpost').innerHTML = 'Buat Postingan';
        }
      </script>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <button id="create-post" disabled class="text-purple navbar-new-post-next"></button>
        <script>
          if (localStorage.lang == 0) {
            // $('#title-newpost').text('New Post');
            document.getElementById('create-post').innerHTML = 'Post';
          } else {
            // $('#title-newpost').text('Buat Postingan');
            document.getElementById('create-post').innerHTML = 'Buat';
          }
        </script>
      </div>
    </div>
  </nav>

  <!-- SECTION NEW POST IMAGE -->

  <!-- <div class="section-new-post">
    <div class="row gx-0">

      <input type="file" id="addFile" class="d-none" onchange="loadFile(event)">
      <label for="addFile" id="addFile-label">
        <img src="../assets/img/tab5/Shop Manager/noimage-large.jpg" id="image-preview" class="new-post-image">
      </label>
      <img id="video-thumbnail" class="d-none">
    </div>
  </div> -->

  <div class="section-new-post mt-3">
    <div class="row small-text gx-0 ps-3">
      <div class="container">
        <ul class="nav nav-tabs horizontal-slide gx-0">

          <!-- LOOP 10 SLOT FOR UPLOAD MEDIA -->

          <?php for ($i = 1; $i <= 5; $i++) { ?>

            <li class="nav-item <?= $i != 1 ? 'd-none' : '' ?>" id="attach-item-<?= $i ?>">
              <div class="upload-listing-image-slot d-flex justify-content-center">
                <div class="single-upload-cover-listing">
                  <div class="image-upload">
                    <label for="file-input-<?= $i ?>" class="row" style="--bs-gutter-x: none">
                      <img src="../assets/img/tab5/delete-listing-2.png" data-delete-slot="<?= $i ?>" class="delete-listing-img" id="delete-listing-<?= $i ?>" style="z-index: 9999; display:none;">
                      <div id="image-preview-<?= $i ?>" class="upload-listing-border" style="border-radius:10px; border:1px solid black"></div>
                      <img src="../assets/img/tab5/Add-(Grey).png" class="upload-listing-add" id="upload-listing-add-<?= $i ?>" style="<?php echo $i != 1 ? "display:none;" : "" ?>">
                    </label>
                    <input id="file-input-<?= $i ?>" type="file" name="listing_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                    <img src="" id="thumbnail-<?= $i ?>" name="thumbnail-<?= $i ?>" style="display:none;" />
                  </div>
                </div>
              </div>
            </li>

          <?php } ?>

          <!-- <p class="ms-3 mb-1" id="file-required" style="font-size: 11px;"></p> -->
        </ul>
      </div>
    </div>
  </div>

  <!-- SECTION NEW POST FORM -->

  <div class="section-post-description mt-3">
    <div class="row gx-0 add-post">
      <input type="text" class="add-post-input" id="post_title" maxlength="64" name="post_title" placeholder="Write a title*">
      <div class="d-flex justify-content-end">
        <span id="counter-word-2" class="smallest-text text-grey">0</span><span class="smallest-text text-grey">/64</span>
      </div>
    </div>
    <div class="row gx-0 add-post">
      <textarea class="add-post-input" id="caption" maxlength="512" required rows="10" name="caption" placeholder="Write a Description*"></textarea>
      <div class="d-flex justify-content-end">
        <span id="counter-word" class="smallest-text text-grey">0</span><span class="smallest-text text-grey">/512</span>
      </div>
    </div>
    <div class="row gx-0 add-post">
      <input type="text" class="add-post-input" id="post_link" name="post_link" placeholder="URL">
    </div>
    <div class="row gx-0 add-post mt-2 <?= count($categoryData) > 0 ? "" : "d-none" ?>" style="padding-bottom: 0px" id="select-category-row">
      <div class="dropdown" style="margin-top:-7px; margin-bottom: 5px;">
        <button class="dropdown-toggle text-grey" style="margin-top: 10px; margin-bottom: -10px" type="button" id="upload-listing-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="listing-category-select" style="float:left; font-size: 14px">Category*</span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuSelectCategory">

          <!-- LOOPING FROM CATEGORY TABLE -->

          <?php foreach ($categoryData as $category) : ?>

            <li>
              <a class="dropdown-item" data-id="<?= $category['CATEGORY_ID'] ?>">
                <?= $category["NAME"] ?>
                <span id="<?= $category['PARENT'] ?>" class="d-none"></span>
              </a>
            </li>

          <?php endforeach; ?>

          <input type="hidden" id="category" class="category" name="category" value="">
          <input type="hidden" id="category_name" class="category_name" name="category_name" value="">
          <input type="hidden" id="parent_category" class="parent_category" name="parent_category" value="">
        </ul>
      </div>
    </div>
    <div class="row gx-0 mt-3">
      <div class="col-12 col-md-12 col-lg-12 upload <?= ($user_type["OFFICIAL_ACCOUNT"] == 1 || $user_type["OFFICIAL_ACCOUNT"] == 2 || $user_type["OFFICIAL_ACCOUNT"] == 3) && $origin == "1" && $be == 347 ? "" : "d-none" ?>" style="padding-bottom: 5px; padding-top: 5px; padding-left: 20px; padding-right: 20px">
        <div class="form-check" style="padding-top: 5px; padding-bottom: 5px !important">
          <input class="form-check-input" name="post_check_old" type="checkbox" value="1" id="paid_post" onchange="changeProductMenu()">
          <label class="form-check-label small-text text-grey" for="paid_post">
            <span id="is-product-lang" class="is-product-flag" style="font-size:14px">Is Product</span>
          </label>
        </div>
        <div class="form-check d-none" id="shop_only_section" style="padding-top: 5px; padding-bottom: 5px !important">
          <input class="form-check-input" name="shop_only" type="checkbox" value="1" id="shop_only">
          <label class="form-check-label small-text text-grey" for="shop_only" id="shop_only_label">
            <span class="shop-only" style="font-size:14px">Show in shop only</span>
          </label>
          <p id="shop-only-notice" class="small-text text-grey">By checking this box, your post will only show in your Profile page -> shop tab. Otherwise, it will show up as regular content on the front page.</p>
        </div>
      </div>
    </div>
    <div id="submenu_product" class="row gx-0 d-none" style="margin-bottom: 20px">
      <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-left add-post" style="padding-right: 5px">
        <div class="form-group">
          <input type="number" class="add-post-input" id="price" maxlength="64" name="price" placeholder="Price">
        </div>
      </div>
      <div class="col-6 col-md-6 col-lg-6 add-post" style="padding-left: 5px">
        <div class="form-group">
          <input type="number" class="add-post-input" id="stock" maxlength="64" name="quantity" placeholder="Quantity">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="filesize-warning" tabindex="-1">
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

  <div class="modal fade" id="form-check" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-3">
          <!-- <h6>File size is too large. Please limit it to 2MB or less.</h6> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="form-posting" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-3">
          <!-- <h6>File size is too large. Please limit it to 2MB or less.</h6> -->
        </div>
        <div class="modal-footer">
          <!-- <button id="form-posting-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button> -->
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="confirm-back" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-3">
          <!-- <h6>File size is too large. Please limit it to 2MB or less.</h6> -->
          <p id="confirm-back-message">You have already filled in some data. Are you sure to go back?</p>
        </div>
        <div class="modal-footer">
          <!-- <button id="form-posting-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button> -->
          <button type="button" class="btn btn-addcart" data-bs-dismiss="modal" id="confirm-back-no">No</button>
          <button type="button" class="btn btn-addcart" id="confirm-back-yes">Yes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="file-format-warning" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-3">
          <!-- <h6>File size is too large. Please limit it to 2MB or less.</h6> -->
          <p id="format-warning-msg">Unknown file format. Please choose another file.</p>
        </div>
        <div class="modal-footer">
          <!-- <button id="form-posting-close" type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button> -->
          <button type="button" class="btn btn-addcart" data-bs-dismiss="modal" id="confirm-back-no2"></button>
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" class="tagged_product" id="tagged_product" name="tagged_product" value="">
  <input type="hidden" class="post_photo" name="post_photo" value="">
  <!-- <input type="hidden" id="is_paid" name="is_paid" value="0"> -->
  <input type="hidden" name="post_check" id="post_check" value="0">

  <!-- Modal -->
  <!-- </div> -->
  <!-- </form> -->

  <input type="hidden" id="shop_id" value="<?= $id_shop ?>">

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script src="../assets/js/jquery-3.6.3.min.js"></script>
<script src="../assets/js/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<!-- <script type="module" src="../assets/js/translate.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.js"></script>
<script>
  // SCRIPT CHANGE LANGUAGE


  if (window.Android) {
    window.Android.tabShowHide(false);
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.tabShowHide) {
    window.webkit.messageHandlers.tabShowHide.postMessage({
      param1: false,
    });
  }

  function goBack() {

    let caption = document.getElementById("caption");
    let title = document.getElementById("post_title");
    let postButton = document.getElementById('create-post');
    let category = document.getElementById("category");

    let hasCategory = !$('#select-category-row').hasClass('d-none');

    let price = $('#price').val();
    let stock = $('#stock').val();

    // let isFileValid = (media_link != undefined && media_link != "");
    // console.log('array photo lengh', array_upload_photo)
    let isFileValid = array_upload_photo.length > 0;
    let isCaptionValid = caption.value.trim().length > 0;
    let isTitleValid = title.value.trim().length > 0;
    let isCategoryValid = true;

    if (hasCategory) {
      isCategoryValid = category.value.trim() !== '';
    }

    let f_pin = '';
    if (window.Android) {
      f_pin = window.Android.getFPin();
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    if (isFileValid || isCaptionValid || isTitleValid || isCategoryValid || $("#paid_post").is(':checked')) {
      window.removeEventListener("beforeunload", beforeUnloadListener, {
        capture: true
      })
      $('.modal#confirm-back').modal('show');
    } else if (localStorage.getItem('currentTab') != null) {
      let currentTab = localStorage.getItem('currentTab');

      if (currentTab == '0') {
        localStorage.removeItem('currentTab');
        window.location.href = 'tab1-main-only?f_pin=' + f_pin;
      } else if (currentTab == '1') {
        localStorage.removeItem('currentTab');
        window.location.href = 'tab3-main-only?f_pin=' + f_pin;
      } else if (currentTab == '2') {
        // window.location.href = 'tab3-main-only?f_pin=' + f_pin;
        let mode = localStorage.getItem('is_grid')
        if (mode) {
          if (mode == '0') {
            localStorage.removeItem('currentTab');
            window.location.href = 'tab1-main?f_pin=' + f_pin;
          } else {
            localStorage.removeItem('currentTab');
            window.location.href = 'tab3-main?f_pin=' + f_pin;
          }
        } else {
          localStorage.removeItem('currentTab');
          window.location.href = 'tab1-main?f_pin=' + f_pin;
        }
      } else if (currentTab == '4') {
        window.location.href = 'tab1-video?f_pin=' + f_pin;
      }
    } else {
      window.history.back();
    }


  }

  if (localStorage.lang == 1) {
    $('#is-product-lang').text('Produk');
  }

  $(document).ready(function() {
    function changeLanguage() {

      var lang = localStorage.lang;
      change_lang(lang);

    }

    if (localStorage.lang == 1) {
      // $('.navbar-title-2').css('margin-left', '30px');
      $('#post_title').attr('placeholder', 'Tulis Judul*');
      $('#caption').attr('placeholder', 'Tulis Deskripsi*');
      $('#location').attr('placeholder', 'Tambahkan Lokasi');
      $('#tagged-text').attr('placeholder', 'Kaitkan Produk');
      $('#search-tagged').attr('placeholder', 'Pencarian');
      $('#paid-content').text('Konten Berbayar');
      $('#price').attr('placeholder', 'Harga');
      $('#stock').attr('placeholder', 'Jumlah');
      $('.listing-category-select').text('Kategori*');
      $('.is-product-flag').text('Produk');
      // By checking this box, your post will only show in your Profile page -> shop tab. Otherwise, it will show up as regular content on the front page.
      $('.shop-only').text('Hanya tampil di toko');
      $('#shop-only-notice').text('Jika kamu menceklis kotak ini, kontenmu hanya akan muncul di halaman profil -> tab toko. Jika tidak, konten akan muncul sebagai konten biasa di halaman depan.');
      $('#confirm-back-no').text('Batal')
      $('#confirm-back-yes').text('Ya')
      $('#confirm-back-message').text('Kamu sudah mengisi beberapa data. Yakin ingin kembali?')
      $("#format-warning-msg").text("Format file tidak diketahui. Mohon pilih file lain");
    }

    // changeLanguage();
    $('body').css('visibility', 'visible');

    $('#confirm-back-yes').click(function() {
      let f_pin = '';
      if (window.Android) {
        f_pin = window.Android.getFPin();
      } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
      }
      if (localStorage.getItem('currentTab') != null) {
        let currentTab = localStorage.getItem('currentTab');

        if (currentTab == '0') {
          localStorage.removeItem('currentTab');
          window.location.href = 'tab1-main-only?f_pin=' + f_pin;
        } else if (currentTab == '1') {
          localStorage.removeItem('currentTab');
          window.location.href = 'tab3-main-only?f_pin=' + f_pin;
        } else if (currentTab == '2') {
          // window.location.href = 'tab3-main-only?f_pin=' + f_pin;
          let mode = localStorage.getItem('is_grid')
          if (mode) {
            if (mode == '0') {
              localStorage.removeItem('currentTab');
              window.location.href = 'tab1-main?f_pin=' + f_pin;
            } else {
              localStorage.removeItem('currentTab');
              window.location.href = 'tab3-main?f_pin=' + f_pin;
            }
          } else {
            localStorage.removeItem('currentTab');
            window.location.href = 'tab1-main?f_pin=' + f_pin;
          }
        } else if (currentTab == '4') {
          window.location.href = 'tab1-video?f_pin=' + f_pin;
        }
      } else {
        window.history.back();
      }
    })
  });

  // SCRIPT SLIDER TAGGED PRODUCT

  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));
  let init = parseInt(fixedPos.replace('px', ''));

  $('[data-bs-target="#taggedProductModal"]').click(function() {
    $('#taggedProductModal .modal-dialog').css('top', fixedPos);
    $('#taggedProductModal .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#taggedProductModal').draggable({
    handle: ".mod-header",
    axis: "y",
    drag: function(event, ui) {

      // console.log('init: ' + init);
      if (ui.position.top < init) {
        ui.position.top = init;
      }

      let dialog = ui.position.top + window.innerHeight;
      if (dialog - window.innerHeight > 50) {
        $('#taggedProductModal').modal('hide');
      }
    }
  });

  function showModal() {
    $('body').css('height', '900px');
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 1));
    $('#taggedProductModal').modal('show');
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    $('.modal-dialog').css('margin-top', '50px');
    $('.modal-dialog').css('padding-bottom', '50px');
  }

  $('#taggedProductModal').on('hidden.bs.modal', function() {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    $('.modal').css('top', '0px');
    $('.modal').css('left', '0px');
    $('body').css('height', '100%');
    $('.modal-dialog').css('margin-top', '-50px');
    $('.modal-dialog').css('padding-bottom', '-50px');
  })

  // IS PAID

  $("#flexCheckDefaultPaid").change(function(event) {
    if (this.checked) {
      $('#is_paid').val(1);
      localStorage.setItem('is_paid', 1);
      $('#price').attr('disabled', false);
    } else {
      $('#is_paid').val(0);
      $('#price').val("");
      $('#price').attr('disabled', true);
    }
  });

  // CHECKBOX TAGGED PRODUCT

  $(document).on("change", ":checkbox", function(e) {

    // IF MODAL OPENED RUN THIS CHECKBOX EVENT

    if ($('#taggedProductModal').is(':visible')) {
      var array_tagged = $('.tagged_product').val();
      var tagged_name = $('.tagged-input').val();

      if (this.checked) {
        $('.tagged_product').val($(this).val());
        $('.tagged-input').val($(this).val());

        if (array_tagged.length > 0) {
          array_tagged = array_tagged + "|" + $(this).data("code-tagged");
          tagged_name = tagged_name + ", " + $(this).val();
        } else {
          array_tagged = $(this).data("code-tagged");
          tagged_name = $(this).val();
        }

        $('.tagged_product').val(array_tagged);
        $('.tagged-input').val(tagged_name);

        // console.log(array_tagged);

        // SAVE TO LOCAL STORAGE

        localStorage.setItem('tagged_post', array_tagged);
        localStorage.setItem('tagged_post_name', $('.tagged-input').val());

      } else {

        var array_tagged = $('.tagged_product').val();
        var tagged_name = $('.tagged-input').val();

        if (array_tagged.includes("|")) {

          var a = array_tagged.replace("|" + $(this).data("code-tagged"), "");
          var b = tagged_name.replace(", " + $(this).val(), "");

          var c = a.replace($(this).data("code-tagged") + "|", "");
          var d = b.replace($(this).val() + ", ", "");

          $(".tagged_product").val(c);
          $(".tagged-input").val(d);

        } else {
          array_tagged = array_tagged.replace($(this).data("code-tagged"), "");
          tagged_name = tagged_name.replace($('.tagged-input').val(), "");

          $('.tagged_product').val(array_tagged);
          $('.tagged-input').val(tagged_name);
        }

        var array_tagged = $('.tagged_product').val();
        var tagged_name = $('.tagged-input').val();

        $('.tagged_product').val(array_tagged);
        $('.tagged-input').val(tagged_name);

        // console.log(array_tagged);

        // SAVE TO LOCAL STORAGE

        localStorage.setItem('tagged_post', array_tagged);
        localStorage.setItem('tagged_post_name', $('.tagged-input').val());

      }
    }
  });

  for (var i = 2; i <= 25; i++) {
    $('#upload-listing-add-' + i).hide();
    $('#file-input-' + i).prop("type", "text");
    $('#delete-listing-' + (i - 1)).hide();
  }

  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg', 'quicktime'];

  var loadFile = function(event, number) {
    // // console.log('event', event);
    // // console.log('event', event.target.files[0]);
    if (event.target.files[0].size <= 33554432) { //32 MB
      // var fileFormat = event.target.files[0].name.split('.')[1];
      var fileFormat = event.target.files[0].type.split('/')[1];
      var img, vid, canvas, ctx;
      var reader = new FileReader();
      reader.onload = createImage;
      reader.readAsDataURL(event.target.files[0]);

      function createImage() {
        console.log("FILEFORMAT", fileFormat);
        console.log("FILEREADER", reader.result)
        if ($image_type_arr.includes(fileFormat.toLowerCase())) {
          img = new Image();
          img.onload = imageLoaded;
          img.src = reader.result;
        } else if ($video_type_arr.includes(fileFormat.toLowerCase())) {
          // setImage(reader.result);
          // checkValid();
          vid = document.createElement('video');
          vid.onloadedmetadata = videoLoaded;
          vid.src = reader.result;
          vid.load();
        } else {

          if (localStorage.lang == 1) {
            $('#confirm-back-no2').text("Tutup");
          } else {
            $('#confirm-back-no2').text("Close");
          }

          $("#file-format-warning").modal("show");
          event.target.value = "";
        }
      }

      function imageLoaded() {
        let imgDataURL;

        canvas = document.createElement('canvas');
        ctx = canvas.getContext("2d");
        if (event.target.files[0].size > 204800) {
          // set size proportional to image
          canvas.height = canvas.width * (img.height / img.width);

          // step 1 - resize to 50%
          var oc = document.createElement('canvas'),
            octx = oc.getContext('2d');

          oc.width = img.width * 0.8;
          oc.height = img.height * 0.8;
          octx.drawImage(img, 0, 0, oc.width, oc.height);

          // step 2
          octx.drawImage(oc, 0, 0, oc.width, oc.height);

          // step 3, resize to final size
          ctx.drawImage(oc, 0, 0, oc.width, oc.height,
            0, 0, canvas.width, canvas.height);
        } else {
          canvas.width = img.naturalWidth;
          canvas.height = img.naturalHeight;
          ctx.drawImage(img, 0, 0);
        }
        imgDataURL = canvas.toDataURL("image/webp");
        setImage(imgDataURL, number);
        checkValid();
      }

      function videoLoaded() {
        let thumbnail;
        console.log('VIDEO LOADED');
        canvas = document.createElement('canvas');
        ctx = canvas.getContext("2d");
        // ctx.drawImage(vid, 0, 0, vid.videoWidth, vid.videoHeight);
        // let thumbnail = canvas.toDataURL("image/webp");

        // seek to user defined timestamp (in seconds) if possible
        if (vid.duration < 1) {
          reject("video is too short.");
          return;
        }

        // setImage(reader.result);
        // delay seeking or else 'seeked' event won't fire on Safari
        vid.addEventListener('seeked', () => {
          console.log('video is now paused at 0.1');
          // define a canvas to have the same dimension as the video
          canvas.width = vid.videoWidth;
          canvas.height = vid.videoHeight;
          ctx.drawImage(vid, 0, 0, canvas.width, canvas.height);
          // return the canvas image as a blob
          thumbnail = canvas.toDataURL('image/webp', 0.8);
          console.log("VID THUMB", thumbnail);
          setImage(reader.result, number, thumbnail);
          checkValid();
        });
        setTimeout(() => {
          vid.currentTime = 1;
        }, 1000);
        // extract video thumbnail once seeking is complete


      }
    } else {
      if (localStorage.lang == 0) {
        $('#filesize-warning .modal-body').html('<h6>File size is too large. Please limit it to 32MB or less.</h6>');
      } else {
        $('#filesize-warning .modal-body').html('<h6>Ukuran file terlalu besar. Mohon batasi menjadi 32MB atau kurang.</h6>');
      }
      $('#filesize-warning').modal('toggle');
    }
  }

  function pauseAll() {
    if ($('video#media').length > 0) {
      $('video#media').get(0).pause();
    }
  }

  function resumeAll() {
    if ($('video#media').length > 0) {
      $('video#media').get(0).play();
    }
  }

  var media_link;
  var video_thumb;
  var array_upload_photo = [];
  var array_thumbnail = [];

  function setImage(link, number, thumbnail = "") {

    var strings = link.split("/");

    checkValid();

    if (strings[0] == 'data:video') {
      // $('#image-preview-' + number).replaceWith('<video src="' + link + '#t=0.5" id="video-preview-' + number + '" autoplay muted type="video/mp4" class="upload-listing-border">');

      // $('#image-preview-' + number).html('<video src="' + link + '#t=0.5" id="video-' + number + '" autoplay muted type="video/mp4" class="attach-preview">');
      $('#thumbnail-' + number).attr('src', thumbnail);

      let vidPreview = document.createElement("video");
      vidPreview.src = link + '#t=1';
      vidPreview.id = "video-" + number;
      vidPreview.muted = true;
      vidPreview.autoplay = true;
      vidPreview.type = "video/*";
      vidPreview.classList.add("attach-preview");

      document.querySelector("#image-preview-" + number).append(vidPreview);

      vidPreview.play();
      // $("video#video-" + number).play()
    } else {
      $('#image-preview-' + number).html('<img src="' + link + '" id="image-' + number + '" class="attach-preview" style="vertical-align:baseline;">');
      $('#thumbnail-' + number).attr('src', link);
      // var output = document.getElementById('image-preview-' + number);
      // output.src = link;
    }

    $('#upload-listing-add-' + number).hide();
    $('#attach-item-' + (number + 1)).removeClass('d-none');
    $('#image-preview-' + number).css('border', '0')
    $('#upload-listing-add-' + (number + 1)).show();
    $('#file-input-' + (number + 1)).prop("type", "file");
    $('#thumbnail-' + (number + 1)).prop("type", "");

    $('#delete-listing-' + number).show();

    // WHICH SLOT CHANGED IMAGE UPLOADED [EX = 0,4,5]

    if (!array_upload_photo.includes(number)) {

      array_upload_photo.push(number);
      $('#array_upload_photo').val(array_upload_photo);
    }

    if (!array_thumbnail.includes(number)) {
      array_thumbnail.push(number);
    }

    // console.log('array photo', array_upload_photo);
    // console.log($('#array_upload_photo').val());

  }

  // DELETE DIV WHILE CLICK X

  var number_extend = 6;

  $('body').on("click", ".delete-listing-img", function() {



    $(this).parent().parent().parent().parent().parent().remove();

    // IF MATCH WITH SLOT DELETE THAT EX = DELETE 2 REMOVE 2 FROM ARRAY

    var delete_slot = $(this).data("delete-slot");
    array_upload_photo = array_upload_photo.filter(e => e !== delete_slot);
    array_thumbnail = array_thumbnail.filter(e => e !== delete_slot);

    $('#array_upload_photo').val(array_upload_photo);
    // console.log($('#array_upload_photo').val());

    // ADD MORE SLOT WHILE USER DELETED SLOT

    /* FOR
        <li class="nav-item <?= $i != 1 ? 'd-none' : '' ?>" id="attach-item-<?= $i ?>">
              <div class="upload-listing-image-slot d-flex justify-content-center">
                <div class="single-upload-cover-listing">
                  <div class="image-upload">
                    <label for="file-input-<?= $i ?>" class="row" style="--bs-gutter-x: none">
                      <img src="../assets/img/action_close_circle.png" data-delete-slot="<?= $i ?>" class="delete-listing-img" id="delete-listing-<?= $i ?>" style="z-index: 9999; display:none;">
                      <div id="image-preview-<?= $i ?>" class="upload-listing-border" style="border-radius:10px; border:1px solid black"></div>
                      <img src="../assets/img/tkt_add_follow.png" class="upload-listing-add" id="upload-listing-add-<?= $i ?>" style="<?php echo $i != 1 ? "display:none;" : "" ?>">
                    </label>
                    <input id="file-input-<?= $i ?>" type="file" name="listing_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                  </div>
                </div>
              </div>
            </li>
    */

    var add_slot =
      `<li class="nav-item d-none" id="attach-item-${number_extend}">
        <div class="upload-listing-image-slot d-flex justify-content-center">
            <div class="single-upload-cover-listing">
                <div class="image-upload">
                    <label for="file-input-` + number_extend + `" class="row" style="--bs-gutter-x: none">
                        <img src="../assets/img/tab5/delete-listing-2.png" data-delete-slot="` + number_extend + `" class="delete-listing-img" id="delete-listing-` + number_extend + `" style="z-index: 9999; display:none">
                        <div id="image-preview-${number_extend}" class="upload-listing-border" style="border-radius:10px; border:1px solid black"></div>
                        <img src="../assets/img/tab5/Add-(Grey).png" class="upload-listing-add" id="upload-listing-add-` + number_extend + `">
                    </label>
                    <input id="file-input-` + number_extend + `" type="file" accept="image/*,video/*" name="listing_thumbnail-` + number_extend + `" onchange="loadFile(event, ` + number_extend + `)" />
                    <img src="" id="thumbnail` + number_extend + `" name="thumbnail-` + number_extend + `" style="display:none;" />
                </div>
            </div>
        </div>
    </li>`
    $('.horizontal-slide').append(add_slot);

    // if ($('.horizontal-slide li').length == 5 && $('#attach-item-' + number_extend).hasClass('d-none')) {
    //   $('#attach-item-' + number_extend).removeClass('d-none');
    //   $('#upload-listing-add-' + number_extend).removeClass('d-none');
    // }

    if ($('.attach-preview').length < 5) {
      let getAttach = $('.attach-preview').length;
      $('.horizontal-slide li:nth-child(' + (getAttach + 1) + ')').removeClass('d-none');
    }

    number_extend++;
    checkValid();

    // IF LOCALSTORAGE DELETE EXIST

    window.localStorage.removeItem('get_listing_media_temp_1');

  });


  // FOR INPUT INSIDE DRAGGABLE CONTENT FOCUSED

  $(':input').bind('click', function() {
    $(this).focus();
  });

  // PREVENT INPUT WITH ENTER (DISABLE POPUP REQUIRED)

  $(':input').on('keypress', function(e) {
    return e.which !== 13;
  });

  // SAVE DATA FOR REFRESH

  $("#caption").blur(function() {
    var caption = $(this).val();
    // localStorage.setItem('caption_post', caption);
  })

  var get_caption = localStorage.getItem('caption_post');
  var get_media = localStorage.getItem('media_post');

  var get_category_name = localStorage.getItem('category_name');
  var get_category_id = localStorage.getItem('category_id');

  if (get_caption != null) {
    $('#caption').siblings('.palceholder').hide();
    // $("#caption").val(get_caption);
  }

  if (get_media != null) {
    // setImage(get_media);
  }

  if (get_category_id != null) {
    // $('.listing-category-select').text(get_category_name);
    // $('.category').val(get_category_id);
    // $('.category_name').val(get_category_name);
  }

  // CHANGE DROPDOWN AS NAME AS CLICK

  $('.dropdown-item').click(function() {
    // console.log('click');
    $('.listing-category-select').text($(this).text());
    $('.category').val($(this).data('id'));
    $('.category_name').val($(this).text());
    $('input#parent_category').val($(this).find('span').attr('id'));

    // SAVE DROPDOWN TO LOCAL STORAGE

    var category_name = $(this).text();
    var category_id = $(this).data('id');
    localStorage.setItem('category_name', category_name);
    localStorage.setItem('category_id', category_id);

    checkValid();
  });

  // FUNCTION VOICE SEARCH

  function voiceSearch() {
    Android.toggleVoiceSearch();
  }

  function dataURLtoFile(dataurl, filename) {
    // console.log(dataurl)
    var arr = dataurl.split(','),
      mime = arr[0].match(/:(.*?);/)[1],
      bstr = atob(arr[1]),
      n = bstr.length,
      u8arr = new Uint8Array(n);

    while (n--) {
      u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, {
      type: mime
    });
  }

  function submitVoiceSearch(searchQuery) {
    $('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
  }

  const beforeUnloadListener = (event) => {
    event.preventDefault();
    return event.returnValue = "Are you sure you want to exit?";
  };

  function checkValid() {


    let caption = document.getElementById("caption");
    let title = document.getElementById("post_title");
    let postButton = document.getElementById('create-post');
    let category = document.getElementById("category");

    let hasCategory = !$('#select-category-row').hasClass('d-none');

    let price = $('#price').val();
    let stock = $('#stock').val();

    // let isFileValid = (media_link != undefined && media_link != "");
    // console.log('array photo lengh', array_upload_photo)
    let isFileValid = array_upload_photo.length > 0;
    let isCaptionValid = caption.value.trim().length > 0;
    let isTitleValid = title.value.trim().length > 0;
    let isCategoryValid = true;

    if (hasCategory) {
      isCategoryValid = category.value.trim() !== '';
    }

    if (isFileValid || isCaptionValid || isTitleValid || isCategoryValid) {
      window.addEventListener("beforeunload", beforeUnloadListener, {
        capture: true
      });
    } else if (!isFileValid && !isCaptionValid && !isTitleValid && !isCategoryValid) {
      window.removeEventListener("beforeunload", beforeUnloadListener, {
        capture: true
      })
    }

    if (isFileValid && isCaptionValid && isTitleValid && isCategoryValid) {
      window.removeEventListener("beforeunload", beforeUnloadListener, {
        capture: true
      })
      // console.log('enabled');
      if ($("#paid_post").is(':checked')) {
        if (isFileValid && isCaptionValid && isTitleValid && price && stock) {
          postButton.disabled = false;
        } else {
          postButton.disabled = true;
        }
      } else {
        postButton.disabled = false;
      }
    } else {
      // // console.log('disabled');
      postButton.disabled = true;
    }
  }

  $('#caption').on("change keyup paste input", function() {
    checkValid();
  })
  $('#post_title').on("change keyup paste input", function() {
    checkValid();
  })
  $('#price').on("change keyup paste input", function() {
    checkValid();
  })
  $('#stock').on("change keyup paste input", function() {
    checkValid();
  })

  function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) {
      return map[m];
    });
  }

  function getHashtags(str) {
    var arr = [],
      re = /(#[a-z\d][\w-]*)/g,
      item;

    while (item = re.exec(str))
      arr.push(item[1]);

    return arr;
  }

  function sendData() {

    window.removeEventListener("beforeunload", beforeUnloadListener, {
      capture: true
    })

    var formData = new FormData();

    // let file = localStorage.getItem('link');

    let title = escapeHtml($('#post_title').val());

    var caption = escapeHtml($('#caption').val());

    let post_link = $('#post_link').val();

    // var file = media_link;
    // var format = file.split(";")[0].split('/')[1];

    // var converted_link = dataURLtoFile(file, "." + format);

    var f_pin = "";

    if (window.Android) {
      f_pin = window.Android.getFPin();
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    let url = post_link.replace(/^https?:\/\//, '');


    // formData.append('post_id', f_pin + new Date().getTime().toString());
    formData.append('title', title);
    formData.append('caption', caption);
    // formData.append('post_photo', converted_link);
    formData.append('array_upload_photo', array_upload_photo);
    formData.append('time', new Date().getTime());
    formData.append('category', localStorage.getItem('category_id'));
    formData.append('parent_category', $('input#parent_category').val());
    formData.append('f_pin', f_pin);
    formData.append('post_link', url);
    // formData.append('post_check', $('#post_check').val());
    formData.append('price', $('#price').val());
    formData.append('stock', $('#stock').val());

    // console.log('arr up', array_upload_photo);
    for (let i = 0; i < array_upload_photo.length; i++) {

      let file = $('#file-input-' + array_upload_photo[i])[0].files[0]

      // console.log('arr idx', '#file-input-' + array_upload_photo[i]);
      formData.append('listing_thumbnail-' + array_upload_photo[i], file);

      var thumbnail = $('#thumbnail-' + array_thumbnail[i]).attr('src');
      var format = thumbnail.split(";")[0].split('/')[1];
      var converted_link = dataURLtoFile(thumbnail, "." + format);
      console.log(format);
      formData.append('thumbnail-' + array_thumbnail[i], converted_link);
    }



    let post_status = 0;
    let isPaid = $('#paid_post').is(':checked') ? 1 : 0;
    let isShopOnly = $('#shop_only').is(':checked') ? 1 : 0;

    if (isPaid && isShopOnly) { // shop only
      post_status = 1;
    } else if (isPaid && !isShopOnly) { // post+shop
      post_status = 2;
    }

    formData.append('post_check', post_status);

    let titleHash = getHashtags(title);
    let descHash = getHashtags(caption);

    var allHash = [];
    allHash = titleHash.concat(descHash.filter((item) => titleHash.indexOf(item) < 0))

    // // console.log('tags', allHash) // c is [1, 2, 3, 101, 10]

    if (allHash.length > 0) {
      formData.append('hashtags', btoa(JSON.stringify(allHash)));
    }

    // Display the key/value pairs
    for (var pair of formData.entries()) {
      console.log(pair[0] + ', ' + pair[1]);
    }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {

      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // console.log(xmlHttp.responseText);
        if (xmlHttp.responseText == "Berhasil") {
          if (localStorage.lang == 0) {
            // alert("Post submitted!");
          } else {
            // alert("An error occured.");
          }

          // console.log('berhasil');
          // window.location = document.referrer;
          sessionStorage.setItem('refresh', 1);
          window.history.back();
        } else {

          if (localStorage.lang == 0) {
            $('#form-check .modal-body').html('<h6>An error occured while posting. Please try again.</h6>');
          } else {
            $('#form-check .modal-body').html('<h6>Terjadi kesalahan saat posting. Mohon coba lagi.</h6>');
          }
          $('#form-posting').modal('toggle');
          $('#form-check').modal('toggle');
          $("#create-post").prop("disabled", false);
          console.log("Gagal nih", xmlHttp.responseText);
        }
      }
    }
    xmlHttp.open("post", "../logics/tab5/insert_new_post");
    xmlHttp.send(formData);

  }

  let maxlength = 512;
  var inputEmail = document.querySelector('#caption');

  // inputEmail.addEventListener('keyup change paste input', function(e) {

  //   if (inputEmail.value.length > maxlength) {
  //     inputEmail.value = inputEmail.value.substring(0, maxlength);
  //   }

  // });
  $('#caption').on('keyup change paste input', function(e) {
    if (inputEmail.value.length > maxlength) {
      inputEmail.value = inputEmail.value.substring(0, maxlength);
    }
  })

  let title_max = 64;
  var inputTitle = document.querySelector('#post_title');

  $('#post_title').on('keyup change paste input', function(e) {

    if (inputTitle.value.length > title_max) {
      inputTitle.value = inputTitle.value.substring(0, title_max);
    }

  });

  $('#create-post').click(function() {
    // let isFile = document.getElementById("addFile").files.length > 0;
    // let isCaption = document.getElementById("caption").value.trim().length > 0;
    // let isTitle = document.getElementById("post_title").value.trim().length > 0;
    // // console.log("isFile", isFile);
    // // console.log("isCaption", isCaption);
    // if (isFile && isCaption && isTitle) {
    $(this).prop("disabled", true);

    if (localStorage.lang == 0) {
      $('#form-posting .modal-body').html('<h6>Upload in progress...</h6>');
    } else {
      $('#form-posting .modal-body').html('<h6>Sedang mengunggah...</h6>');
    }
    $('#form-posting').modal('toggle');
    sendData();
    // } else {
    //   if (localStorage.lang == 0) {

    //     $('#form-check .modal-body').html('<h6>Please fill image and caption before posting.</h6>');
    //   } else {
    //     $('#form-check .modal-body').html('<h6>Mohon isi gambar dan deskripsi sebelum melakukan posting.</h6>');
    //   }
    //   // $('#form-check').modal('toggle');
    // }
  })

  // WORD COUNTER SCRIPT

  var count = $('#caption').val().length;
  $('#counter-word').text(count);

  $('#caption').bind('input propertychange', function() {
    var count = $('#caption').val().length;
    $('#counter-word').text(count);
  });

  // WORD COUNTER TITLE SCRIPT

  var count = $('#post_title').val().length;
  $('#counter-word-2').text(count);

  $('#post_title').bind('input propertychange', function() {
    var count = $('#post_title').val().length;
    $('#counter-word-2').text(count);
  });

  // NEW FEATURE 

  function changeProductMenu() {

    checkValid();

    if ($("#paid_post").is(':checked')) {
      $('#submenu_product').removeClass('d-none');
      $('#post_check').val('1');
      $('#shop_only_section').removeClass('d-none');
    } else {
      $('#submenu_product').addClass('d-none');
      $('#post_check').val('0');
      $('#shop_only_section').addClass('d-none');
    }
  }
</script>

</html>