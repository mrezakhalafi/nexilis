<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

session_start();

if (isset($_GET['f_pin'])) {
  $f_pin = $_GET['f_pin'];
  $_SESSION['user_f_pin'] = $f_pin;
} else if (isset($_SESSION['user_f_pin'])) {
  $f_pin = $_SESSION['user_f_pin'];
}

// GET USER INFO

$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'");
$query->execute();
$userData = $query->get_result()->fetch_assoc();
$query->close();

// get categories
$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 9 ORDER BY ID DESC");
$query->execute();
$categoryResult = $query->get_result();
$query->close();

$categoryList = array();
while ($category = $categoryResult->fetch_assoc()) {
  $categoryList[] = $category;
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>News Update</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Font Icon -->
  <link rel="stylesheet" href="../../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

  <style>
    /* FOR HTML NOT OFFSIDE */

    html,
    body {
      max-width: 100%;
      overflow-x: hidden;
      font-family: 'Poppins';
    }

    .single-news {
      border-radius: 20px;
      background-color: white;
      margin: 6px 0;
    }

    .news-img {
      border-radius: 20px 0px 0px 20px;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .news-title {
      font-size: 15px;
    }

    .news-content {
      font-size: 11px;
    }

    a.news-read-more {
      color: #ff6b00;
      text-decoration: none;
    }

    .small-text {
      font-size: 11px;
    }

    .nav-fill .nav-item .nav-link,
    .nav-justified .nav-item .nav-link {
      width: 100%;
      color: gray;
      font-weight: 600;
      background-color: white;
      font-size: 14px;
      padding: .75rem 1rem;
    }

    .nav-fill .nav-item .nav-link.active,
    .nav-justified .nav-item .nav-link.active {
      color: black;
    }

    .nav-item {
      color: black;
    }

    .nav-pills .nav-item.active:after {
      content: '';
      width: 60px;
      height: 2px;
      background: #ff6b00;
      position: absolute;
      bottom: 0;
      margin: auto;
      right: 0;
      left: 0;
    }

    #timeline-category .nav {
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    #timeline-category .nav-item {
      margin: 0 .3rem;
    }

    #timeline-category .nav-pills .nav-link {
      border-radius: 1rem;
      padding: .25rem .75rem;
      background-color: white;
      color: gray;
      white-space: nowrap;
    }

    #timeline-category .nav-pills .nav-link.active {
      border-radius: 1rem;
      background-color: #ff6b00;
      color: white;
    }

    .nav-item {
      font-size: 14px !important;
    }

    .small-text {
      font-size: 10px;
    }

    .single-news {
      border-radius: 20px;
      background-color: white;
      margin: 6px 0;
      box-shadow: 0 0 14px 0 rgb(0 0 0 / 3%), 0 0 10px 0 rgb(0 0 0 / 3%);
    }

    .news-img {
            border-radius: 20px 0px 0px 20px;
            width: 125px;
            height: 125px;
            object-fit: cover;
        }

        .news-title {
            font-size: 1rem;
            margin-top: .25rem;
        }

        .news-content {
            font-size: .7rem;
        }

    .news-img-col {
      position: relative;
    }

    .category-tag {
      font-size: 9px;
      background-color: blue;
      color: white;
      padding: 3px 8px;
      border-radius: 15px;
      position: absolute;
      left: 7px;
      top: 7px;
    }


    .news-content {
      font-size: .7rem;
    }

    a.news-read-more {
      color: #ff6b00;
      text-decoration: none;
    }

    .btn-loadmore {
      border-radius: 20px;
      border: 1px solid black;
    }

    .empty-logo {
      width: 40%;
      height: auto;
      margin-bottom: 2rem;
    }

    .empty-title {
      color: gray;
    }

    .empty-subtitle {
      font-size: 12px;
      color: gray;
    }

    #list-category.nav {
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    #list-category.nav li {
      margin-right: 2em;
      margin-bottom: 1.5em;
    }

    #list-category.nav .nav-link {
      color: gray;
      text-decoration: none;
      font-size: 1.5rem;
      position: relative;
      padding: 0;
    }

    #list-category.nav .nav-link.active {
      color: #005E6A;
      text-decoration: none;
      background-color: transparent;
    }

    #list-category.nav .nav-link.active:before {
      width: 32px;
    }

    #list-category.nav .nav-link:before {
      content: "";
      position: absolute;
      background: #005E6A;
      height: 4px;
      bottom: -16px;
      left: 2px;
      width: 0;
      border-radius: 1px;
      -webkit-transition: width .2s ease;
      -moz-transition: width .2s ease;
      -o-transition: width .2s ease;
      transition: width .2s ease;
    }
  </style>

</head>

<body style="background-color: #f1f1f1">

  <div class="px-3 py-2 shadow-sm fixed-top" style="border-bottom: 1px solid #e4e4e4; background-color: white">
    <div class="row">
      <div class="col-2 text-center">
        <img src="../../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
      </div>
      <div class="col-10 ps-0 pt-1">
        <b style="font-size: 14px">News Update</b>
      </div>
    </div>
  </div>

  <div class="row align-items-center pb-1 px-2" style="margin-top:70px;">
    <div class="col-12">
      <ul class="nav nav-pills" id="list-category">
        <li class="nav-item">
          <a class="nav-link category" id="all">
            All
          </a>
        </li>
        <?php foreach ($categoryList as $category) { ?>

          <li class="nav-item">
            <a class="nav-link category" aria-current="page" id="<?= $category["ID"] ?>">
              <?= $category["CODE"] ?>
            </a>
          </li>

        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid" id="news-section">


  </div>

  <div class="row mt-5 d-none" id="empty-news">
    <div class="col-8 mx-auto text-center">
      <img class="empty-logo" src="../../assets/img/empty-state.png">
      <h6 class="empty-title"><strong>No news at the moment</strong></h6>
      <p class="empty-subtitle">Please come back later.</p>
    </div>
  </div>

  <div class="row my-4 d-none" id="section-load-more">
    <div class="col-12 text-center">
      <a>
        <!-- <button class="btn btn-loadmore" id="btn-loadmore">
          <img src="../assets/img/action_docs.png" style="width:25px; height:auto">
          <span class="mb-0" style="font-size: 12px"><strong>Load more</strong></span>
        </button> -->
        <a id="btn-loadmore" style="text-decoration:none; color:#f54e07;">
          Lebih banyak <i class="fa fa-angle-right ms-2" style="font-size: 18px"></i>
        </a>
      </a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script src="../../assets/js/script-homepage.js?v=<?= time() ?>"></script>
</body>

</html>

<script>
  function closeAndroid() {

    // if (window.Android) {

    //   window.Android.finishGaspolForm();

    // } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishGaspolForm) {

    //   window.webkit.messageHandlers.finishGaspolForm.postMessage({
    //     param1: ""
    //   });
    //   return;

    // } else {

    history.back();

    // }
  }
</script>