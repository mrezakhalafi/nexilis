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

  // ID SHOP CHECK

	if (!isset($id_shop)) {
		die("ID Shop Tidak Diset.");
	}

	// SELECT STORE PRODUCT
	
	$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 1 
                            AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC LIMIT 2");
	$query->execute();
	$store_product = $query->get_result();
	$query->close();

  // SELECT SHOP DATA ACTIVE

	$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 1 
                            AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
	$query->execute();
	$store_product_all = $query->get_result();
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

    /* FOR RED DOT FORM  */

    .form-group{
      position: relative;
    }

    .form-control:focus {
      background-color: transparent;
      box-shadow: none;
    }

    .upload-listing-input{
      background-color: transparent;
    } 

    .palceholder{
      position: absolute;
      color: #797979;
      display: none;
    }

    .star{
      color: red;
      margin-left: -3px;
    }

    /* FOR MODAL CAN BE SLIDER DOWN */

    body.modal-open {
      position: inherit;
    }

    /* FOR FULL WIDTH MODAL */

    .modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
    margin-top: 10px;
    }

    .modal-body{
      margin-top: -15px;
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

  </style>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_16.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">
  <div id="header"></div>

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5createlivestream-1">Create Live Stream</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img src="" class="navbar-brand-slot">
      </div>
    </div>
  </nav>

  <!--  SECTION LIVE STREAM FORM -->

  <form action="../logics/tab5/live_streaming" method="POST" enctype="multipart/form-data">
    <div class="section-live-stream">
      <div class="row gx-0">
        <div class="col-7 col-md-7 col-lg-7">
          <div class="live-stream-title">
            <div class="form-group">
              <div class="palceholder live-stream-title-input">
                <label for="title" class="title_placeholder" data-translate="tab5createlivestream-2">Live Stream Title</label>
                <span class="star">*</span>
              </div>
              <input type="text" class="form-control live-stream-title-input" id="title" name="ls_title" required>
            </div>
          </div>
          <div class="live-stream-desc">
            <div class="form-group">
              <div class="palceholder live-stream-desc-input">
                <label for="desc" data-translate="tab5createlivestream-3">Live Stream <br> Description (Optional)</label>
              </div>
              <img src="../assets/img/tab5/Pin-(Grey).png" class="live-stream-pin">
              <textarea class="upload-listing-input form-control" id="desc" rows="5" name="ls_desc"></textarea>
            </div>
          </div>
        </div>
        <div class="col-5 col-md-5 col-lg-5 live-stream-image d-flex justify-content-center">
          <div class="single-upload-cover">
            <div class="image-upload">
              <label for="file-input">
                <img src="../assets/img/tab5/Dashed-Image.png" class="live-upload-image" id="image-preview-1">
                <img src="../assets/img/tab5/Add-(Grey).png" class="upload-cover-add"></label>
              <input id="file-input" type="file" name="ls_thumbnail" onchange="loadFile(event)" />
            </div>
            <p class="cover-title" data-translate="tab5createlivestream-4">Live Stream Cover</p>
          </div>
        </div>
      </div>
    </div>

    <!--  SECTION LIVE FEATURED PRODUCT -->

    <div class="section-live-featured-product">
      <div class="live-stream-sub-title">
        <p class="container small-text" data-translate="tab5createlivestream-5"><b>Featured Products</b></p>
      </div>

      <?php 
      
      $product_match = array();

      // IF HAVE A PRODUCT

      if (mysqli_num_rows($store_product)>0): 
      
      ?>

      <div class="live-featured-product">
        <div class="container before-featured-product">

          <!-- LOOP PRODUCT AS FEATURED -->

          <?php foreach ($store_product as $singleProduct): ?>

          <div class="row single-featured-product" id="single-product-<?= $singleProduct['CODE'] ?>">
            <div class="col-2 col-md-2 col-lg-2">

              <?php $product_image = explode('|', $singleProduct['THUMB_ID']); ?>

              <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

              <?php $i = 0; ?>

              <?php if (substr($product_image[$i], -3) == "mp4"): ?>

                <!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

                <?php 
                while (substr($product_image[$i], -3) == "mp4"):
                  $product_image_video = $product_image[$i+1];
                  $i++;
                endwhile; 
                ?>

                <!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

                <?php if ($product_image_video): ?>
                  <img src="../images/<?= $product_image_video ?>" class="live-featured-image" style="border-radius: 10px">
                <?php else: ?>
                  <video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="live-featured-image"></video>
                <?php endif; ?>

              <?php else: ?>

                <img src="../images/<?= $product_image[$i] ?>" class="live-featured-image" style="border-radius: 10px">

              <?php endif; ?>

            </div>
            <div class="col-8 col-md-8 col-lg-9 featured-product-desc">
              <div class="small-text"><?= $singleProduct['NAME'] ?></div>
              <div class="small-text text-grey">Rp <?= number_format($singleProduct['PRICE'],0,",",",") ?></div>
            </div>
            <div class="col-2 col-md-2 col-lg-1">
              <img src="../assets/img/tab5/Delete.png" class="delete-feature-icon" data-code="<?= $singleProduct['CODE'] ?>">
            </div>
          </div>

          <!-- IF FEATURED EXIST BEFORE ADD WITH | -->

          <?php 

            if (!isset($featured_product)) :
              $featured_product = $singleProduct['CODE'];
            else:    
              $featured_product = $featured_product."|".$singleProduct['CODE']; 
            endif;

            array_push($product_match, $singleProduct['CODE']);
          
          endforeach; ?>

        </div>
      </div>

      <div class="live-stream-sub-desc d-flex justify-content-center" onclick="showModal()">
        <img src="../assets/img/tab5/Add-(Grey).png" class="add-live-icon">
        <span class="text-center small-text text-grey" data-translate="tab5createlivestream-6">Add More Products</span>
      </div>

  <?php else: ?>

    <div class="section-live-featured-product">
      <p class="text-center small-text mt-3 text-grey" data-translate="tab5createlivestream-7">Anda belum memiliki produk.</p>
    </div>

  <?php endif; ?>

    <div class="section-live-featured-product product-0">
      <p class="text-center small-text mt-3 text-grey" data-translate="tab5createlivestream-8">Anda tidak memasukan produk.</p>
    </div>

    <input type="hidden" id="ls_product" name="ls_product" value="<?= $featured_product ?>">
  </div>

  <!--  SECTION LIVE DISCOUNT VOUCHER -->

  <div class="section-live-discount-voucher">
    <div class="live-stream-sub-title">
      <div class="container">
        <p class="small-text" data-translate="tab5createlivestream-9"><b>Discount & Vouchers</b></p>
      </div>
      <div class="live-stream-sub-desc">
        <a href="../pages/tab5-coupons.php?source=lv">
          <div class="d-flex justify-content-center">
            <img src="../assets/img/tab5/Add-(Grey).png" class="add-live-icon">
            <span class="text-center small-text text-grey" data-translate="tab5createlivestream-10">Add Discount & Vouchers</span>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div id="btn-place">
    <div class="row text-center fixed-bottom">
      <button class="btn-live" style="border:none" type="submit" data-translate="tab5createlivestream-11">Go Live!</button>
    </div>
  </div>

  <!-- Modal -->

  <div class="modal fade" id="addMoreProductModal" tabindex="-1" aria-labelledby="addMoreProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="align-items: flex-end;">
      <div class="modal-content">
        <div class="mod-header">
          <div class="row d-flex justify-content-center">
            <hr class="shop-modal-line">
          </div>
        </div>
        <div class="modal-body modal-add-tag">
          <div class="row new-post-search-slot" style="width: 94%">
            <div class="col-9 col-md-9 col-lg-9">
              <input type="text" class="tag-product-input shadow-sm" placeholder="Search">
              <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png" style=" width: 20px; height: 20px; margin-left: 50%; margin-top: -24px; position: absolute">
              <img src="../assets/img/icons/Voice-Command-(Black).png" onclick="voiceSearch()" class="new-post-voice">
            </div>
            <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end">
              <!-- <img src="../assets/img/tab5/Search-(Purple).png" class="new-post-search"> -->
              <button type="button" class="btn-submit-tagged-product" data-bs-dismiss="modal"><b>SUBMIT</b></button>
            </div>

            <div class="row featured-modal gx-0">

              <!-- LOOPING ALL PRODUCT FOR FEATURED PRODUCT -->

              <?php foreach ($store_product_all as $product): ?>

              <div class="col-6 col-md-6 col-lg-6 gx-0 shadow-sm col-tagged-product">
                <div class="single-tagged-product">
                  <label for="myCheck<?= $product['CODE'] ?>">
                    <div class="form-check form-check-tagged" style="padding-left: 0 !important">

                      <!-- EXPLODE IMAGE TO GET 1 IMAGE -->

                      <?php $product_image = explode('|', $product['THUMB_ID']); ?>

                      <input class="form-check-input check-form-input check-form-input" type="checkbox" 

                      <?php if (in_array($product['CODE'],$product_match)): ?> checked <?php endif; ?>
                        data-code-product="<?= $product['CODE'] ?>" data-name-product="<?= $product['NAME'] ?>"
                      
                      <?php $i = 0;

                      // SET THUMBNAIL TO PHOTO IF SELECTED IS VIDEO

                      // WHILE TO GET IMAGES, IF THERE IS NO IMAGE USE TAG <VIDEO
                      
                      if (substr($product_image[$i], -3) == "mp4"): ?>

                        <?php 
                        while (substr($product_image[$i], -3) == "mp4"):
                          $product_image_video = $product_image[$i+1];
                          $i++;
                        endwhile; 
                        ?>

                        <?php if ($product_image_video): ?>
                          data-image-product="<?= $product_image_video ?>"
                        <?php else: ?>
                          data-image-product="<?= $product_image[0] ?>"
                        <?php endif; ?>

                      <?php else: ?>

                        data-image-product="<?= $product_image[$i] ?>"
                        
                      <?php endif ?>
                      
                      data-price-product="<?= $product['PRICE'] ?>"
                      value="<?= $product['NAME'] ?>" id="myCheck<?= $product['CODE'] ?>">

                      <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

                      <?php $i = 0; ?>

                      <?php if(substr($product_image[$i], -3) == "mp4"): ?>

                        <!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

                        <?php 
                        while (substr($product_image[$i], -3) == "mp4"):
                          $product_image_video = $product_image[$i+1];
                          $i++;
                        endwhile; 
                        ?>

                        <!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

                        <?php if ($product_image_video): ?>
                          <img src="../images/<?= $product_image_video ?>" class="tagged-image" style="border-radius: 10px">
                        <?php else: ?>
                          <video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="tagged-image"></video>
                        <?php endif; ?>

                      <?php else: ?>

                        <img src="../images/<?= $product_image[$i] ?>" class="tagged-image" style="border-radius: 10px">

                      <?php endif; ?>
                      
                      <img src="../assets/img/tab5/Settings-(White).png" class="tagged-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                      
                      <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                        
                        <li><a href="tab5-edit-listing.php?id=<?= $product['CODE'] ?>" class="dropdown-item" data-translate="tab5newpost-5">Edit</a></li>
                        <form action="../logics/tab5/delete_listing.php?id=<?= $product['CODE'] ?>" method="POST">  
                          <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5newpost-6">Delete</button></li>
                        </form>
                      
                      </ul>
                      
                      <div class="row tagged-product-desc">
                        <span class="small-text tagged-product-title"><b><?= $product['NAME'] ?></b></span>
                        <div class="row tagged-product-desc-row">
                          <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                            <span class="smallest-text text-grey"><?= $product['QUANTITY'] ?> in stock</span>
                          </div>
                          <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                            <span class="smallest-text text-grey">Rp <?= number_format($product['PRICE'],0,",",",") ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <?php endforeach; ?>

              </div>

              <div class="col-tagged-product-no-result">
                <p class="text-center small-text" style="margin-left: -30px" data-translate="tab5newpost-7">Produk tidak ditemukan.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <input type="hidden" id="shop_id" value="<?= $id_shop ?>">

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.13.0-rc.2/jquery-ui.js" integrity="sha256-bLjSmbMs5XYwqLIj5ppZFblCo0/9jfdiG/WjPhg52/M=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();
    $('body').show();
	});

  // SCRIPT RED DOT FORM

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

  // SAVE DATA FOR REFRESH

  $("#title").blur(function() {
    var title = $(this).val();
    localStorage.setItem('title_ls', title);
  })

  var get_title = localStorage.getItem('title_ls');
  var get_thumbnail = localStorage.getItem('thumbnail_ls');
  var feature_product_temp_code = localStorage.getItem('feature_product_temp_code');

  if (get_title != null){
    $('#title').siblings('.palceholder').hide();
    $("#title").val(get_title);
  }

  if (get_thumbnail != null){
    $('.upload-cover-add').hide();
    $('.cover-title').hide();

    var output = document.getElementById('image-preview-1');
    output.src = get_thumbnail;
  }

  if (feature_product_temp_code != null){
    $('#ls_product').val(feature_product_temp_code);

    var split = feature_product_temp_code.split("|");

    // UNCHECK ALL AND CHECK BASED ARRAY BEFORE REFRESH 

    $('.check-form-input').prop('checked', false);

    for (var i=0; i<split.length; i++){
      $('#myCheck'+split[i]).prop('checked', true);
    }
  }

  if (localStorage.getItem('feature_product_temp') != null){
    $('.before-featured-product').html(localStorage.getItem('feature_product_temp'));
  }

  console.log($('#ls_product').val());

  // CHANGE IMAGE AS CHOOSE

  var loadFile = function(event){
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('image-preview-1');
      output.src = reader.result;

      // SET SELECTED IMAGE TO LOCAL STORAGE

      var thumbnail = reader.result;
      localStorage.setItem("thumbnail_ls", thumbnail);
    };
    reader.readAsDataURL(event.target.files[0]);

    $('.upload-cover-add').hide();
    $('.cover-title').hide();
  };

  var feature_product_temp;

  // SCRIPT DELETE FEATURED PRODUCT

  $('.product-0').hide();

  $('body').on('click', '.delete-feature-icon', function (){    

    var code = $(this).data("code"); 
    $(this).parent().parent().hide();

    var ls_product = $('#ls_product').val();

      // IF DELETED ARRAY EXIST BEFORE ADD |

      if (ls_product.includes("|")){

        var a = ls_product.replace("|"+code,"");
        var b = a.replace(code+"|","");
        $("#ls_product").val(b);   

      }else{
        ls_product = ls_product.replace(code,"");

        $('#ls_product').val(ls_product);
      }

    // DELETE DIV IF THERE IS NO MORE PRODUCT

    // if (ls_product == ""){
    //     $('.live-featured-product').hide();
    // }

    // REMOVE FROM CHECKBOX

    $('#myCheck'+code).prop('checked', false);

    console.log($('#ls_product').val());

    // SAVE DELETE TO LOCAL STORAGE 

    feature_product_temp = $('.before-featured-product').html();
    localStorage.setItem('feature_product_temp', feature_product_temp);
    localStorage.setItem('feature_product_temp_code', $('#ls_product').val());
      
  });

  // ON CHECKED/UNCHECKED PRODUCT

  $('body').on('click', ':checkbox', function (e){    

    var array_product = $('#ls_product').val();

    if (this.checked){

      $('#ls_product').val($(this).val());

      // IF USER ALREADY CHOOSE PRODUCT USE PRODUCT FROM USER

      if (array_product.length>0){
        array_product = array_product + "|" + $(this).data("code-product");
      }else{
        array_product = $(this).data("code-product");
      }

      $('#ls_product').val(array_product);

      // APPEND FROM MODAL (REAL) TO FEATURED PRODUCT

      console.log(array_product);

      // CHECK IF THIS MEDIA PHOTO OR VIDEO

      var media = $(this).data("image-product");

      // IF MEDIA HAS MP4 OR #T=0.5 USE TAG VIDEO

      if (media.slice(-3) == 'mp4'){
        var ext = "video";
        var ext2 = "</video>";
        media = $(this).data("image-product")+"#t=0.5";
      }else if(media.slice(-6) == '#t=0.5'){
        var ext = "video";
        var ext2 = "</video>";
      }else{
        var ext = "img";
        var ext2 = "";
      }

      $('.before-featured-product').append(
        `<div class="row single-featured-product" id="single-product-`+$(this).data("code-product")+`">
          <div class="col-2 col-md-2 col-lg-2">

          <`+ext+` src="../images/`+media+`" class="live-featured-image" style="border-radius: 10px">`+ext2+`

          </div>
          <div class="col-8 col-md-8 col-lg-9 featured-product-desc">
              <div class="small-text">`+$(this).data("name-product")+`</div>
              <div class="small-text text-grey">Rp `+$(this).data("price-product").toLocaleString()+`</div>
          </div>
          <div class="col-2 col-md-2 col-lg-1">
              <img src="../assets/img/tab5/Delete.png" class="delete-feature-icon" data-code="`+$(this).data("code-product")+`">
          </div>
        </div>`);

    }else{

      var array_product = $('#ls_product').val();
      console.log($(this).data('code-product'));
      
      $('#single-product-'+$(this).data('code-product')).remove();

      // REMOVE SECOND THIRD FROM ARRAY

      if (array_product.includes("|")){

        var a = array_product.replace("|"+$(this).data("code-product"),"");
        var b = array_product.replace($(this).data("code-product")+"|","");
        $("#ls_product").val(b);     

      }else{
        array_product = array_product.replace($(this).data("code-product"),"");

        $('#ls_product').val(array_product);
      }

      var array_product = $('#ls_product').val();
      $('#ls_product').val(array_product);
      console.log(array_product);
    }

    // SAVE INSERT TO LOCAL STORAGE 

    feature_product_temp = $('.before-featured-product').html();
    localStorage.setItem('feature_product_temp', feature_product_temp);
    localStorage.setItem('feature_product_temp_code', $('#ls_product').val());
  });

  // POPUP MODAL PRODUCT

  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));
  let init = parseInt(fixedPos.replace('px', ''));

  $('[data-bs-target="#addMoreProductModal"]').click(function () {
    $('#addMoreProductModal .modal-dialog').css('top', fixedPos);
    $('#addMoreProductModal .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#addMoreProductModal').draggable({
    handle: ".mod-header",
    axis: "y",
    drag: function (event, ui) {

      console.log('init: ' + init);

      if (ui.position.top < init) {
        ui.position.top = init;
      }

      let dialog = ui.position.top + window.innerHeight;
      if (dialog - window.innerHeight > 50) {
        $('#addMoreProductModal').modal('hide');
      }
    }
  });

  function showModal(){
    $('body').css('height', '900px');
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 1));
    $('#addMoreProductModal').modal('show');
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    $('.modal-dialog').css('margin-top', '50px');
    $('.modal-dialog').css('padding-bottom', '50px');
  }

  $('#addMoreProductModal').on('hidden.bs.modal', function (){
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    $('.modal').css('top', '0px');
    $('.modal').css('left', '0px');
    $('body').css('height', '100%');
    $('.modal-dialog').css('margin-top', '-50px');
    $('.modal-dialog').css('padding-bottom', '-50px');
  })

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

  // FOR INPUT INSIDE DRAGGABLE CONTENT FOCUSED

  $(':input').bind('click', function(){
    $(this).focus();
  });

  // PREVENT INPUT WITH ENTER (DISABLE POPUP REQUIRED)

  $(':input').on('keypress', function(e) {
    return e.which !== 13;
  });

  // SEARCH TAGGED PRODUCT

  $('.col-tagged-product-no-result').hide();

  $('.tag-product-input').keypress(function (e) {

    // SEARCH BY PRESS ENTER

    if (e.which == 13){

      var query = $('.tag-product-input').val();
      var shop_id = $('#shop_id').val();

      if (query!=""){

        $.ajax({
          type: "POST",
          url: '../logics/tab5/search_tagged',
          data: {
            "query" : query,
            "shop_id" : shop_id
          },
          success: function(response){

            var objResult = JSON.parse(response);
            console.log(response);

            // IF SEARCH PRODUCT BY QUERY FOUND, STRING MORE THAN 3

            if (response.length>3){

              $('.featured-modal').html(""); 
              $('.col-tagged-product-no-result').hide();

              // LOOP RESULT FROM LOGICS

              Object.keys(objResult).forEach(function (item){

                var product_image = objResult[item]['THUMB_ID'].split("|");
                var i = 0;

                // IF THUMBNAIL WAS VIDEOS CHANGE IT TO NEXT ARRAY TO IMAGE

                if ((product_image[i]).slice(-3) == 'mp4'){

                  while (i < product_image.length){

                    // WHEN GET PICTURES ON CENTER [ EX 1,3 VIDEO, 2 PICTURE, BREAK AT 2 ]

                    if ((product_image[i]).slice(-3) == 'mp4'){
                      var product_image_video = product_image[(i+1)];
                    }else{
                      break;
                    }
                    
                    i++;
                  }

                  // IF GET PICTURE TAG = IMG

                  if (product_image_video){
                    var product_image_video = product_image_video;
                    ext = "img style='object-fit:cover; border-radius: 10px'";
                    ext2 = "";

                  // IF THERE IS NO PICTURE ON PRODUCT TAG = VIDEO
                    
                  }else{
                    var product_image_video = product_image[0]+"#t=0.5";
                    ext = "video style='object-fit:none; border-radius: 10px'";
                    ext2 = "</video>";
                  }
                }else{
                  var product_image_video = product_image[i];
                  ext = "img style='object-fit:cover; border-radius: 10px'";
                  ext2 = "";
                }

                // IF CODE IS IN ARRAY AUTO CHECKED

                var ls_product = $('#ls_product').val();

                if (ls_product.includes(objResult[item]['CODE'])){
                  $checked = "checked";
                }else{
                  $checked = "";
                }

                // APPEND FROM MODAL (SEARCH) TO FEATURED PRODUCT

                var new_tagged = 
                `<div class="col-6 col-md-6 col-lg-6 gx-0 shadow-sm col-tagged-product">
                  <div class="single-tagged-product">
                    <label for="myCheck`+objResult[item]['ID']+`">
                      <div class="form-check form-check-tagged" style="padding-left: 0 !important">

                        <input class="form-check-input check-form-input check-form-input" `+$checked+` type="checkbox" data-code-product="`+objResult[item]['CODE']+`" data-name-product="`+objResult[item]['NAME']+`" 
                        data-price-product="`+objResult[item]['PRICE']+`" data-image-product="`+product_image_video+`" value="`+objResult[item]['NAME']+`" id="myCheck`+objResult[item]['ID']+`">

                        <`+ext+` src="../images/`+product_image_video+`" class="tagged-image">`+ext2+`
                        
                        <img src="../assets/img/tab5/Settings-(White).png" class="tagged-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                        <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                          
                          <li><a href="tab5-edit-listing.php?id=`+objResult[item]['CODE']+`" class="dropdown-item" data-translate="tab5newpost-5">Edit</a></li>
                          <form action="../logics/tab5/delete_listing.php?id=`+objResult[item]['CODE']+`" method="POST">  
                          <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5newpost-6">Delete</button></li>
                          </form>
                        
                        </ul>
                        <div class="row tagged-product-desc">
                          <span class="small-text tagged-product-title"><b>`+objResult[item]['NAME']+`</b></span>
                          <div class="row tagged-product-desc-row">
                            <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                              <span class="smallest-text text-grey">`+objResult[item]['QUANTITY']+` <span data-translate="tab5newpost-4"> in stock</span></span>
                            </div>
                            <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                              <span class="smallest-text text-grey">Rp `+objResult[item]['PRICE'].toLocaleString()+`</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </label>
                  </div>
                </div>`;

                $('.featured-modal').append(new_tagged);
                console.log(objResult[item]['NAME']);

              });

            }else{
              $('.col-tagged-product').html("");
              $('.col-tagged-product-no-result').show();
            }
          }
        });
      }
    }
  });

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function () {
    $('.tag-product-input').val('');
    $('#delete-query').addClass('d-none');

    var query = $('.tag-product-input').val();
    var shop_id = $('#shop_id').val();

    // GET ALL PRODUCT

    $.ajax({
      type: "POST",
      url: '../logics/tab5/all_product',
      data: {
        "shop_id" : shop_id
      },
      success: function(response){

        var objResult = JSON.parse(response);
        console.log(response);

        $('.featured-modal').html(""); 
        $('.col-tagged-product-no-result').hide();

        Object.keys(objResult).forEach(function (item){

          var product_image = objResult[item]['THUMB_ID'].split("|");
          var i = 0;

          // IF THUMBNAIL WAS VIDEOS CHANGE IT TO NEXT ARRAY TO IMAGE

          if ((product_image[i]).slice(-3) == 'mp4'){

            while (i < product_image.length){

              // WHEN GET PICTURES ON CENTER [ EX 1,3 VIDEO, 2 PICTURE, BREAK AT 2 ]

              if ((product_image[i]).slice(-3) == 'mp4'){
                var product_image_video = product_image[(i+1)];
              }else{
                break;
              }
              
              i++;
            }

            // IF GET PICTURE TAG = IMG

            if (product_image_video){
              var product_image_video = product_image_video;
              ext = "img style='object-fit:cover; border-radius: 10px'";
              ext2 = "";

            // IF THERE IS NO PICTURE ON PRODUCT TAG = VIDEO
              
            }else{
              var product_image_video = product_image[0]+"#t=0.5";
              ext = "video style='object-fit:none; border-radius: 10px'";
              ext2 = "</video>";
            }
          }else{
          var product_image_video = product_image[i];
            ext = "img style='object-fit:cover; border-radius: 10px'";
            ext2 = "";
          }

          var ls_product = $('#ls_product').val();

          // GIVE CHECKED TO PRODUCT IF USER ALREADY CHECKED BEFORE

          if (ls_product.includes(objResult[item]['CODE'])){
            $checked = "checked";
          }else{
            $checked = "";
          }

          // APPEND ALL PRODUCT AGAIN TO FEATURED PRODUCT

          var new_tagged = 
          `<div class="col-6 col-md-6 col-lg-6 gx-0 shadow-sm col-tagged-product">
            <div class="single-tagged-product">
              <label for="myCheck`+objResult[item]['ID']+`">
                <div class="form-check form-check-tagged" style="padding-left: 0 !important">

                  <input class="form-check-input check-form-input check-form-input" `+$checked+` type="checkbox" data-code-product="`+objResult[item]['CODE']+`" data-name-product="`+objResult[item]['NAME']+`" 
                  data-price-product="`+objResult[item]['PRICE']+`" data-image-product="`+product_image_video+`" value="`+objResult[item]['NAME']+`" id="myCheck`+objResult[item]['ID']+`">

                  <`+ext+` src="`+product_image_video+`" class="tagged-image">`+ext2+`
                  
                  <img src="../assets/img/tab5/Settings-(White).png" class="tagged-product-settings" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false">
                  <ul class="dropdown-menu" style="min-width: auto !important; position: absolute" aria-labelledby="dropdownMenuLanguage">
                    
                    <li><a href="tab5-edit-listing.php?id=`+objResult[item]['CODE']+`" class="dropdown-item" data-translate="tab5newpost-5">Edit</a></li>
                    <form action="../logics/tab5/delete_listing.php?id=`+objResult[item]['CODE']+`" method="POST">  
                    <li><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5newpost-6">Delete</button></li>
                    </form>
                  
                  </ul>
                  <div class="row tagged-product-desc">
                    <span class="small-text tagged-product-title"><b>`+objResult[item]['NAME']+`</b></span>
                    <div class="row tagged-product-desc-row">
                      <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                        <span class="smallest-text text-grey">`+objResult[item]['QUANTITY']+` <span data-translate="tab5newpost-4"> in stock</span></span>
                      </div>
                      <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                        <span class="smallest-text text-grey">Rp `+objResult[item]['PRICE'].toLocaleString()+`</span>
                      </div>
                    </div>
                  </div>
                </div>
              </label>
            </div>
          </div>`;

          $('.featured-modal').append(new_tagged);
          console.log(objResult[item]['NAME']);

        });
      }
    });
  })

  // APPEAR X WHILE STRING ON SEARCH

  $('.tag-product-input').keyup(function () {

    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }

  })

  // FUNCTION VOICE SEARCH

  function voiceSearch(){
    Android.toggleVoiceSearch();
  }

  function submitVoiceSearch(searchQuery){
    $('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
  }

</script>
</html>