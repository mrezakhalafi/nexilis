<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$f_pin = $_GET['f_pin'];

$dbconn = paliolite();

$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

$query = $dbconn->prepare("SELECT * FROM LINK_CATEGORY");
$query->execute();
$groups  = $query->get_result();
$query->close();

$categories = array();
while ($group = $groups->fetch_assoc()) {
    $categories[] = $group;
};

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
    $sqlBE = "SELECT BE AS BE_ID FROM USER_LIST WHERE F_PIN = '$f_pin'";
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
  
  $sqlBG = "SELECT `VALUE` FROM `PREFS` WHERE `BE` = $be AND `KEY` = 'app_builder_background'";
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

// CHECK IF HAVE SHOP

$query = $dbconn->prepare("SELECT * FROM SHOP WHERE CREATED_BY = '".$f_pin."'");
$query->execute();
$checkShop = $query->get_result()->fetch_assoc();
$query->close();

$_SESSION['id_shop'] = $checkShop['CODE'];

// print_r($_SESSION['id_shop']);

?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/tab3-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jQueryRotate.js"></script>
    <script src="../assets/js/jquery.validate.js"></script>
    <script src="../assets/js/isInViewport.min.js?v=<?= time(); ?>"></script>
    <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
    <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />

    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>

    <?php
    $rand_bg = rand(1, 18) . ".png";
    ?>

    <style>
        body {
            background-image: url('<?php echo $bg_url; ?>');
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
            width: 100%;
        }

        input#query {
            background-color: rgba(255, 255, 255, 0);
        }

        .grid-stack>.grid-stack-item>.grid-stack-item-content {
            overflow-y: hidden !important;
        }

        #content-grid {
            /* margin-top: 175px; */
        }

        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 75px;
            right: 20px;
            background-color: rgba(0, 0, 0, .65);
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            z-index: 999;
        }

        .my-float {
            /* margin-top: 22px; */
            z-index: 999;
        }
    </style>

</head>


