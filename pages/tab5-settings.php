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

        /* FOR OFF SWITCH BORDER */

        #flexSwitchCheckChecked{
            border: 1px solid #000000 !important;
        }
        
    </style>
</head>

<body id="main-menu">
    <img src="../assets/img/lbackground_17.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

    <script>
		if (localStorage.lang == 1) {
			document.getElementById("main-menu").style.visibility = "hidden";
		}
	</script>

    <!-- NAVBAR -->

    <nav class="navbar navbar-light" style="background-color:<?= $nexilis_configuration['COLOR_PALETTE'] ?>; border-bottom: 1px solid #d1d5db">
        <div class="container">
            <a href="tab5.php">
                <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
            </a>
            <p class="navbar-title" data-translate="tab5settings-1" style="color: #000000">Settings</p>
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
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-10 col-md-10 col-lg-10">
                    <small data-translate="tab5settings-2">Personal Information</small>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-10 col-md-10 col-lg-10">
                    <small data-translate="tab5settings-3">Contacts Syncing</small>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-10 col-md-10 col-lg-10">
                    <small data-translate="tab5settings-4">Sharing to Other Apps</small>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-10 col-md-10 col-lg-10">
                    <small data-translate="tab5settings-5">Data Usage</small>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-10 col-md-10 col-lg-10">
                    <small data-translate="tab5settings-6">Posts You`ve Liked</small>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-10 col-md-10 col-lg-10">
                    <small data-translate="tab5settings-7">Recently Deleted</small>
                </div>
            </div>
        </div>
    </div>

    <div class="section-menu-2" style="border-top: 1px solid #d1d5db">
        <div class="container">
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                </div>
                <div class="col-9 col-md-9 col-lg-9">
                    <small data-translate="tab5settings-8">Save to Gallery</small>
                </div>
                <div class="col-2 col-md-2 col-lg-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                </div>
            </div>
        </div>
        <div class="row mt-2 mb-2">
            <div class="col-1 col-md-1 col-lg-1">
                <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
            </div>
            <div class="col-9 col-md-9 col-lg-9">
                <small data-translate="tab5settings-9">Automatically Download</small>
            </div>
            <div class="col-2 col-md-2 col-lg-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                </div>
            </div>
        </div>
        <div class="row mt-2 mb-2">
            <div class="col-1 col-md-1 col-lg-1">
                <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
            </div>
            <div class="col-9 col-md-9 col-lg-9">
                <small data-translate="tab5settings-10">Auto Play Live Streaming</small>
            </div>
            <div class="col-2 col-md-2 col-lg-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                </div>
            </div>
        </div>
        <div class="row mt-2 mb-2">
            <div class="col-1 col-md-1 col-lg-1">
                <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
            </div>
            <div class="col-9 col-md-9 col-lg-9">
                <small data-translate="tab5settings-11">Email Account is not Active</small>
            </div>
            <div class="col-2 col-md-2 col-lg-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                </div>
            </div>
        </div>
    </div>

    <div class="section-menu-2" style="border-top: 1px solid #d1d5db">
        <div class="container">
            <div class="row mt-2 mb-2">
                <div class="col-1 col-md-1 col-lg-1">
                    <img class="section-menu-icon" src="../assets/img/icons/Language.png">
                </div>
                <div class="col-8 col-md-8 col-lg-8">
                    <small data-translate="tab5settings-12">Battery Optimization</small>
                </div>
                <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
                    <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false" data-translate="tab5settings-13">
                        Optimized
                    </button>
                    <ul class="dropdown-menu" style=" min-width: auto !important" aria-labelledby="dropdownMenuLanguage">
                        <li><a onclick="changeSettings(0)" class="dropdown-item">Option A</a>
                        <li><a onclick="changeSettings(1)" class="dropdown-item">Option B</a></li>
                    </ul>
                    </div>
                    <span class="text-grey language-arrow">></span>
                </div>
            </div>
        </div>
        <div class="section-menu-2" style="border-top: 1px solid #d1d5db">
            <div class="container">
                <div class="row mt-2 mb-2" onclick="openModal()">
                    <div class="col-1 col-md-1 col-lg-1">
                        <img class="section-menu-icon" src="../assets/img/icons/user-grey.png">
                    </div>
                    <div class="col-10 col-md-10 col-lg-10">
                        <small data-translate="tab5settings-16">Logout</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center" style="margin-top: 130px">
        <small class="text-grey smallest-text"><small data-translate="tab5settings-14">Version</small> 1.0.211026</small><br />
        <!-- <img src="../assets/img/icons/Q-Button-PNG.png" width="18"> -->
        <span class="small-text" data-translate="tab5settings-15">Powered by Nexilis Sports</span>
    </div>

    <!-- LOGOUT CONFIRMATION MODAL -->

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="height:120px">
            <div class="modal-body">
            <span data-translate="tab5settings-17" style="font-size: 14px">Are you sure to logout?</span>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()" style="font-size: 14px; color: #000000; border: 1px solid #000000; background-color: #FFFFFF" data-dismiss="modal" data-translate="tab5following-7">No</button>
                <button type="button" onclick="logout()" class="btn" style="font-size: 14px; color: #FFFFFF; background-color: #000000" data-translate="tab5following-6">Yes</button>
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
        // $('body').show();

        if (localStorage.lang == 1) {
			document.getElementById("main-menu").style.visibility = "visible";
        }
    });

    // FUNCTION LOGOUT

    function logout(){
        Android.logout();
    }

    function openModal(){
        $('#logoutModal').modal('show');
    }

    function closeModal(){
        $('#logoutModal').modal('hide');
    }

</script>
</html>