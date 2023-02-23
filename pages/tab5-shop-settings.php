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

	// SELECT SHOP MANAGER DATA
	
	$query = $dbconn->prepare("SELECT * FROM SHOP WHERE CODE = '$id_shop'");
	$query->execute();
	$shop_data = $query->get_result()->fetch_assoc();
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <title>Nexilis Sports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

  <style>

  /* FOR DISSAPEAR BORDER IN EMPTY IMAGE */

  img[src=""] { 
    display: none; 
  }

  </style>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_4.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5shopsettings-1">Shop Settings</p>
      <div class="navbar-brand pt-2" class="navbar-brand-slot">
        <img src="" class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION SHOP SETTINGS IMAGE -->

  <form action="../logics/tab5/shop_settings" method="POST" enctype="multipart/form-data">
    <div class="section-shop-settings-banner">

      <?php if ($shop_data['BANNER_ID']): ?>
        
        <div class="single-upload-banner">
          <div class="cover-upload">
            <label for="new-file-cover">
              <div class="d-flex justify-content-center">
                <img src="../images/<?= $shop_data['BANNER_ID'] ?>" id="image-preview-2" class="shop-banner" style="margin-top: -68px" >
              </div>
            </label>
            <input id="new-file-cover" type="file" name="shop_banner" onchange="loadFile2(event)"/>
          </div>
        </div>

        <?php else: ?>

          <div class="single-upload-banner">
            <div class="cover-upload">
              <label for="file-cover">
                <p class="small-text text-grey text-center shop-banner-text" data-translate="tab5shopsettings-2">Shop Banner</p>
                <img src="../assets/img/tab5/Add-(Grey).png" class="upload-settings-add-small">
              </label>
              <input id="file-cover" type="file" name="shop_banner" onchange="loadFile2(event)"/>
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <img src="" class="shop-banner" id="image-preview-2" style="border: none ; outline: none; margin-top: -85px">
          </div>

        <?php endif; ?>

    </div>

    <div class="section-shop-settings-image">
      <div class="single-upload-cover">
        <div class="image-upload">
          <label for="file-input">
            <img src="../images/<?= $shop_data['THUMB_ID'] ?>" class="shop-settings-image" id="image-preview">
          </label>
          <input id="file-input" type="file" name="shop_thumbnail" onchange="loadFile(event)"/>
        </div>
      </div>
    </div>

    <!-- SECTION SHOP SETTINGS FORM -->

    <div class="section-settings-form text-center container">
      <div class="smallest-text float-start mb-3">
        <b data-translate="tab5shopsettings-3">INFO & APPEARANCE</b>
      </div> 
      <input type="text" autocomplete="off" class="shop-settings-input" id="shop_title" placeholder="Store Name" name="name" value="<?= $shop_data['NAME'] ?>">
      <textarea class="shop-settings-input-desc" id="shop_desc" placeholder="Store Description" name="description" rows="5"><?= $shop_data['DESCRIPTION'] ?></textarea>
      <input type="text" autocomplete="off" class="shop-settings-input" placeholder="Website" name="website" value="<?= $shop_data['LINK'] ?>">
      <input type="hidden" name="code" value="<?= $shop_data['CODE'] ?>">
      <input type="hidden" name="old_image" value="<?= $shop_data['THUMB_ID'] ?>">
      <input type="hidden" name="old_banner" value="<?= $shop_data['BANNER_ID'] ?>">
    </div>

    <div id="btn-place">
      <div class="row text-center fixed-bottom">
        <button class="btn-save-changes-settings" style="border:none" type="submit" data-translate="tab5shopsettings-4">Save Changes</button>
      </div>
    </div>
  </form>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

    if ((localStorage.lang) == 1){
      $('#shop_title').attr('placeholder', 'Nama Toko');
      $('#shop_desc').attr('placeholder', 'Deskripsi Toko');
    }

    $('body').show();
  });

  // CHANGE IMAGE AS CHOOSE

  var loadFile = function(event){
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('image-preview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

  var loadFile2 = function(event){
    var reader = new FileReader();
      reader.onload = function(){
      var output = document.getElementById('image-preview-2');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

  // PREVENT FROM KEYBOARD GOING UP

  $('input').focus(function(){
    $('#btn-place').removeClass('fixedBottom');
  });

  $('input').focusout(function(){
    $('#btn-place').addClass('fixedBottom');
  });

  $('textarea').focus(function(){
    $('#btn-place').removeClass('fixedBottom');
  });

  $('textarea').focusout(function(){
    $('#btn-place').addClass('fixedBottom');
  });

</script>
</html>