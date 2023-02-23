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
// FOR PADDING DIV BASIC ACCOUNT

// get categories
$query = $dbconn->prepare("SELECT * FROM CATEGORY WHERE EDUCATIONAL = 8 ORDER BY ID DESC");
$query->execute();
$categoryResult = $query->get_result();
$query->close();

$categoryList = array();
while ($category = $categoryResult->fetch_assoc()) {
    $categoryList[] = $category;
}

// get categories

$id = $_GET['id'];

$queryCarousel = $dbconn->prepare("SELECT * FROM BNI_CAROUSEL LEFT JOIN USER_LIST ON BNI_CAROUSEL.F_PIN = USER_LIST.F_PIN WHERE BNI_CAROUSEL.ID = '$id'");
$queryCarousel->execute();
$bniCarousel = $queryCarousel->get_result()->fetch_assoc();
$queryCarousel->close();

$created_date = $bniCarousel['CREATED_AT'];
$time = strtotime($created_date);
$time = strtotime("+6 month", $time);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gaspol Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
            position:relative;
            padding:0;
        }

        #list-category.nav .nav-link.active {
            color: #005E6A;
            text-decoration: none;
            background-color: transparent;
        }

        #list-category.nav .nav-link.active:before {
            width:32px;
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

        #demo{
            position: absolute;
            z-index: -1;
        }

        #demo-2{
            position: absolute;
            z-index: 8888;
            margin-top: -650px;
            width: 90%;
        }

        #curs_section{
            position: absolute;
            z-index: 8888;
            margin-top: -430px;
            width: auto;
            height: 200px;
            border-radius: 20px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            width: 90%;
        }

        #frontpage{
            padding: 50px;
            opacity: 0.4;
            background-color: #000000;
            height: 100%;
        }

        #frontpage-2{
            position: absolute;
            padding: 50px;
            opacity: 0.4;
            background-color: #000000;
            height: 203px;
            border-radius: 20px;
            width: 100%;
        }

        .ci-1{
            height: 1000px;
        }

        .cp-1{
            object-fit: cover;
            object-position: center;
            width: 100%;
        }

        .ci-2{
            width: 100%;
        }

        .cp-2{
            object-fit: cover;
            object-position: center;
            border-radius: 20px;
            width: 100%;
        }

        .navbar-toggler{
            border: none;
        }

        .navbar-toggler-icon {
            background-image: url('https://www.rrcc.edu/sites/all/themes/rrcc/images/hamburger-icon.png') !important;
            width: 23px !important;
        }

        .carousel-indicators [data-bs-target] {
            height: 10px;
            width: 10px;
            border: 1px solid transparent;
            border-radius: 1000px;
            opacity: 1;
            background-color: rgb(220,220,220,0.5);
        }

        .carousel-indicators > .active {
            width: 50px;
            opacity: 0.5;
            opacity: 1;
        }

        .navbar-collapse {
            background-color: rgb(0,0,0,0.5);
            text-align: center;
        }

        .nav-link{
            color: white;
        }

        .carousel-indicators {
            bottom: -60px;
        }

        .carousel-indicators li {
            background-color: white;
            border: 1px solid #f4540b;
        }

        .carousel-indicators .active {
            background-color: #f4540b;
            border: 1px solid #f4540b;
            /* width: unset; */
        }

        .carousel-indicators > .active {
            width: 12px;
            opacity: 0.5;
            opacity: 1;
        }

    </style>

</head>

