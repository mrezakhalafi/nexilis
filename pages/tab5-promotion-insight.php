<?php

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();
    session_start();

    // ID SHOP GET

    if (!isset($_SESSION['id_shop'])){
        $id_shop = $_GET['id'];
        $_SESSION['id_shop'] = $id_shop;
    }else{
        $id_shop = $_SESSION["id_shop"];
    }

    // CHECK SHOP ID

    if (!isset($id_shop)) {
        die("ID Shop Tidak Diset.");
    }

    // SELECT COLOR CONFIGURATION

    $query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
    $query->execute();
    $nexilis_configuration = $query->get_result()->fetch_assoc();
    $query->close();

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Nexilis Sports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">
    <img src="../assets/img/lbackground_1.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

    <!-- NAVBAR -->

    <nav class="navbar navbar-light navbar-shop-manager">
        <div class="container">
            <a href="tab5-ads.php">
                <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
            </a>
            <div class="navbar-title-2" data-translate="tab5promotioninsight-1">Promotion Insights</div>
            <div class="navbar-brand pt-2 navbar-brand-slot">
                <img src="" class="navbar-img-slot">
            </div>
        </div>
    </nav>

    <!-- SECTION INTERACTION -->

    <div class="insight-section promotion-text">
        <div class="container">
            <div class="row">
                <div class="insight-title">
                    <div data-translate="tab5promotioninsight-2">Interactions</div>
                </div>
            </div>
        </div>
    <div class="insight-desc">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="single-row-insight" data-translate="tab5promotioninsight-3">Likes</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                    <div class="single-row-insight">1,546</div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="single-row-insight" data-translate="tab5promotioninsight-4">Comments</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                    <div class="single-row-insight">300</div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="single-row-insight" data-translate="tab5promotioninsight-5">Profile Visits</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                    <div class="single-row-insight">537</div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="single-row-insight" data-translate="tab5promotioninsight-6">Website Visits</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                    <div class="single-row-insight">525</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION DISCOVERY -->

    <div class="insight-section promotion-text">
        <div class="container">
            <div class="row">
                <div class="insight-title">
                    <div data-translate="tab5promotioninsight-7">Discovery</div>
                </div>
            </div>
        </div>
    </div>
    <div class="insight-desc">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="single-row-insight" data-translate="tab5promotioninsight-8">Impressions</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                    <div class="single-row-insight">4,600</div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="single-row-insight" data-translate="tab5promotioninsight-9">Follows</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                    <div class="single-row-insight">240</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION PROMOTION -->

    <div class="insight-section-2 promotion-text">
        <div class="container">
            <div class="row">
                <div class="insight-title">
                    <div data-translate="tab5promotioninsight-10">Promotion</div>
                </div>
            </div>
        </div>
        <div class="insight-desc-2">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="single-row-insight" data-translate="tab5promotioninsight-11">Budget</div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                        <div class="single-row-insight">Rp 4,500,000</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="single-row-insight" data-translate="tab5promotioninsight-12">Spend</div>
                        <div class="text-grey smallest-text">77.78% <span data-translate="tab5promotioninsight-13">of your budget</span></div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
                        <div class="single-row-insight">Rp 3,500,000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center fixed-bottom">
        <a href="tab5-create-an-ad.php">
            <div class="btn-app-notification" data-translate="tab5promotioninsight-14">Promote Again</div>
        </a>
    </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();
        $('body').show();
	});
  
</script>
</html>