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

  $caption = $_POST['caption'];
  $location = $_POST['location'];
  $hashtag = $_POST['hashtag'];
  $tagged_product = $_POST['tagged_product'];
  $tagged_input = $_POST['tagged_input'];
  $post_check = $_POST['post_check'];
  $is_paid = $_POST['is_paid'];
  $price = $_POST['price'];

  $category = $_POST['category'];
  $category_name = $_POST['category_name'];

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
  <img src="../assets/img/lbackground_7.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-new-post">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5newpost-1" style="margin-left: 30px">New Post</p>
        <div class="navbar-brand pt-2 navbar-brand-slot">
          <button class="text-purple navbar-new-post-next" onclick="sendData()" data-translate="tab5newpost-2">Next</button>
        </div>
    </div>
  </nav>

  <!-- SECTION NEW POST IMAGE -->

  <div class="section-new-post">
    <div class="row gx-0">
      <img src="../assets/img/tab5/Shop Manager/noimage-large.jpg" id="image-preview" class="new-post-image">
      <!-- <img src="../assets/img/tab5/Tagged-Product.png" class="tagged-product-icon"> -->
      <!-- <img src="../assets/img/tab5/Tag-Product.png" class="tagged-product-text-image">
      <p class="tagged-product-text">Kayu Jati</p> -->
    </div>  
  </div>

  <!-- SECTION NEW POST FORM -->

  <div class="section-post-description">
    <div class="row gx-0 preview-post-desc">
      <p class="small-text"><?= $caption ?></p>
    </div>
    <div class="row gx-0 add-post">
      <div class="col-1 col-md-1 col-lg-1">
        <img src="../assets/img/tab5/Tag.png" class="tag-icon">
      </div>
      <div class="col-11 col-md-11 col-lg-11 preview-post-tagged">
        <span class="small-text">
          <?php if ($tagged_input != ""): ?>
            <?= $tagged_input ?>
          <?php else: ?>
            <span data-translate="tab5newpost-8">No product tagged.</span>
          <?php endif; ?>
        </span>   
      </div>
    </div>
    <div class="row gx-0 add-post">

      <!-- LOOPING HASHTAG FROM PREVIOUS FORM -->

      <?php $new_hashtag = explode(' ', $hashtag); ?>

      <?php foreach($new_hashtag as $hash): ?>
        <span class="preview-post-hashtag text-center"><?= $hash ?></span>
      <?php endforeach; ?>
      
    </div>
    <div class="row gx-0 add-post" style="padding-top: 10px; padding-bottom: 0px !important">
      <p class="small-text"><?= $location ?></p>
    </div>
    <div class="row gx-0 preview-post">
      <div class="col-6 col-md-6 col-lg-6">
        <div class="form-check">
          <input disabled class="form-check-input" style="border:1px solid #6c6f74 !important" type="checkbox" value="" id="flexCheckDefault">
          <label class="form-check-label add-post-input" style="padding-top: 0px" for="flexCheckDefault" id="paid-content">
            Paid Content
          </label>
        </div>
      </div>
      <div class="col-6 col-md-6 col-lg-6">
        <input type="text" class="add-post-input" id="price" style="padding-top: 0px !important" readonly required name="price" placeholder="<?= $price?>">
      </div>
    </div>
    <div class="row gx-0 add-post" style="padding-top: 10px; padding-bottom: 0px !important">
      <p class="small-text"><?= $category_name ?></p>
    </div>
  </div>

  <input type="hidden" id="caption" name="caption" value="<?= $caption ?>">
  <input type="hidden" id="location" name="location" value="<?= $location ?>">
  <input type="hidden" id="hashtag" name="hashtag" value="<?= $hashtag ?>">
  <input type="hidden" id="tagged_product" name="tagged_product" value="<?= $tagged_product ?>">
  <input type="hidden" id="post_check" name="post_check" value="<?= $post_check ?>">
  <input type="hidden" id="is_paid" name="is_paid" value="<?= $is_paid ?>">
  <input type="hidden" id="category" name="category" value="<?= $category ?>">

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

    if (localStorage.lang == 1){
      $('#paid-content').text('Konten Berbayar');
    }

    $('body').show();
	});

  // DISPLAY IMAGE FROM LOCALSTORAGE

  var link = localStorage.getItem('link');

  if (link != null){
    setImage(link);
  }

  function setImage(link){
    var strings = link.split("/");

    if (strings[0] == 'data:video'){
      $('#image-preview').replaceWith('<video src="'+link+'#t=0.5" id="video-preview" autoplay muted style="object-fit: none" type="video/mp4" class="new-post-image">');
      
      // IF DIV ALREADY VIDEO REPLACE AGAIN (CONFLICT : NO IMG REPLACED WITH VIDEO)

      $('#video-preview').replaceWith('<video src="'+link+'#t=0.5" id="video-preview'+'" autoplay muted  style="object-fit: none" type="video/mp4" class="new-post-image">');
    }else{
      var output = document.getElementById('image-preview');
      output.src = link;
    }
  }

  // IMAGE ONCLICK SHOW TAG

  $(".tagged-product-text-image").hide();
  $(".tagged-product-text").hide();

  $(".preview-post-image").click(function() {
    $(".tagged-product-text-image").show();
    $(".tagged-product-text").show();
  });

  // IF IS PAID CHECKED

  if ($('#is_paid').val() == 1){
    $('#flexCheckDefault').prop('checked', true);
  }
    
// SCRIPT CONVERT BASE64 TO OBJECT

function dataURLtoFile(dataurl, filename){
  var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
  bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);

  while(n--){
    u8arr[n] = bstr.charCodeAt(n);
  }

  return new File([u8arr], filename, {type:mime});
}

// SEND FORM VIA XMLHTTPREQUEST

function sendData(){

  var formData = new FormData();

  let file = localStorage.getItem('link');

  var caption = $('#caption').val();
  var hashtag = $('#hashtag').val();
  var location = $('#location').val();
  var tagged_product = $('#tagged_product').val();
  var post_check = $('#post_check').val();
  var is_paid = $('#is_paid').val();

  var format = file.split(";");
  
  // SORT JPEG

  if (format[0].slice(-4) == "jpeg" || format[0].slice(-4) == "webp"){
    var ext = format[0].slice(-4);
  }else{
    var ext = format[0].slice(-3);
  }

  var converted_link = dataURLtoFile(file, "." + ext);

  formData.append('caption', caption);
  formData.append('hashtag', hashtag);
  formData.append('location', location);
  formData.append('tagged_product', tagged_product);
  formData.append('post_photo', converted_link);
  formData.append('post_check', post_check);
  formData.append('is_paid', is_paid);

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function (){

    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
      console.log(xmlHttp.responseText);
      if(xmlHttp.responseText=="Berhasil"){
      window.location.href = "/nexilis/pages/tab5-store?success=true";
      }else{
        console.log("Gagal nih");
      }
    }
  }

  xmlHttp.open("post", "../logics/tab5/insert_new_post");
  xmlHttp.send(formData);

}

</script>
</html>