<body style="visibility:hidden">

    <div class="container-fluid p-0">
        <div class="row p-3">
            <div class="col-12">
                <h1 class="mb-0 ms-4" style="font-size: 24px">BNI - <?= $bniCarousel['DESC'] ?></h1>
                <p class="mb-0 ms-4" style="margin-top: 20px">Hingga <?= date('d M Y', $time) ?></p>
            </div>
        </div>
        <img src="../../images/<?= $bniCarousel['POSTING_IMAGE'] ?>" alt="" style="height: 148px; width: 100%; margin-top: 25px">
        <div class="row p-3">
            <div class="col-12" style="margin-top: 25px">
                <h3 style="font-size: 24px; font-weight: 400; color: #474747" class="ms-4 mb-0">Bagi Pengguna</h3>
            </div>
            <div class="col-12" style="margin-top: 25px">
                <img class="ms-4" src="../../assets/img/cc-icon.png" alt="" style="width: 15px; height: 15px"><span style="font-size: 16px; color: #f4540b" class="ms-3">Kartu Kredit</span>
                &nbsp;
                <img class="ms-4" src="../../assets/img/dc-icon.png" alt="" style="width: 15px; height: 15px"><span style="font-size: 16px; color: #f4540b" class="ms-3">Kartu Debit</span>
                &nbsp;
                <img class="ms-4" src="../../assets/img/mbanking-icon.png" alt="" style="width: 15px; height: 15px"><span style="font-size: 16px; color: #f4540b" class="ms-3">BCA Mobile</span>
            </div>
        </div>
        <div class="row p-3">
            <div class="col-12" style="margin-top: 25px">
                <h1 class="ms-4 mb-0">Deskripsi Produk:</h1>
                <p class="ms-4 mb-0" style="margin-top: 20px; color: #474747; font-weight: 700; font-size: 16px"><?= $bniCarousel['DESC'] ?></p>
            </div>
        </div>
        <div class="row p-3">
            <div class="col-12" style="margin-top: 25px">
                <h1 class="ms-4 mb-0">Bagikan Promo Ini</h1>
                <img class="ms-5" src="../../assets/img/hyperlink-icon.png" alt="" style="width: 20px; height: 20px; margin-top: 25px">
                <img class="ms-4" src="../../assets/img/twitter-icon.png" alt="" style="width: 20px; height: 20px; margin-top: 25px">
                <img class="ms-4" src="../../assets/img/facebook-icon.png" alt="" style="width: 20px; height: 20px; margin-top: 25px">
                <img class="ms-4" src="../../assets/img/whatsapp-icon.png" alt="" style="width: 20px; height: 20px; margin-top: 25px">
                <img class="ms-4" src="../../assets/img/line-icon.png" alt="" style="width: 20px; height: 20px; margin-top: 25px">
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../assets/js/script-homepage.js?v=<?= time() ?>"></script>

    <script>
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
            }

            $('body').css('visibility', 'visible')

        })

        $('#progress-bar-0').animate({
            width: '100%',
        }, 5000);

        $('#demo-2').on('slide.bs.carousel', function(e){
           var position = e.to;
           var old = e.from;

           console.log(old);

           $('#progress-bar-'+old).stop();
           $('#progress-bar-'+old).css('width','0%');

           $('#progress-bar-'+position).animate({
                width: '100%',
            }, 5000);

        });

        function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
		}

        var firstCursBeli = 10201207;
        var firstCursJual = 10010229;

        startCounterBeli(firstCursBeli);
        startCounterJual(firstCursJual);

        $('#demo-3').on('slide.bs.carousel', function(e){

           var position = e.to;
           var arrayBeli = [10201207,3560000,8340000];
           var arrayJual = [10010229,3180000,8130000];

           console.log(position);

           startCounterBeli(arrayBeli[position]);
           startCounterJual(arrayJual[position]);

        });

        function startCounterBeli(number) {
            const maxCounter = number;
            $({
                Counter: 0
            }).animate({
                Counter: maxCounter
            }, {
                duration: 500,
                easing: 'swing',
                step: function() {
                    $('#beli-kurs').text(formatRupiah(Math.ceil(this.Counter).toString(),''));
                }
            });
        }

        function startCounterJual(number) {
            const maxCounter = number;
            $({
                Counter: 0
            }).animate({
                Counter: maxCounter
            }, {
                duration: 500,
                easing: 'swing',
                step: function() {
                    $('#jual-kurs').text(formatRupiah(Math.ceil(this.Counter).toString(),''));
                }
            });
        }

        $('#mobile-menu').hide();

        $('.navbar-toggler').click(function(){
            $('#mobile-menu').slideToggle();
            $('#magnifier').toggle();
        })
        
    </script>

</body>

</html>