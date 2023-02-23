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

if (isset($_GET['post_id'])) {
  $post_id = $_GET['post_id'];
}

$query = $dbconn->prepare("SELECT * FROM POST WHERE POST_ID = '$post_id'");
$query->execute();
$postData = $query->get_result()->fetch_assoc();
$query->close();

// var_dump($postData);

function time_elapsed_string($datetime, $full = false)
{
  // echo $datetime;
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) : 'just now';
}

$seconds = intval(intval($postData["CREATED_DATE"]) / 1000);
$date_diff = time_elapsed_string('@' . strval($seconds));

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>News</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Font Icon -->
  <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

  <style>
    /* FOR HTML NOT OFFSIDE */

    html,
    body {
      max-width: 100%;
      overflow-x: hidden;
      font-family: 'Poppins';
    }

    .small-text {
      font-size: 10px;
    }

    .news-img {
      width: 100%;
      height: auto;
    }

    .news-title {
      font-size: 15px;
      margin-top: .25rem;
    }

    .news-content {
      font-size: 11px;
    }

    #news-section-img {
      margin-top: 70px
    }

    #row-title h4{
      line-height: 1.6;
    }

    #row-content .col-12 {
      line-height: 1.8;
      font-size: 14px;
    }
  </style>

</head>

<body style="background-color: #f1f1f1">

  <div class="p-3 shadow-sm fixed-top" style="border-bottom: 1px solid #e4e4e4; background-color: white">
    <div class="row">
      <div class="col-2 text-center">
        <img src="../../assets/img/membership-back.png" style="width: 30px; height: 30px" onclick="closeAndroid()">
      </div>
      <div class="col-8 ps-0 pt-1">
        <b style="font-size: 14px">News</b>
      </div>
      <div class="col-2">
        <!-- <img src="../../assets/img/action_share.png" style="width: 30px; height: 30px"> -->
      </div>
    </div>
  </div>


  <div class="container-fluid px-0" id="news-section-img">
    <div class="row px-0">
      <img class="news-img" src="../../images/<?= $postData["THUMB_ID"] ?>">
    </div>
  </div>

  <div class="container-fluid mt-2" id="news-section">
    <div class="row" id="row-source">
      <div class="col-6">
        <span class="text-secondary small-text"><?= $date_diff ?></span>
        <span class="small-text" style="margin: 0 3px;">•</span>
        <span class="text-secondary small-text">Admin</span>
      </div>
    </div>

    <div class="row" id="row-title">
      <div class="col-12">
        <h4><strong><?= $postData["TITLE"] ?></strong></h4>
      </div>
    </div>

    <div class="row mt-3" id="row-content">
      <div class="col-12">
        <?= nl2br($postData["DESCRIPTION"]) ?>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <!-- <script src="../assets/js/script-homepage.js"></script> -->
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

    }
  // }
</script>