<body class="tab3">
    <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
    <div class="container-fluid px-0">
        <div id="header-layout" class="sticky-top">
            <div id="story-container">
                <?php //require('timeline_story_container.php'); ?>
            </div>
            <div id="header" class="row justify-content-between">
                <div class="col-12">
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
                            <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
                            <img id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png" onclick="voiceSearch();">
                            <!-- </div> -->
                        </form>
                    </div>
                </div>
                <div id="gear-div" class="col-2 d-none align-items-center justify-content-center" style="padding-right: 9px; padding-left: 9px;">
                    <a class="me-2" id="to-grid-layout">
                        <div class="position-relative">
                            <img class="header-icon" src="../assets/img/ic_grid.png">
                            <!-- <span id='counter-here'></span> -->
                        </div>
                    </a>
                    <a id="to-list-layout" class="me-3">
                        <div class="position-relative">
                            <img class="header-icon mx-auto" src="../assets/img/ic_list.png">
                            <!-- <span id='counter-notifs'></span> -->
                        </div>
                    </a>
                </div>
            </div>
            <div id="category-tabs" class="ms-2 small-text d-none">
                <ul class="nav nav-tabs horizontal-slide" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="categoryFilter-all" data-bs-toggle="tab" role="tab">All</a>
                    </li>
                    <?php

                    $filters = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_products_category.php');

                    for ($i = 0; $i < count($filters); $i++) {

                        $idFilter = $filters[$i]["ID"];
                        $nameFilter = $filters[$i]["CODE"];
                        echo '<li class="nav-item">';

                        echo '<a class="nav-link" id="categoryFilter-' . $idFilter . '" data-bs-toggle="tab" role="tab">' . $nameFilter . '</a>';
                        echo '</li>';
                    }

                    ?>
                </ul>
            </div>
        </div>
        
        <?php 

        if ($checkShop): ?>

        <a id="to-upload-listing" class="float">
            <i class="fa fa-plus my-float"></i>
        </a>

        <?php else: ?>

        <a id="to-open-shop" class="float">
            <i class="fa fa-plus my-float"></i>
        </a>

        <?php endif; ?>

    </div>
    <div class="box">
        <div id="container">
            <div id="loading" class="d-none">
                <div class="col-sm mt-5">
                    <h5 class="prod-name" style="text-align:center;">Sedang memuat. Tunggu sebentar...</h5>
                </div>
            </div>
            <div class="d-none" id="no-stores">
                <div class="col-sm mt-5">
                    <h5 class="prod-name" style="text-align:center; margin-top:100px;">Nothing matches your criteria</h5>
                </div>
            </div>
            <div id="content-grid" class="grid-stack grid-stack-3" style="inset: -1px;">
                <div id="grid-overlay" class="overlay d-none"></div>
            </div>
        </div>
        <script>
            const search = <?php if (isset($_GET['query'])) {
                                echo '"' . $_GET['query'] . '"';
                            } else {
                                echo "null";
                            } ?>;
            const filter = <?php if (isset($_GET['filter'])) {
                                echo '"' . $_GET['filter'] . '"';
                            } else {
                                echo "null";
                            } ?>;
        </script>
    </div>
    <div class="bg-grey stack-top" style="display: none;" id="stack-top">
        <div class="container small-text">
            <div id="sort-store-popular" class="bg-white row py-3">
                <div class="col-6" style="font-weight:500;">Popular</div>
                <div class="col-6 check-mark">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-date" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Date Added (New to Old)</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
            <div id="sort-store-follower" class="bg-white row py-3" style="margin-top: 1px;">
                <div class="col-6" style="font-weight:500;">Followers</div>
                <div class="col-6 check-mark d-none">
                    <img class="float-end" src="../assets/img/icons/Check-(Orange).png" style="width: 15px; height: 15px;"></img>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->

    <div class="modal fade" id="modal-addtocart" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body p-0" id="modal-add-body" style="position: relative;">
                </div>
            </div>
        </div>
    </div>

</body>

<!-- <script type="text/javascript" src="../assets/js/script-filter.js?random=<?= time(); ?>"></script> -->
<script src="https://apis.google.com/js/api.js" defer></script>
<!-- <script src="../assets/js/update_counter.js?random=<?= time(); ?>"></script> -->
<script type="text/javascript" src="../assets/js/script-store_list-commerce.js?random=<?= time(); ?>" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script>
    function myFunction() {
        var x = document.getElementById("stack-top");
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#grid-overlay').removeClass('d-none');
        } else {
            x.style.display = "none";
            $('#grid-overlay').addClass('d-none');
        }
    }

    if (localStorage.lang == 0) {
        $('input#query').attr('placeholder', 'Search');
        $('#no-stores .prod-name').text('Nothing matches your criteria');
    } else {
        $('input#query').attr('placeholder', 'Pencarian');
        $('#no-stores .prod-name').text('Tidak ada konten yang sesuai dengan kriteria');
    }

    // register merchant if checkProfile == true
    $(document).ready(function() {

      $('#to-open-shop').click(function() {
        if (window.Android) {
          if (window.Android.checkProfile()) {
            window.location = "tab5-open-shop?f_pin=" + window.Android.getFPin();
          }
        } else {
          let fpin = new URLSearchParams(window.location.search).get("f_pin");
          window.location = "tab5-open-shop?f_pin=" + fpin;
        }
      })

      $('#to-upload-listing').click(function() {
        if (window.Android) {
          if (window.Android.checkProfile()) {
            window.location = "tab5-upload-listing?f_pin=" + window.Android.getFPin() + "&id=<?= $checkShop['CODE'] ?>";
          }
        } else {
          let fpin = new URLSearchParams(window.location.search).get("f_pin");
          window.location = "tab5-upload-listing?f_pin=" + fpin + "&id=<?= $checkShop['CODE'] ?>";
        }
      })

    })
</script>

</html>