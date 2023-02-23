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

$be = $userData['BE'];
// FOR PADDING DIV BASIC ACCOUNT

$be_id = $userData['BE'];

// get categories
$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 9 ORDER BY ID DESC");
$query->execute();
$categoryResult = $query->get_result();
$query->close();

$categoryList = array();
while ($category = $categoryResult->fetch_assoc()) {
    $categoryList[] = $category;
}

$query = $dbconn->prepare("SELECT * FROM DIGINETS_THEME WHERE BE = 312");
$query->execute();
$section1Theme = $query->get_result();
$query->close();

// get categories
$sql = "SELECT ul.IMAGE, bc.*
FROM BNI_CAROUSEL bc 
LEFT JOIN USER_LIST ul ON ul.F_PIN = bc.F_PIN WHERE bc.BE = 312";

// $sql="SELECT * FROM USER_LIST where F_PIN = '$f_pin'";
$queryCarousel = $dbconn->prepare($sql);
$queryCarousel->execute();
$bniCarousel = $queryCarousel->get_result();
$queryCarousel->close();

// $bniCarousel = array();

// $domain = 'http://108.137.84.148';
$domain = '';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DigiNetS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <style>
        @font-face {
            font-family: "Biome";
            src: url("/nexilis/assets/font/biome/@font-face/1c6b6cddab6609324d9016562358e0cd.eot");
            /* IE9*/
            src: url("/nexilis/assets/font/biome/@font-face/1c6b6cddab6609324d9016562358e0cd.eot?#iefix") format("embedded-opentype"),
                /* IE6-IE8 */
                url("/nexilis/assets/font/biome/@font-face/1c6b6cddab6609324d9016562358e0cd.woff2") format("woff2"),
                /* chrome firefox */
                url("/nexilis/assets/font/biome/@font-face/1c6b6cddab6609324d9016562358e0cd.woff") format("woff"),
                /* chrome firefox */
                url("/nexilis/assets/font/biome/@font-face/1c6b6cddab6609324d9016562358e0cd.ttf") format("truetype"),
                /* chrome firefox opera Safari, Android, iOS 4.2+*/
                url("/nexilis/assets/font/biome/@font-face/1c6b6cddab6609324d9016562358e0cd.svg#Biome") format("svg");
            /* iOS 4.1- */
        }

        /* FOR HTML NOT OFFSIDE */

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            font-family: 'Poppins';
        }

        #main-div {
            border-radius: 1rem 1rem 0 0;
            background-color: #ffa500;
            margin-top: 60px;
        }

        .era-insurance {
            background-color: white;
            border-radius: .6rem;
        }

        .progress {
            background-color: grey;
            height: 10px;
        }

        .card {
            border-radius: 1rem;
            /* color: white !important; */
        }

        .caption-content {
            font-family: 'Biome';
            font-weight: light;
            color: rgba(217, 217, 217, 1)
        }

        .small-text {
            font-size: .7rem;
        }

        #story-container {
            margin: 0;
            padding: 0;
            width: 100%;
            /* background: white; */
            overflow-x: auto;
            box-sizing: border-box;
        }

        #story-container ul {
            list-style-type: none;
            user-select: none;
            display: flex;
            margin-bottom: 0;
            overflow-x: auto;
            padding-inline-start: 0;
            padding: 10px 0;
        }

        #story-container ul li {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3px;
            /* margin: 0 2px; */
        }

        #story-container ul li:first-child {
            padding-left: 10px
        }

        #story-container ul li:last-child {
            padding-right: 10px
        }

        #story-container ul li .story {

            width: 48px;
            height: 48px;
            padding: 2px;
            border-radius: 50%;
            /* background: rgba(255, 255, 255, 0); */
            position: relative;
            margin-bottom: 5px;
        }

        #story-container ul li .story img {
            padding: 1px;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* #story-container ul li.selected {
    background: white;
} */

        #story-container ul li.selected .story img {
            /* background: #cccf12; */
            box-shadow: inset 0 0 0 4px #cccf12;
        }

        #story-container ul li span {
            color: black;
            font-size: 8.5px;
            text-align: center;
            white-space: nowrap;
            width: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            /* margin-top: 5px; */
        }

        #story-container ul li span img {
            width: 9px;
            height: 9px;
            margin-right: 2px;
            vertical-align: middle;
        }

        .single-news {
            border-radius: 20px;
            background-color: white;
            /* border: 1px solid black; */
            margin: 6px 0;
            box-shadow: 0 0 14px 0 rgb(0 0 0 / 3%), 0 0 10px 0 rgb(0 0 0 / 3%);
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
            color: #f54e07;
            text-decoration: none;
            background-color: transparent;
        }

        #list-category.nav .nav-link.active:before {
            width: 32px;
        }

        #list-category.nav .nav-link:before {
            content: "";
            position: absolute;
            background: #f54e07;
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

        a.news-read-more {
            color: #ff6b00;
            text-decoration: none;
        }

        .btn-loadmore {
            border-radius: 20px;
            border: 1px solid black;
        }

        .card-body {
            padding: 1rem .5rem;
        }

        /* SECTION ONE CSS */

        .carousel-inner img {
            width: 100%;
            height: 100%;
        }

        #demo {
            width: 100%;
            height: 100%;
        }

        .ci-1 {
            /* height: 1000px; */
        }

        .cp-1 {
            object-fit: cover;
            object-position: center;
            width: 100%;
        }

        .carousel-indicators {
            /* bottom: -60px */
            bottom: unset !important;
        }

        .carousel-indicators.ind-section-2 {
            bottom: -60px !important;
        }

        .carousel-indicators [data-bs-target] {
            height: 6px;
            width: 6px;
            border: 1px solid transparent;
            border-radius: 1000px;
            opacity: 0.8;
            background-color: rgb(220, 220, 220, 1);
        }

        .carousel-indicators>.active {
            opacity: 1;
            background-color: #00b500;
        }

        form#searchFilterForm-a {
            border: 1px solid #c9c9c9;
            background-color: rgba(255, 255, 255, .55);
            width: 100%;
        }

        #searchFilter-a {
            margin-left: 0;
            position: absolute;
            z-index: 5000;
        }

        input#query {
            background-color: rgba(255, 255, 255, 0);
            font-size: 13px;
            color: white;
        }

        input:focus {
            outline: none;
        }

        #searchFilterForm-a #voice-search {
            width: 25px;
            height: 25px;
        }

        #searchFilterForm-a #delete-query {
            width: 22px;
            height: 22px;
            margin-right: 7px;
        }

        form#searchFilterForm-a {
            display: flex;
            flex-direction: row;
            border-radius: 20px;
            padding: 1px 12px;
            background-color: rgba(0, 0, 0, 0.5);
            ;
            align-items: center;
            height: 35px;
        }

        .rounded {
            border-radius: 10px !important;
        }

        input.search-query {
            flex-grow: 2;
            border: none;
            margin-bottom: 0;
            -webkit-border-radius: 20px;
            -moz-border-radius: 20px;
            border-radius: 20px;
            font-size: 10px;
        }

        footer {
            font-size: .8em;
            color: #3F4342;
        }

        footer #footer-logo img {
            width: 100px;
            height: auto;
        }

        #social-media i * {
            font-size: 2.5 em;
        }

        #section-carousel-caption {
            bottom: -10%;
            right: 0;
            left: 0;
            margin: 20px;
            position: absolute;
        }

        #section-carousel-caption .carousel-inner {
            border-radius: 20px;
        }

        .caption-bg {
            border-radius: 20px;
            background-color: rgb(0, 0, 0, 0.5);
            padding-top: 20px;
            padding-left: 35px;
            padding-right: 35px;
            height: 100%;
        }

        /* #demo .carousel-item-prev, */
        #caption .carousel-item-prev {
            border-radius: 20px;
        }

        /* #demo .carousel-item-next, */
        #caption .carousel-item-next {
            border-radius: 20px;
        }

        /* #demo .carousel-item-start, */
        #caption .carousel-item-start {
            border-radius: 20px;
        }

        /* #demo .carousel-item-end, */
        #caption .carousel-item-end {
            border-radius: 20px;
        }

        footer .carousel-item#footer-bni {
            /* background-color: #1BC1CE; */
        }

        footer a {
            
            text-decoration: none;
        }

        footer #footer-bni a {
            color: #F15A23;
        }

        footer #footer-bri a,
        footer #footer-btn a,
        footer #footer-mandiri a {
            color: #014a94;
        }

        #footer-mandiri ul li {
            margin-bottom: 8px;
        }
    </style>

