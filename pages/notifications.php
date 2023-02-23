<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Qiosk - Notifications</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/notifications.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="../assets/js/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../assets/js/jquery.ui.touch-punch.min.js"></script>

    <?php
    $rand_bg = rand(1,10) . ".png";
  ?>
  
  <style>
    body {
      background-image:url('../assets/img/lbackground_<?php echo $rand_bg;?>'); 
      background-size: 100% auto; 
      background-repeat: repeat-y;
    }
        #header-layout {
            background: <?= $setting['COLOR_PALETTE']; ?>;
            z-index: 99;
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

<body style="background-color: white;">
    <div class="container-fluid">
        <div id="header" class="col-12">
            <div class="row align-items-center" style="padding: 10px 0 10px 0;">
                <div class="col-1">
                    <a href="#" id="back-btn" onclick="goBack();">
                        <img src="../assets/img/icons/Back-(White).png" style="width:30px">
                    </a>
                </div>
                <div id="searchFilter-a" class="col-9 d-flex align-items-center justify-content-center text-white">
                    <form id="searchFilterForm-a" action="search-result" method=GET style="width: 90%;">
                        <?php
                        $query = "";
                        if (isset($_REQUEST['query'])) {
                            $query = $_REQUEST['query'];
                        }
                        ?>
                        <input id="query" placeholder="Search" type="text" class="search-query" name="query" onclick="onFocusSearch()" value="<?= $query; ?>">
                        <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
                        <img id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png">
                    </form>
                </div>
                <a class="col-1" href="cart.php?v=<?= time(); ?>">
                    <div class="position-relative me-2">
                        <img class="float-end" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
                        <span id="counter-here"></span>
                    </div>
                </a>
                <div class="col-1">
                    <a href="notifications.php">
                        <div class="position-relative me-2">
                            <img class="float-end" src="../assets/img/icons/Shop Manager/App-Notification-(white).png" style="width:30px">
                            <span id='counter-notifs'></span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="activity" role="tab" data-bs-toggle="tab" data-bs-target="activity-tab" aria-controls="activity-tab" aria-selected="true">Activity</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link" id="order" data-bs-toggle="tab" data-bs-target="order-tab" aria-controls="order-tab" aria-selected="false">
                                <div class="position-relative">
                                    <span>Order</span>
                                    <span id="counter-sub-tab">
                                        <!-- <span id="counter-subtab-text" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px;">
                                            3
                                        </span> -->
                                    </span>

                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="promotion" data-bs-toggle="tab" aria-selected="false">Promotion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0 tab-content">

        <!-- ACTIVITY TAB -->
        <div class="tab-pane fade" id="activity-tab" role="tabpanel" aria-labelledby="activity">
            <div class="container-fluid small-text pt-4">

            </div>
        </div>

        <!-- ORDER TAB -->
        <div class="tab-pane fade" id="order-tab" role="tabpanel" aria-labelledby="order">
            <div class="container-fluid small-text pt-4">
            </div>
        </div>

        <!-- PROMOTION TAB -->
        <div class="tab-pane fade" id="promotion-tab" role="tabpanel" aria-labelledby="promotion">
            <div class="container small-text pt-4">
                <div class="row mb-3">
                    -none-
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script src="../assets/js/update_counter.js?random=<?= time(); ?>"></script>
<script src="../assets/js/script-notifications.js?random=<?= time(); ?>"></script>

</html>