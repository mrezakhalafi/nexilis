<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

	// ID SHOP GET

	if (isset($_GET['id'])){
		$id_shop = $_GET['id'];
	}else if(isset($_SESSION['id_shop'])){
		$id_shop = $_SESSION["id_shop"];
	}else if(isset($_COOKIE['id_shop'])){
		$id_shop = $_COOKIE['id_shop'];
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <title>Nexilis Sports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

  <style>

  /* FOR RED DOT FORM */

  .form-group{
    position: relative;
    margin-left:-10px;
  }

  .form-control:focus {
    background-color: #FAFAFF;
    box-shadow: none;
  }

  .palceholder{
    position: absolute;
    color: #8a898b;
    display: none;
  }

  .star{
    color: red;
    padding-left: -10px;
    position: absolute;
  }

  .red-dot{
    color: red;
    position: absolute;
    margin-top:-10px;
    margin-left: 50px;
    font-size: 10px;
  }

  .red-dot-2{
    color: red;
    position: absolute;
    margin-top:11px;
    margin-left: 61px;
    font-size: 10px;
    z-index: 9999;
  }

  .red-dot-3{
    color: red;
    position: absolute;
    margin-top:11px;
    margin-left: 71px;
    font-size: 10px;
    z-index: 9999;
  }

  </style>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_1.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-ads.php">
        <span class="small-text" data-translate="tab5createanad-1">Cancel</span>
      </a>
      <p class="navbar-title-2" data-translate="tab5createanad-2">Create an Ad</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION CREATE AD PHOTO VIDEO -->

  <div class="section-create-ad-photo">
    <div class="row container gx-0 create-an-ad-title">
      <p class="small-text"><b data-translate="tab5createanad-3">Photo / Video</b></p>
    </div>
    <div class="row small-text gx-0" style="background-color: #FAFAFF;">
      <div class="container">
        <ul class="nav nav-tabs horizontal-slide gx-0">

        <!-- LOOPING IMAGE SLOT -->

        <?php for ($i=1; $i<=10; $i++){ ?>

          <li class="nav-item">
            <div class="upload-listing-image-slot d-flex justify-content-center">
              <div class="single-upload-cover-listing">
                <div class="image-upload">
                  <label for="file-input-<?= $i ?>">
                    <img src="../assets/img/tab5/Dashed-Image.png" id="image-preview-<?= $i ?>" class="upload-ads-border">
                    <img src="../assets/img/tab5/Add-(Grey).png" class="upload-ads-add" id="upload-ads-add-<?= $i ?>">
                  </label>
                  <input id="file-input-<?= $i ?>" type="file" name="ads_thumbnail-<?= $i ?>" onchange="loadFile(event, <?= $i ?>)" />
                </div>
              </div>
            </div>
          </li>

          <?php } ?>

        </ul>
      </div>
    </div>
  </div>

  <!-- SECTION CREATE AD FORM -->

  <div class="section-upload-ads-desc">
    <div class="row gx-0 upload-ads">
      <div class="form-group">
        <div class="palceholder live-stream-title-input">
            <label for="title" data-translate="tab5createanad-4">Campaign Title</label>
            <span class="star">*</span>
        </div>
        <input type="text" id="title" class="form-control upload-ads-input" required>
      </div>
    </div>
    <div class="row gx-0 upload-ads">
      <div class="form-group">
        <div class="palceholder live-stream-title-input">
          <label for="title" data-translate="tab5createanad-5">Target URL</label>
          <span class="star">*</span>
        </div>
        <input type="text" id="title" class="form-control upload-ads-input" required>
      </div>
    </div>
    <div class="row gx-0 upload-ads">
      <div class="form-group">
        <div class="row">
          <div class="col-10 col-md-10 col-lg-11">
            <div class="palceholder live-stream-title-input">
              <label for="title" data-translate="tab5createanad-6">Budget</label>
              <span class="star">*</span>
            </div>
            <input type="text" id="title" class="form-control upload-ads-input" required>
          </div>
          <div class="col-2 col-md-2 col-lg-1">
            <p class="small-text text-grey create-ad-daily" data-translate="tab5createanad-7">/daily</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row gx-0 upload-ads-2">
      <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-left create-ad-calendar-start">
        <input type="text" class="datepicker tbl-calendar-date text-grey create-ad-calendar-width" readonly placeholder="Start Date">
        <div class="red-dot-2">*</div>
        <img src="../assets/img/tab5/Calendar.png" class="calendar-icon-ad-left">
      </div>
      <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-right create-ad-calendar-end">
        <input type="text" class="datepicker tbl-calendar-date-2 text-grey create-ad-calendar-width" readonly placeholder="Ending Date">
        <div class="red-dot-3">*</div>       
        <img src="../assets/img/tab5/Calendar.png" class="calendar-icon-ad-right">  
      </div>
    </div>
    <div class="row gx-0 upload-ads">
      <div class="form-group">
        <div class="palceholder live-stream-title-input" id="weight">
          <label for="weight" data-translate="tab5createanad-8">Audience</label>
          <span class="star">*</span>
        </div>
        <input type="text" class="form-control upload-ads-input" required>
      </div>
    </div>
  </div>

  <div class="row text-center fixed-bottom">
    <a href="tab5-ad-review.php">
      <div class="btn-confirm-withdrawal" data-translate="tab5createanad-9">Continue</div>
    </a>
  </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

    changeLanguage();

    if (localStorage.lang == 1){
      $('.tbl-calendar-date').attr('placeholder','Tanggal Mulai');
      $('.tbl-calendar-date-2').attr('placeholder','Tanggal Berakhir');
      $('.red-dot-2').css('margin-left','83px');
      $('.red-dot-3').css('margin-left','96px');
    }

    $('body').show();
	});
  
  // DATEPICKER

  $('.datepicker').datepicker({
    format: "dd MM yyyy",
    autoclose: true,
  });

  function show_datepicker(){
    $(".datepicker").datepicker('show');
  }

  // SCRIPT RED DOT INPUT

  $('.palceholder').click(function(){
    $(this).siblings('textarea').focus();
  });

  $('.form-control').focus(function(){
    $(this).siblings('.palceholder').hide();
  });

  $('.form-control').blur(function(){

    var $this = $(this);
    if ($this.val().length == 0)
      $(this).siblings('.palceholder').show();
    });

  $('.form-control').blur();

  // SCRIPT RED DOT SELECT

  $( ".tbl-calendar-date").change(function(){
    $(".red-dot-2").hide();
  });

  $( ".tbl-calendar-date-2").change(function(){
    $(".red-dot-3").hide();
  });

  // DISABLE PLUS INSTEAD FIRST ONE

  for (var i=2; i<=10; i++){
    $('#upload-ads-add-'+i).hide();
    $('#file-input-'+i).prop("type", "text");
  }

  // CHANGE IMAGE AS CHOOSE

  var loadFile = function(event, number){
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('image-preview-'+number);
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);

    // SHOW PLUS AFTER BEFORE IMAGE ALREADY SELECTED

    $('#upload-ads-add-'+number).hide();
    $('#upload-ads-add-'+(number+1)).show();
    $('#file-input-'+(number+1)).prop("type", "file");
    $('.num_images').val(number);
  };

</script>
</html>