</head>

<body style="visibility:hidden">

    <div class="container-fluid px-0">

        <div id="section-one" style="height: 100vh; position:relative;">

            <div id="demo" class="carousel lazy-load slide carousel-sync" data-bs-ride="carousel" data-bs-interval="false">

                <div id="searchFilter-a" class="col-12 d-flex align-items-center justify-content-center text-white mt-4 px-4">
                    <form id="searchFilterForm-a">
                        <!-- <div class="d-flex align-items-center div-search"> -->
                        <?php
                        $query = "";
                        if (isset($_REQUEST['query'])) {
                            $query = $_REQUEST['query'];
                        }
                        ?>
                        <input id="query" type="text" class="search-query" name="query" placeholder="Search..." value="<?= $query; ?>">
                        <img class="d-none" id="delete-query" src="../../assets/img/icons/X-fill.png">
                        <img id="voice-search" src="../../assets/img/icons/Voice-Command.png" onclick="voiceSearch();">
                        <!-- </div> -->
                    </form>
                </div>

                <div class="carousel-indicators" style="top: 75px !important">

                    <?php foreach ($section1Theme as $i => $theme) : ?>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="<?= $i ?>" <?php if ($i == 0) : ?> class="active" <?php endif; ?> aria-current="true" aria-label="Slide 1"></button>
                    <?php endforeach; ?>

                </div>
                <div class="carousel-inner">

                    <?php foreach ($section1Theme as $i => $theme) : ?>

                        <div class="carousel-item ci-1 <?php if ($i == 0) : ?> active <?php endif; ?>">

                            <?php if ($theme['TYPE'] == 1) : ?>

                                <img style="height: 100vh" <?= $i == 0 ? 'src="'. $domain : 'data-src="' . $domain ?>/nexilis/assets/img/<?= $theme['MEDIA'] ?>" loading="lazy" class="d-block w-100 carousel-photo cp-1" alt="..." onclick="openContent('<?= $theme['LINK'] ?>')">

                            <?php elseif ($theme['TYPE'] == 2) : ?>

                                <video muted loop playsinline preload="metadata" style="height: 100vh" src="<?= $domain ?>/nexilis/assets/img/<?= $theme['MEDIA'] ?>?t=1" class="d-block w-100 carousel-photo cp-1" alt="..." onclick="openContent('<?= $theme['LINK'] ?>')"></video>

                            <?php endif; ?>

                            <div class="carousel-caption text-start d-none" style="bottom: 15%; right: 0; left: 0; margin: 20px; padding-left: 10px; padding-right: 10px; border-radius: 20px; background-color:rgb(0,0,0,0.5)" onclick="openContent('<?= $theme['LINK'] ?>')">
                                <h5 style="font-size: 17px"><?= $theme['TITLE'] ?></h5>
                                <p style="font-size: 15px; padding-top: 5px"><?= $theme['DESCRIPTION'] ?></p>
                                <p style="font-size: 14px; float: right"><u><b><?= parse_url($theme['LINK'], PHP_URL_HOST); ?></b></u></p>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <div id="section-carousel-caption">

            <div id="caption" class="carousel slide carousel-sync" data-bs-ride="carousel" data-bs-interval="false">
                <div class="carousel-inner">
                    <?php foreach ($section1Theme as $i => $theme) : ?>
                        <div class="carousel-item caption-item<?php if ($i == 0) : ?> active <?php endif; ?>" onclick="openContent('<?= $theme['LINK'] ?>')">
                            <div class="caption-bg row" id="caption-<?= $i ?>">
                                <div class="caption-content text-start" onclick="openContent('<?= $theme['LINK'] ?>')">
                                    <h5 style="font-size: 17px"><?= $theme['TITLE'] ?></h5>
                                    <p style="font-size: 14.5px; padding-top: 5px"><?= $theme['DESCRIPTION'] ?></p>
                                    <p style="font-size: 14px; float: right"><u><b><?= parse_url($theme['LINK'], PHP_URL_HOST); ?></b></u></p>
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>
                </div>

            </div>

        </div>

    </div>

    <div class="container-fluid px-0">
        <!-- SECTION DIO -->
        <div id="section-two" style="height: 410px" class="p-3">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">

                <ol class="carousel-indicators ind-section-2">
                    <?php
                    foreach ($bniCarousel as $index => $bcl) {
                    ?>
                        <li data-bs-target="#myCarousel" data-bs-slide-to="<?= $index ?>" class="<?php if ($index == 0) : ?> active <?php endif; ?> me-3"></li>
                    <?php
                    }
                    ?>
                    <!-- <li data-target="#myCarousel" data-slide-to="1" class="me-3"></li>
                    <li data-target="#myCarousel" data-slide-to="2" class="me-3"></li> -->
                </ol>

                <div class="carousel-inner" style="border-radius: 10px">

                    <?php
                    $carousel_data = [];

                    // print_r($bniCarousel);
                    foreach ($bniCarousel as $index => $bcl) {
                        $date = $bcl['CREATED_AT'];
                        $time = strtotime($date);
                        $time = strtotime("+6 month", $time);

                    ?>
                        <div id="carousel_item_<?= $index ?>" class="carousel-item <?= $index == 0 ? "active" : "" ?> ">
                            <img src="../../assets/img/BNI.jpg" alt="" style="width: 50px; height: 50px; position: absolute; border-radius: 50%" class="m-4">
                            <img src="../../images/<?= $bcl['POSTING_IMAGE'] ?>" alt="Los Angeles" style="width:100%; height: 267px" onclick="newsData('<?= $bcl['ID'] ?>')">
                            <div class="p-3 bg-white" style="height: 150px">
                                <h3 class="mt-0 mb-0"><?= $bcl['DESC'] ?></h3>
                                <br>
                                <div class="row">
                                    <div class="col-1">
                                        <img src="../../assets/img/clock-icon.png" alt="" style="width: 15px; height: 15px" class="mb-1 ms-1">
                                    </div>
                                    <div class="col-11">
                                        <p class="ms-3 mb-0">Berlaku Hingga <?= date("d M Y", $time) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <!-- <div class="item">
                        <img src="../../assets/img/clock.jpeg" alt="" style="width: 50px; height: 50px; position: absolute; border-radius: 50%" class="m-4">
                        <img src="../../assets/img/idn_nature_7.webp" alt="New york" style="width:100%; height: 255px">
                        <div class="p-3 bg-white">
                            <h3 class="mt-0 mb-0">New York is Beautiful..</h3>
                            <br>
                            <div class="row">
                                <div class="col-1">
                                    <img src="../../assets/img/clock-icon.png" alt="" style="width: 15px; height: 15px" class="mb-1 ms-3">
                                </div>
                                <div class="col-11">
                                    <p class="mb-0">Berlaku Hingga 30 Sep 2022</p>
                                </div>
                            </div>
                        </div>
                    </div> -->

            </div>

        </div>
    </div>

    <div class="row p-3 mt-3">
        <div class="col-12 d-flex justify-content-center mt-4">
            <p style="color: #f54e07; font-size: 16px" class="mb-0 mt-4">Semua Promo <i class="fa fa-angle-right ms-2" style="font-size: 18px"></i></p>
        </div>
    </div>
    <!-- END OF SECTION DIO -->

    <div class="row align-items-center mt-4 pb-1 px-2">
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
    <div class="container-fluid mt-3 pb-3" id="load-more-section">
        <div class="row">
            <div class="col-12 text-center">
                <a id="to-all-news" style="text-decoration:none; color:#f54e07;" onclick="goToPage(this.id);">
                    Semua berita <i class="fa fa-angle-right ms-2" style="font-size: 18px"></i>
                </a>
            </div>
        </div>
    </div>
    </div>

    <footer>
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" id="footer-bni">
                    <div class="container-fluid mb-5">
                        <div class="row" id="footer-logo">
                            <div class="col-12">
                                <img src="../../assets/img/diginets/bni-logo.png">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                BNI terdaftar dan diawasi oleh:
                                <!-- <ul>
                                    <li><a href="http://ojk.go.id/">Otoritas Jasa Keuangan</a></li>
                                    <li><a href="http://lps.go.id/">Lembaga Penjamin Simpanan</a></li>
                                </ul> -->
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/ojk.png">
                                    </div>
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/lps.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>Kantor Pusat</strong></h6>
                                Gedung Grha BNI<br>
                                Jl. Jenderal Sudirman Kav. 1<br>
                                Jakarta Pusat 10220<br>
                                Indonesia<br><br>
                                <strong>Telepon</strong><br>
                                +62-21-2511946
                            </div>
                        </div>
                        <div class="row mt-3" id="useful-links">
                            <div class="col-12">
                                <h6><strong>Tautan Berguna</strong></h6>
                                <ul>
                                    <li><a href="https://www.bni.co.id/id-id/beranda/berita/sitemapkami">Peta Situs</a></li>
                                    <li><a href="https://www.bni.co.id/id-id/beranda/berita/securityawareness">Security Awareness</a></li>
                                    <li><a href="https://recruitment.bni.co.id/">Situs Karir</a></li>
                                    <li><a href="https://www.bni.co.id/id-id/beranda/berita/lelangagunan">Situs Lelang Agunan</a></li>
                                    <li><a href="https://www.bni.co.id/id-id/beranda/berita/lelangpengadaan">Situs Lelang Pengadaan</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <h6><strong>Hubungi Kami</strong></h6>
                                <a href="https://www.bni.co.id/id-id/kontak/kontak-bni/bnicall">BNI CALL - 1500046</a>
                            </div>
                        </div>
                        <div class="row mt-3" id="social-media">
                            <div class="col-12">
                                <h6><strong>Media Sosial</strong></h6>
                                <a href="https://twitter.com/bni">
                                    <i class="fa-brands fa-twitter-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://facebook.com/bni">
                                    <i class="fa-brands fa-facebook-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://www.linkedin.com/company/pt.-bank-negara-indonesia-persero-tbk.">
                                    <i class="fa-brands fa-linkedin" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://instagram.com/bni46">
                                    <i class="fa-brands fa-instagram-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://www.youtube.com/user/BNITVC">
                                    <i class="fa-brands fa-youtube-square" style="font-size:2.5em"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" id="footer-bri">
                    <div class="container-fluid mb-4">
                        <div class="row" id="footer-logo">
                            <div class="col-12">
                                <img src="../../assets/img/diginets/bri-logo.png">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                BRI terdaftar dan diawasi oleh:
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/ojk.png">
                                    </div>
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/lps.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>BRI Kantor Pusat</strong></h6>
                                Gedung BRI<br>
                                Jl. Jenderal Sudirman Kav.44-46<br>
                                Jakarta Pusat 10210<br>
                                Indonesia<br><br>
                            </div>
                        </div>
                        <div class="row mt-2" id="useful-links">
                            <div class="col-12">
                                <h6><strong>Tautan Berguna</strong></h6>
                                <ul>
                                    <li><a href="https://bri.co.id/web/guest/bri-products">Produk BRI</a></li>
                                    <li><a href="https://bri.co.id/web/guest/deposit-interest">Deposit Interest</a></li>
                                    <li><a href="https://bri.co.id/web/guest/kurs-detail">Rates</a></li>
                                    <li><a href="https://bri.co.id/web/guest/loan-interest-rates">Loan Interest Rates</a></li>
                                    <li><a href="https://bri.co.id/web/guest/fees-and-rates">Fees &amp; Rates</a></li>
                                    <li><a href="https://whistleblowing-system.bri.co.id/">Whistleblowing System</a></li>
                                    <li><a href="https://bri.co.id/web/erecruitment">Karier</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <h6><strong>Hubungi Kami</strong></h6>
                                <ul style="list-style:none; padding-left:0;">
                                    <li><i class="fa fa-phone"></i> 14017 / 1500017</li>
                                    <li><i class="fa fa-envelope"></i> callbri@bri.co.id</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-2" id="social-media">
                            <div class="col-12">
                                <h6><strong>Media Sosial</strong></h6>
                                <a href="https://www.facebook.com/BRIofficialpage">
                                    <i class="fa-brands fa-facebook-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://twitter.com/kontakbri">
                                    <i class="fa-brands fa-twitter-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://www.instagram.com/bankbri_id/">
                                    <i class="fa-brands fa-instagram-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://youtube.com/channel/UCRHFE_ooDrkEiRRJbog3EjA">
                                    <i class="fa-brands fa-youtube-square" style="font-size:2.5em"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" id="footer-btn">
                    <div class="container-fluid mb-3">
                        <div class="row" id="footer-logo">
                            <div class="col-12">
                                <img src="../../assets/img/diginets/btn-logo.png">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                BTN terdaftar dan diawasi oleh:
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/ojk.png">
                                    </div>
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/lps.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>BTN Kantor Pusat</strong></h6>
                                Menara Bank BTN<br>
                                Jl. Gajah Mada No. 1<br>
                                Jakarta 10130<br>
                                Indonesia<br>
                                Telp.: (021) 633 6789<br>
                                Fax: (021) 633 6719
                            </div>
                        </div>
                        <div class="row mt-4" id="useful-links">
                            <div class="col-12">
                                <h6><strong>Tautan Berguna</strong></h6>
                                <div class="row">
                                    <div class="col-6">
                                        <ul>
                                            <li>
                                                <a href="https://www.btn.co.id/id/Investor-Relation-Home">
                                                    Hubungan Investor
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.btn.co.id/id/Informasi-Publik">
                                                    Informasi Publik
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.btn.co.id/ttps://recruitment.btn.co.id/">
                                                    Karir
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.btn.co.id/id/contact-us">
                                                    Layanan Pengaduan
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.btn.co.id/id/Tentang-Kami">
                                                    Tentang BTN
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul>
                                            <li>
                                                <a href="https://cashmanagement.btn.co.id/">
                                                    BTN Cash Management
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://internetbanking.btn.co.id/">
                                                    BTN Internet Banking
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.btn.co.id/id/Syariah-Home">
                                                    BTN Syariah
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.kartusukasuka.btn.co.id/">
                                                    Kartu Suka-Suka
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.btn.co.id/id/Conventional/Informasi-yang-Anda-Butuhkan-Saat-Ini/Info/Sitemap-BTN">
                                                    Sitemap BTN
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Hubungi Kami</strong></h6>
                                <ul style="list-style:none; padding-left:0;">
                                    <li><i class="fa fa-phone"></i> 1500 286 </li>
                                    <li><i class="fa fa-envelope"></i> btncontactcenter@btn.co.id</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-2" id="social-media">
                            <div class="col-12">
                                <h6 style="margin-bottom:0"><strong>Media Sosial</strong></h6>
                                <a href="https://www.facebook.com/bankbtn/">
                                    <i class="fa-brands fa-facebook-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://www.instagram.com/bankbtn/">
                                    <i class="fa-brands fa-twitter-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://mobile.twitter.com/bankbtn">
                                    <i class="fa-brands fa-instagram-square" style="font-size:2.5em"></i>
                                </a>
                                <a href="https://www.btn.co.id/-/media/User-Defined/Social-Media/Youtube.png?h=24&w=24&la=id&hash=0499484A04EB2D1F516F6D9DCE6AE06379D2FCDA>
                                    <i class=" fa-brands fa-youtube-square" style="font-size:2.5em"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" id="footer-mandiri">
                    <div class="container-fluid mb-5" style="padding-bottom:2.1rem !important;">
                        <div class="row" id="footer-logo">
                            <div class="col-12">
                                <img src="../../assets/img/diginets/mandiri-logo.png">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                Bank Mandiri terdaftar dan diawasi oleh:
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/ojk.png">
                                    </div>
                                    <div class="col-4">
                                        <img src="../../assets/img/diginets/lps.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <h6 class="mb-3"><strong>Kantor Pusat</strong></h6>
                                Jl. Jenderal Gatot Subroto Kav. 36-38<br>
                                Jakarta 12190<br>
                                Telp: 14000, +62-21-52997777<br>
                                Fax: +62-21-52997735<br>
                                Email: mandiricare@bankmandiri.co.id<br>
                            </div>
                        </div>
                        <div class="row mt-3 d-none">
                            <div class="col-12">
                                <h6><strong>Hubungi Kami</strong></h6>
                                <ul style="list-style:none; padding-left:0;">
                                    <li><i class="fa fa-phone"></i> 1500 286 </li>
                                    <li><i class="fa fa-envelope"></i> btncontactcenter@btn.co.id</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-5" id="social-media">
                            <div class="col-12">
                                <h6 class="mb-3"><strong>Hubungi Kami</strong></h6>
                                <ul style="list-style:none; padding-left:0;">
                                    <li>
                                        <li><i class="fa fa-envelope"></i>&emsp; mandiricare@bankmandiri.co.id</li>
                                    </li>
                                    <li>
                                        <a href="https://www.facebook.com/bankmandiricare">
                                            <i class="fa-brands fa-facebook"></i>&emsp; facebook
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.twitter.com/mandiricare">
                                            <i class="fa-brands fa-twitter"></i>&emsp; twitter
                                        </a>
                                    </li>
                                    <li>
                                        <a href="http://telegram.me/bankmandiricare">
                                            <i class="fa-brands fa-telegram"></i>&emsp; telegram
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://bankmandiri.co.id/web/guest/kontak">
                                            <i class="fa-brands fa-address-book"></i>&emsp; hubungi kami
                                        </a>
                                    </li>
                                    <li><i class="fa fa-phone"></i>&emsp; mandiri call 14000 </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../../assets/js/script-homepage.js?v=<?= time() ?>"></script>

    <script>
        var f_pin = '';
        $(document).ready(function() {

            if (localStorage.lang == 0) {
                $('#hello').text("Hello");
                $('#news-update').text('NEWS UPDATE');
                $('#official-partner').text('OFFICIAL PARTNER');
                $('#load-more').text('Load more');
                $('#news-all').text('See all')
                $('#become-imi').text('Become IMI member to receive extraordinary benefits.')
                $('#insurance-title').text("Insurance");
                $('#insurance-subtitle').text("Travel and Life Insurance");
                $('#imi-partner-text').text("IMI Partner");
                $('#imi-directory-text').text("IMI Directory");
                $('#imi-benefit-text').text("KTA Benefits");
                $('#query').attr('placeholder','Search...');
            } else {
                $('#hello').text("Halo");
                $('#news-update').text('INFORMASI TERBARU');
                $('#official-partner').text('MITRA RESMI');
                $('#load-more').text('Tampilkan Lebih Banyak');
                $('#news-all').text('Lihat Semua')
                $('#become-imi').text('Jadi anggota IMI untuk dapatkan beragam benefitnya.')
                $('#insurance-title').text("Asuransi");
                $('#insurance-subtitle').text("Perjalanan dan jiwa");
                $('#imi-partner-text').text("Rekan IMI");
                $('#imi-directory-text').text("Petunjuk IMI");
                $('#imi-benefit-text').text("Benefit KTA");
                $('#query').attr('placeholder','Pencarian...');
            }

            $('body').css('visibility', 'visible');

            // FOR ANIMATE CAPTION SLIDE 1

            $('#section-carousel-caption').css('opacity', '0');

            var animA, animB;
            var timeoutA;

            timeoutA = setTimeout(function() {

                animA = $("#section-carousel-caption").animate({
                    opacity: 1,
                    bottom: '3%'
                }, 1000);

            }, 1000);

            $('.carousel-sync').on('slide.bs.carousel', function(ev) {

                var dir = ev.direction == 'right' ? 'prev' : 'next';
                $('.carousel-sync').not('.sliding').addClass('sliding').carousel(dir);

                // $('#section-carousel-caption').css('opacity','0');

                clearTimeout(timeoutA);
                animA.stop();

                animB = $("#section-carousel-caption").animate({
                    opacity: 0
                }, 300);
            });
            $('.carousel-sync').on('slid.bs.carousel', function(ev) {

                animB.stop();
                $('#section-carousel-caption').css('opacity', '0');

                $("#section-carousel-caption").css('bottom', '-10%');

                $('.carousel-sync').removeClass('sliding');

                timeoutB = setTimeout(function() {

                    animA = $("#section-carousel-caption").animate({
                        bottom: '3%',
                        opacity: 1
                    }, 1000);

                }, 1000);

            });

            $('#demo').on('slid.bs.carousel', function() {
                
                $('#demo .carousel-item video').each(function() {
                    if ($(this).closest('.carousel-item').hasClass('active')) {
                        console.log($(this).attr('src'), 'play');
                        $(this).get(0).play();
                    } else if (!$(this).closest('.carousel-item').hasClass('active')) {
                        console.log($(this).attr('src'), 'pause');
                        $(this).get(0).pause();
                    }
                })
            })

            $('.carousel.lazy-load').bind('slide.bs.carousel', function (e) {
                var image = $(e.relatedTarget).find('img[data-src]');
                image.attr('src', image.data('src'));
                image.removeAttr('data-src');
            });

            var myCarousel = document.querySelector('#demo')
            var carousel = new bootstrap.Carousel(myCarousel, {
                interval: 7000
            })

            if (window.Android) {
                f_pin = window.Android.getFPin();
            } else if (!window.Android || f_pin == '') {
                f_pin = new URLSearchParams(window.location.search).get('f_pin');
            }

            eraseQuery();

            $('form#searchFilterForm-a').submit(function (e) {
                e.preventDefault();
                return;
            })

        })

        function openContent(link) {

            window.location.href = link;

        }

        function voiceSearch() {
            if (window.Android) {
                $isVoice = window.Android.toggleVoiceSearch();
                toggleVoiceButton($isVoice);
            } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.toggleVoiceSearch) {
                window.webkit.messageHandlers.toggleVoiceSearch.postMessage({
                    param1: ""
                });
            }
        }

        function submitVoiceSearch($searchQuery) {
            // // console.log("submitVoiceSearch " + $searchQuery);
            $('#query').val($searchQuery);
            $('#delete-query').removeClass('d-none');
            // searchFilter();
        }

        function toggleVoiceButton($isActive) {
            if ($isActive) {
                $("#mic").attr("src", "../../assets/img/action_mic_blue.png");
            } else {
                $("#mic").attr("src", "../../assets/img/action_mic.png");
            }
        }

        function eraseQuery() {
            $("#delete-query").click(function() {
                $('#searchFilterForm-a input#query').val('');
                $('#delete-query').addClass('d-none');
                // searchFilter();
            })

            $('#searchFilterForm-a input#query').keyup(function() {

                // alert();

                if ($(this).val() != '') {
                    $('#delete-query').removeClass('d-none');
                } else {
                    $('#delete-query').addClass('d-none');
                }
            })
        }

        function resetSearch() {
            $('#searchFilterForm-a input#query').val('');
        }

        function newsData(id) {
            window.location.href = 'homepage-section-two?id=' + id; 
        }

        $('video').on('play', function(e) {
            $("#demo").carousel('pause');
        });
        $('video').on('stop ended', function(e) {
            $("#demo").carousel();
        });

        function pauseAll() {
            console.log('pause all');
            $('#demo').carousel('pause');
            if ($('video').parents('.active').length > 0) {
                let $activeVideo = $('video').parents('.active').find('video');
                $activeVideo.get(0).pause();
                console.log('pause video');
            }
        }

        function resumeAll() {
            console.log('play all');
            $('#demo').carousel();
            if ($('video').parents('.active').length > 0) {
                let $activeVideo = $('video').parents('.active').find('video');
                $activeVideo.get(0).play();
                console.log('play video');
            }
        }
    </script>
    <!-- END OF SCRIPT DIO -->
</body>

</html>