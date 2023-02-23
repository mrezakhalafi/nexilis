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

<body class="bg-white-background">
  <img src="../assets/img/lbackground_3.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5finance-1">Finances</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img src="" class="navbar-img-slot">
      </div>
    </div>
    </div>
  </nav>

  <!-- SECTION QIOSK BALANCE -->

  <div class="section-qiosk-balance">
    <div class="container">
      <div class="finance-title">
        <p class="small-text" data-translate="tab5finance-2">Qiosk Balance</p>
      </div>
      <div class="row available-deposit">
        <p class="small-text" data-translate="tab5finance-3">Available For Deposit</p>
        <b class="deposit-value">Rp 10,565,000</b>
      </div>
    </div>
    <div class="finance-title-2">
      <div class="container">
      <p class="small-text" data-translate="tab5finance-4">Transfer to Your Bank</p>
      </div>
    </div>
  </div>

  <!-- SECTION TRANSFER AMOUNT -->

  <div class="section-transfer-amount">
    <div class="container">
      <div class="row transfer-square shadow-sm">
        <div class="finance-title-3">
          <p class="small-text text-grey" data-translate="tab5finance-5">Transfer Amount</p>
        </div>
        <p class="transfer-value">
          <span class="text-grey">Rp </span><b>4,650,000</b>
        </p>
        <p class="small-text text-grey" data-translate="tab5finance-6">To</p>
        <div class="row gx-0 select-bank">
          <div class="col-3 col-md-3 col-lg-2">
            <img src="../assets/img/tab5/bca.png" class="bank-icon">
          </div>
          <div class="col-7 col-md-7 col-lg-8" class="bank-desc">
            <div class="bank-text">Bank Central Asia(BCA)</div>
            <div class="bank-text">*6190</div>
          </div>
          <div class="col-2 col-md-2 col-lg-2 d-flex justify-content-end">
            <p class="small-text text-orange" data-translate="tab5finance-7">Change</p>
          </div>
        </div>
        <div class="withdrawal-fee">
        <div class="row">
          <div class="col-10 col-md-10 col-lg-10">
            <p class="small-text text-grey" data-translate="tab5finance-8">Withdrawal Fee</p>
          </div>
          <div class="col-2 col-md-2 col-lg-2 d-flex justify-content-end">
            <p class="small-text" data-translate="tab5finance-9">Free</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row text-center fixed-bottom">
    <a href="tab5-confirm-withdrawal.php">
      <div class="btn-confirm-withdrawal" data-translate="tab5finance-10">Confirm Withdrawal</div>
    </a>
  </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();
	});

</script>
</html>