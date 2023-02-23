<?php

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();
    session_start();

    // GET USER FROM SESSION

    if (!isset($_SESSION['user_f_pin'])){
        $id_user = $_GET['f_pin'];
        $_SESSION['user_f_pin'] = $id_user;
    }else{
        $id_user = $_SESSION["user_f_pin"];
    }

    // CHECK USER

    if (!isset($id_user)){
        die("ID User Tidak Diset.");
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
    <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
  
    <style>
		/* FOR HTML NOT OFFSIDE */

		html,
		body {
			max-width: 100%;
			overflow-x: hidden;
            height: 100%;
		}
    </style>
</head>

<body style="display:none">
    <img src="../assets/img/lbackground_8.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

    <!-- NAVBAR -->

    <nav class="navbar navbar-light" style="background-color:<?= $nexilis_configuration['COLOR_PALETTE'] ?>; border-bottom: 1px solid #d1d5db">
        <div class="container">
            <a href="tab5.php">
                <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
            </a>
            <p class="navbar-title" data-translate="tab5notifications-1" style="color: #000000">Notifications</p>
            <div class="navbar-brand pt-2 navbar-brand-slot">
                <img src="" class="search-white-right">
            </div>
        </div>
    </nav>

    <!-- SECTION MENU HELP -->

    <div class="section-menu-2">
        <div class="container">
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/tab5/mail.png" style="width:13px">
                </div>
                <div class="col-8 col-md-8 col-lg-8">
                    <small data-translate="tab5notifications-2">Incoming Message(s)</small>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="dropdown">
                            <button class="dropdown-toggle" type="button" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false" data-translate="tab5notifications-5">
                                Default
                            </button>
                            <ul class="dropdown-menu" style=" min-width: auto !important" aria-labelledby="dropdownMenuLanguage">
                                <li><a onclick="changeSettings(0)" class="dropdown-item">Option A</a>
                                <li><a onclick="changeSettings(1)" class="dropdown-item">Option B</a></li>
                            </ul>
                        </div>
                    <span class="text-grey language-arrow">></span>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/tab5/phone.png" style="width:13px">
                </div>
                <div class="col-8 col-md-8 col-lg-8">
                    <small data-translate="tab5notifications-3">Incoming Call(s)</small>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false" data-translate="tab5notifications-5">
                            Default
                        </button>
                        <ul class="dropdown-menu" style=" min-width: auto !important" aria-labelledby="dropdownMenuLanguage">
                            <li><a onclick="changeSettings(0)" class="dropdown-item">Option A</a>
                            <li><a onclick="changeSettings(1)" class="dropdown-item">Option B</a></li>
                        </ul>
                    </div>
                    <span class="text-grey language-arrow">></span>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/tab5/vibrate.png" style="width:15px">
                </div>
                <div class="col-9 col-md-9 col-lg-9">
                    <small data-translate="tab5notifications-4">Vibrate Mode</small>
                </div>
                <div class="col-2 col-md-2 col-lg-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	//  SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();
    $('body').show();
  });

</script>
</html>