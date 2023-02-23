<?php

  // KONEKSI

  include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
  $dbconn = paliolite();
  session_start();
  
  // GET USER FROM SESSION

  // if (!isset($_SESSION['user_f_pin'])){
  //   $id_user = $_GET['f_pin'];
  //   $_SESSION['user_f_pin'] = $id_user;
  // }else{
  //   $id_user = $_SESSION["user_f_pin"];
  // }

  $id_user = $_REQUEST["f_pin"];

  // ID USER CHECK

  // if (!isset($id_user)) {
  //   die("ID User Tidak Diset.");
  // }

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

<body class="bg-white-background">
  <img src="../assets/img/lbackground_2.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>">
    <div class="container">
      <a onclick="window.history.back();">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5changeaddress-1">Change Delivery Address</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION SHIPPING -->

  <form action="../logics/tab5/change_address" method="POST">
    <div class="section-settings-form text-center container">
      <div class="smallest-text float-start mb-3">
        <b data-translate="tab5changeaddress-2">SHIPMENT INFORMATION</b>
      </div> 
      <input type="text" autocomplete="off" class="shop-settings-input" id="name" placeholder="Name" name="name" value="<?= $user['FIRST_NAME'] ?>">
      <input type="text" maxlength="14" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off" class="shop-settings-input" id="phone_number" placeholder="Phone Number" name="phone_number" value="<?= $user['MSISDN'] ?>">
      <textarea class="shop-settings-input-desc" id="address" placeholder="Address" name="address" rows="5"><?= $user['ADDRESS'] ?></textarea>
      <input type="hidden" name="f_pin" value="<?= $id_user ?>">
      <button style="border:none; background-color: #000000; border-radius: 10px; margin: 10px; padding: 8px; padding-left: 20px; padding-right: 20px; color: #FFFFFF; font-size: 14px" type="submit" data-translate="tab5changeaddress-3">Submit</button>
    </div>

    <!-- <div class="row text-center fixed-bottom">
      <button class="btn-live" style="border:none" type="submit" data-translate="tab5changeaddress-3">Submit</button>
    </div> -->
  </form>
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