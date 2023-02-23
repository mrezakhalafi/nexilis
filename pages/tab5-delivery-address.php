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

  // ID USER CHECK

  if (!isset($id_user)) {
    die("ID User Tidak Diset.");
  }

  // SELECT USER

  $query = $dbconn->prepare("SELECT * FROM USER_LIST LEFT JOIN USER_LIST_EXTENDED ON USER_LIST.F_PIN =
                              USER_LIST_EXTENDED.F_PIN WHERE USER_LIST.F_PIN = '$id_user'");
  $query->execute();
  $user = $query->get_result()->fetch_assoc();
  $query->close();

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
  <img src="../assets/img/lbackground_3.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>">
    <div class="container">
      <a href="tab5-main.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5deliveryaddress-1">Delivery Address</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION SHIPPING -->

  <div class="section-shipping">
    <div class="container">
      <div class="row">
        <div class="col-9 col-md-9 col-lg-9">
          <b class="small-text"><?= $user['FIRST_NAME'] ?></b>

          <!-- GET ADDRESS & PHONE NUMBER FROM DATABASE -->
          
          <?php if (isset($user['ADDRESS'])): ?>
            <div class="small-text text-grey">
              <?= $user['ADDRESS'] ?>
            </div>
          <?php else: ?>
            <div class="small-text text-grey">
              Belum Diatur / Not Set
            </div>
          <?php endif; ?>

          <?php if (isset($user['MSISDN'])): ?>
            <div class="small-text text-grey">
              <?= $user['MSISDN'] ?>
            </div>
          <?php else: ?>
            <div class="small-text text-grey">
              Belum Diatur / Not Set
            </div>
          <?php endif; ?>

        </div>
        <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
          <a href="tab5-change-address.php">
            <p class="small-text text-orange" data-translate="tab5deliveryaddress-2">Change</p>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="row text-center fixed-bottom">
    <button class="btn-live" style="border:none" type="submit" data-translate="tab5deliveryaddress-3">Add Address</button>
  </div> -->

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