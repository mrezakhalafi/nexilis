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
$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 10 ORDER BY ID DESC");
$query->execute();
$categoryResult = $query->get_result();
$query->close();

$categoryList = array();
while ($category = $categoryResult->fetch_assoc()) {
    $categoryList[] = $category;
}

$query = $dbconn->prepare("SELECT * FROM DIGINETS_THEME WHERE BE = 309");
$query->execute();
$section1Theme = $query->get_result();
$query->close();

// get categories
$sql = "SELECT ul.IMAGE, bc.*
FROM BNI_CAROUSEL bc 
LEFT JOIN USER_LIST ul ON ul.F_PIN = bc.F_PIN WHERE bc.BE = 309";

// $sql="SELECT * FROM USER_LIST where F_PIN = '$f_pin'";
$queryCarousel = $dbconn->prepare($sql);
$queryCarousel->execute();
$bniCarousel = $queryCarousel->get_result();
$queryCarousel->close();

// $bniCarousel = array();


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
            background-color: #252525;
            font-size: .8em;
            color: #ccc;
        }

        footer a {
            color: #ccc;
            text-decoration: none;
        }

        footer #footer-logo img {
            width: 100px;
            height: auto;
        }

        #social-media i * {
            font-size: 2.5 em;
        }

        #section-carousel-caption {
            bottom: 3%;
            right: 0;
            left: 0;
            margin: 20px;
            position: absolute;
        }

        #section-carousel-caption .carousel-inner {
            border-radius: 20px;
        }

        #section-carousel-caption .carousel-item {

            border-radius: 20px;
            background-color: rgb(0, 0, 0, 0.5);
            padding: 15px;
        }

        .carousel-item-prev {
            border-radius: 20px;
        }

        .carousel-item-next {
            border-radius: 20px;
        }

        .carousel-item-start {
            border-radius: 20px;
        }

        .carousel-item-end {
            border-radius: 20px;
        }

        table#kontak td {
            vertical-align: baseline;
        }
    </style>

</head>

<body style="visibility:hidden">

    <div class="container-fluid px-0">

        <div id="section-one" style="height: 100vh; position:relative;">

            <div id="demo" class="carousel slide carousel-sync" data-bs-ride="carousel" data-bs-interval="false">

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

                                <img style="height: 100vh" src="../../assets/img/<?= $theme['MEDIA'] ?>" class="d-block w-100 carousel-photo cp-1" alt="..." onclick="openContent('<?= $theme['LINK'] ?>')">

                            <?php elseif ($theme['TYPE'] == 2) : ?>

                                <video playsinline autoplay muted loop style="height: 100vh" src="../../assets/img/<?= $theme['MEDIA'] ?>" class="d-block w-100 carousel-photo cp-1" alt="..." onclick="openContent('<?= $theme['LINK'] ?>')"></video>

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
                        <div class="carousel-item <?php if ($i == 0) : ?> active <?php endif; ?>" onclick="openContent('<?= $theme['LINK'] ?>')">
                            <div class="caption-content text-start" onclick="openContent('<?= $theme['LINK'] ?>')">
                                <h5 style="font-size: 17px"><?= $theme['TITLE'] ?></h5>
                                <p style="font-size: 14.5px; padding-top: 5px"><?= $theme['DESCRIPTION'] ?></p>
                                <p style="font-size: 14px; float: right"><u><b><?= parse_url($theme['LINK'], PHP_URL_HOST); ?></b></u></p>
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
                            <img src="../../assets/img/app_icon.png" alt="" style="width: 50px; height: 50px; position: absolute; border-radius: 50%" class="m-4">
                            <img src="../../images/<?= $bcl['POSTING_IMAGE'] ?>" alt="Los Angeles" style="width:100%; height: 267px" onclick="newsData('<?= $bcl['ID'] ?>')">
                            <div class="p-3 bg-white" style="height: 150px">
                                <h3 class="mt-0 mb-0" style="font-size: 20px"><?= $bcl['DESC'] ?></h3>
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
        <div class="container-fluid py-5">
            <div class="row mt-3">
                <div class="col-12">
                    <h6><strong>TNI ANGKATAN DARAT</strong></h6>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <table id="kontak">
                        <tbody>
                            <tr>
                                <td><i class="fa fa-map-marker" aria-hidden="true"></i></td>
                                <td style="padding-left: 5px;"><b>Dinas Penerangan Angkatan Darat</b><br>Jl. Veteran No. 5<br>Jakarta Pusat 10110, Indonesia</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-phone" aria-hidden="true"></i></td>
                                <td style="padding-left: 5px;">+62-21-38998080</td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-envelope" aria-hidden="true"></i></td>
                                <td style="padding-left: 5px;"><a href="https://tniad.mil.id/kontak/">Hubungi Kami</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-3" id="social-media">
                <div class="col-12">
                    <h6><strong>Media Sosial</strong></h6>
                    <a href="https://twitter.com/tni_ad">
                        <i class="fa-brands fa-twitter-square" style="font-size:2.5em"></i>
                    </a>
                    <a href="https://www.facebook.com/TNIAngkatanDarat/">
                        <i class="fa-brands fa-facebook-square" style="font-size:2.5em"></i>
                    </a>
                    <!-- <a href="">
                        <i class="fa-brands fa-linkedin" style="font-size:2.5em"></i>
                    </a> -->
                    <a href="https://www.instagram.com/tni_angkatan_darat/">
                        <i class="fa-brands fa-instagram-square" style="font-size:2.5em"></i>
                    </a>
                    <a href="https://www.youtube.com/channel/UCkGqMrkCgjHt72GUTXzoySA">
                        <i class="fa-brands fa-youtube-square" style="font-size:2.5em"></i>
                    </a>
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

            $('body').css('visibility', 'visible')

            $('.carousel-sync').on('slide.bs.carousel', function(ev) {
                // get the direction, based on the event which occurs
                var dir = ev.direction == 'right' ? 'prev' : 'next';
                // get synchronized non-sliding carousels, and make'em sliding
                $('.carousel-sync').not('.sliding').addClass('sliding').carousel(dir);
            });
            $('.carousel-sync').on('slid.bs.carousel', function(ev) {
                // remove .sliding class, to allow the next move
                $('.carousel-sync').removeClass('sliding');
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
            $('#demo').carousel('pause');
            if ($('video').parents('.active').length > 0) {
                let $activeVideo = $('video').parents('.active').find('video');
                $activeVideo.get(0).pause();
            }
        }

        function resumeAll() {
            $('#demo').carousel();
            if ($('video').parents('.active').length > 0) {
                let $activeVideo = $('video').parents('.active').find('video');
                $activeVideo.get(0).play();
            }
        }
    </script>
    <!-- END OF SCRIPT DIO -->
</body>

</html>