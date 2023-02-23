<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

  //START SESSION

	if (isset($_GET['id'])){
		$id_shop = $_GET['id'];
	}else if(isset($_SESSION['id_shop'])){
		$id_shop = $_SESSION["id_shop"];
	}else if(isset($_COOKIE['id_shop'])){
		$id_shop = $_COOKIE['id_shop'];
	}

	// CHECK USER

	if (!isset($id_shop)){
		die("ID Shop Tidak Diset.");
	}

	//SELECT SHOP DATA

	$query = $dbconn->prepare("SELECT * FROM SHOP LEFT JOIN SHOP_SHIPPING_ADDRESS ON SHOP.CODE = 
                              SHOP_SHIPPING_ADDRESS.STORE_CODE WHERE CODE = '$id_shop'");
	$query->execute();
	$shop_data = $query->get_result()->fetch_assoc();
	$query->close();

  // SELECT SHOP FOLLOWERS
	
	$query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW WHERE STORE_CODE = '$id_shop'");
	$query->execute();
	$shop_follow = $query->get_result();
	$query->close();

  // GET SHOP CATEGORY

  $id_category = $shop_data["CATEGORY"];

	$query = $dbconn->prepare("SELECT * FROM SHOP_CATEGORY WHERE ID = '$id_category'");
	$query->execute();
	$shop_category = $query->get_result()->fetch_assoc();
	$query->close();

  // GET SHOP PRODUCT/SHOP

  $shop_product = [];

  $query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop' AND IS_SHOW = 1 
                            AND IS_DELETED = 0 ORDER BY CREATED_DATE DESC");
  $query->execute();
  $shopProduct = $query->get_result();
  $query->close();

  while ($row = $shopProduct->fetch_array(MYSQLI_ASSOC))
  {
    $shop_product[] = $row;
  }

  $query = $dbconn->prepare("SELECT * FROM POST WHERE MERCHANT = '$id_shop' ORDER BY CREATED_DATE DESC");
  $query->execute();
  $shopProduct = $query->get_result();
  $query->close();

  while ($row = $shopProduct->fetch_array(MYSQLI_ASSOC))
  {
    $shop_product[] = $row;
  }

  // GET SHOP POST

  $query = $dbconn->prepare("SELECT * FROM POST WHERE MERCHANT = '$id_shop' ORDER BY CREATED_DATE DESC");
  $query->execute();
  $shop_post = $query->get_result();
  $query->close();

  // GET SHOP REELS

  $query = $dbconn->prepare("SELECT * FROM SHOP_REELS WHERE STORE_CODE = '$id_shop'");
  $query->execute();
  $shop_reels = $query->get_result();
  $query->close();

  // SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

  	// COUNT SHOP FOLLOWING (USER)

	$query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE STORE_CODE = '$id_shop'");
	$query->execute();
	$shop_following = $query->get_result();
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

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5store-1">Store</p>
      <div class="navbar-brand pt-2 navbar-brand-slot-with-margin">
        <a href="tab5-upload-listing.php">
          <img src="../assets/img/tab5/add-black.png" class="navbar-img-add-purple" data-bs-toggle="modal" data-bs-target="#addShopModal">
        </a>
      </div>
    </div>
  </nav>

  <!-- SECTION STORE -->

  <?php if (isset($shop_data)): ?>

  <div class="section-store">
    <div class="container">
      <div class="row mb-2">
        <div class="col-3 col-md-3 col-lg-3">

          <?php if ($shop_data["THUMB_ID"]!=""): ?>
            <img src="../images/<?= $shop_data["THUMB_ID"] ?>" class="ava-profile">
          <?php else: ?>
            <img src="../assets/img/tab5/shop.jpg" class="ava-profile">
          <?php endif; ?>

        </div>
        <div class="col-9 col-md-9 col-lg-9">
          <div class="row store-name">
            <b><?= $shop_data["NAME"] ?></b>
          </div>
          <div class="row small-text"><?= $shop_data['ADDRESS'] ?></div>
          <div style="margin-top: 3px">
            <span class="small-text followers-slot">
              <span class="text-purple small-text follow-number">
                <b><?= mysqli_num_rows($shop_follow) ?></b>
              </span>
              <b data-translate="tab5store-2">Followers</b>
            </span>
            <span class="small-text">
              <span class="text-purple small-text follow-number">
                <b><?= mysqli_num_rows($shop_following) ?></b>
              </span> 
            <b data-translate="tab5store-3">Following</b>
            </span>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <span class="small-text"><?= $shop_category["NAME"] ?></span>
        <span class="small-text"><?= $shop_data["DESCRIPTION"] ?></span>
        <a class="small-text" style="color:#378ff3" href="http://<?= $shop_data["LINK"] ?>"><?= $shop_data["LINK"] ?></a>
      </div>
    </div>
    <div class="section-shop-nav-switch">
      <div class="row text-center" style="width: 106%;">
        <div class="col-4 col-md-4 col-lg-4 store-nav-single-left">
          <p class="small-text"><b data-translate="tab5store-4">Posts</b></p>
        </div>
        <div class="col-4 col-md-4 col-lg-4 store-nav-single-center">
          <p class="small-text"><b data-translate="tab5store-5">Shop</b></p>
        </div>
        <div class="col-4 col-md-4 col-lg-4 store-nav-single-right">
          <p class="small-text"><b data-translate="tab5store-6">Reels</b></p>
        </div>
      </div>
    </div>
  </div>

  <?php else: ?>

  <div class="section-store">
    <div class="container">
      <div class="row mb-2">
        <div class="col-3 col-md-3 col-lg-3">
          <img src="../assets/img/tab5/shop.jpg" class="ava-profile">
        </div>
        <div class="col-9 col-md-9 col-lg-9">
          <div class="row store-name">
            <b>Dummy Shop (ID = <?= $id_shop ?>)</b>
          </div>
          <div class="row small-text">Jakarta Barat, Indonesia</div>
          <div style="margin-top: 3px">
            <span class="small-text followers-slot">
              <span class="text-purple small-text follow-number">
                <b>10</b>
              </span>
              <b>Followers</b>
            </span>
            <span class="small-text">
              <span class="text-purple small-text follow-number">
                <b>20</b>
              </span> 
            <b>Following</b>
            </span>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <span class="small-text">Home & Lifestyle</span>
        <span class="small-text">Create a fun & cozy home</span>
        <a class="small-text" style="color:#378ff3" href="https://www.alexeifurnitureshop.com">https://www.alexeifurnitureshop.com</a>
      </div>
    </div>
    <div class="section-shop-nav-switch">
        <div class="row text-center" style="width: 106%;">
          <div class="col-4 col-md-4 col-lg-4 store-nav-single-left">
            <p class="small-text"><b>Posts</b></p>
          </div>
          <div class="col-4 col-md-4 col-lg-4 store-nav-single-center">
            <p class="small-text"><b>Shop</b></p>
          </div>
          <div class="col-4 col-md-4 col-lg-4 store-nav-single-right">
            <p class="small-text"><b>Reels</b></p>
        </div>
      </div>
    </div>
  </div>

  <?php endif; ?>

  <!-- SECTION POST -->

  <div class="section-store-post">

    <?php if (mysqli_num_rows($shop_post)) : ?>

      <div class="row gx-0">
        <?php foreach ($shop_post as $post): ?>
          <div class="col-4">

              <?php if (substr($post['FILE_ID'], -3) == "mp4"): ?>
                <video src="../images/<?= $post['FILE_ID'] ?>" onclick="openPostModalVideo('<?= $post['FILE_ID'] ?>')" autoplay muted loop class="store-post" style="object-fit: cover; object-position: center; margin-bottom: -10px; border-radius: 10px"></video>
              <?php else: ?>
                <img src="../images/<?= $post['FILE_ID'] ?>" onclick="openPostModal('<?= $post['FILE_ID'] ?>')" class="store-post" style="border-radius: 10px">
              <?php endif; ?>

          </div>
          <?php endforeach; ?>
      </div>

    <?php else: ?>

      <p class="text-center small-text mt-5" data-translate="tab5store-7">Anda belum memiliki postingan.</p>

    <?php endif; ?>
  </div>

  <!-- SECTION SHOP -->

  <div class="section-store-shop">
    <div class="row gx-0">

      <?php if (count($shop_product) > 0) : ?>

        <?php foreach ($shop_product as $product) : ?>

        <div class="col-6">

            <?php $product_image = explode('|', $product['THUMB_ID']); ?>

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
                <img src="../images/<?= $product_image_video ?>" class="store-shop" style="border-radius: 10px">
              <?php else: ?>
                <video src="../images/<?= $product_image[0] ?>" autoplay muted loop style="object-fit: cover; object-position: center; margin-bottom: -10px; border-radius: 10px" type="video/mp4" class="store-shop"></video>
              <?php endif; ?>

            <?php else: ?>

              <img src="../images/<?= $product_image[$i] ?>" class="store-shop" style="border-radius: 10px">

            <?php endif; ?>

            <img src="../assets/img/tab5/Add-to-Cart.png" class="store-add-to-cart">
        </div>

        <?php endforeach; ?>

      <?php else: ?>

        <p class="text-center small-text mt-5" data-translate="tab5store-8">Anda belum memiliki produk.</p>

      <?php endif; ?>

    </div>
  </div>

  <!-- SECTION REELS -->

  <div class="section-store-reels">

    <?php if (mysqli_num_rows($shop_reels)) : ?>

      <div class="row gx-0">

      <?php foreach ($shop_reels as $reels): ?>

          <div class="col-6" onclick="openReelsModal('<?= $reels['URL'] ?>','<?= $reels['ID'] ?>')" style="width: 50%; height: 260px">
            <img src="../images/<?= $reels['COVER_ID'] ?>" class="store-reels" style=" object-fit: cover; object-position: center; border-radius: 10px">
            <img src="../assets/img/tab5/Eye-Watchers-(White).png" class="store-small-eye"> 
            <span class="store-reels-views"><b><?= $reels['VIEWS'] ?></b></span>
            <img src="../assets/img/tab5/Promo-Coupon.png" class="store-small-coupon">
            <div class="row store-reels-desc">
              <div>
                <img src="../images/<?= $shop_data["THUMB_ID"] ?>" height="20px" class="store-reels-thumbnail rounded-pill">
              </div>
              <div style="font-size: 10px;">
                <b><?= $reels['TITLE'] ?></b>
              </div>
              <div class="smallest-text"><?= substr_replace($reels['DESCRIPTION'], "...", 30); ?></div>
            </div>
          </div>

        <?php endforeach; ?>
        
      </div>

    <?php else: ?>

      <p class="text-center small-text mt-5" data-translate="tab5store-9">Anda belum memiliki reels.</p>

    <?php endif; ?>
  </div>

  <!-- Modal -->

  <!-- Dinyalakan Jika Ada Data Backend -->

  <div class="modal fade" id="successPostModal" tabindex="-1" aria-labelledby="successPostModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
      <div class="modal-content" style="height: 100%">
        <div class="row d-flex justify-content-center">
          <hr class="shop-modal-line">
        </div>
        <div class="modal-body text-center">
          <img src="../assets/img/tab5/Congratulations-Success.png" class="success-ads-image">
          <div class="text-purple"><b data-translate="tab5store-10">Success!</b></div>
          <div class="small-text text-grey" data-translate="tab5store-11">Your post has been successfuly uploaded.</div>
          <div class="btn-continue-ads" data-bs-dismiss="modal" data-translate="tab5store-12">Continue</div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL POST ZOOM -->

  <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <img src="" id="post_zoom" width="100%" height="100%">
          </div>
        </div>
    </div>
  </div>

  <div class="modal fade" id="postModalVideo" tabindex="-1" aria-labelledby="postModalVideo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <video src="" id="post_zoom_video" autoplay loop width="100%" height="100%"></video>
          </div>
        </div>
    </div>
  </div>

  <!-- MODAL REELS ZOOM -->

  <div class="modal fade" id="reelsModal" tabindex="-1" aria-labelledby="reelsModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <video src="" autoplay loop width="100%" height="100%" id="video_reels">
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

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

		if (localStorage.lang == 1){
      $('#query').attr('placeholder','Pencarian');
    }else if(localStorage.lang == 0){
      $('#query').attr('placeholder','Search');
    }

    $('body').show();
	});

  // SHOW MODAL AFTER SUCCESS UPLOAD LISTING

  <?php

  if ($_GET['success'] =='true'){

    echo('$(function() {
      $("#successPostModal").modal("show");
      });');
    }

    // FOR NOT DUPLICATE MODAL

    echo("history.pushState(null, null, '/nexilis/pages/tab5-store');");

  ?>

  // SCRIPT SHOW/HIDE 3 SUB MENU

  $(".section-store-shop").hide();
  $(".section-store-reels").hide();

  $(".store-nav-single-left").click(function(){
    $(".section-store-post").show();
    $(".section-store-shop").hide();
    $(".section-store-reels").hide();
    $(".store-nav-single-left").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-center").css({"border-bottom": "none"});
    $(".store-nav-single-right").css({"border-bottom": "none"});

    position = 1;
    localStorage.setItem("position", position);
  });

  $(".store-nav-single-center").click(function(){
    $(".section-store-shop").show();
    $(".section-store-post").hide();
    $(".section-store-reels").hide();
    $(".store-nav-single-center").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-left").css({"border-bottom": "none"});
    $(".store-nav-single-right").css({"border-bottom": "none"});

    position = 2;
    localStorage.setItem("position", position);
  });

  $(".store-nav-single-right").click(function(){
    $(".section-store-post").hide();
    $(".section-store-shop").hide();
    $(".section-store-reels").show();
    $(".store-nav-single-right").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-center").css({"border-bottom": "none"});
    $(".store-nav-single-left").css({"border-bottom": "none"});

    position = 3;
    localStorage.setItem("position", position);
  });

  if (localStorage.getItem('position') == 2){
    $(".section-store-shop").show();
    $(".section-store-post").hide();
    $(".section-store-reels").hide();
    $(".store-nav-single-center").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-left").css({"border-bottom": "none"});
    $(".store-nav-single-right").css({"border-bottom": "none"});
  }

  if (localStorage.getItem('position') == 3){
    $(".section-store-post").hide();
    $(".section-store-shop").hide();
    $(".section-store-reels").show();
    $(".store-nav-single-right").css({"border-bottom": "2px solid #000000"});
    $(".store-nav-single-center").css({"border-bottom": "none"});
    $(".store-nav-single-left").css({"border-bottom": "none"});
  }

  // RESET UPLOAD POST DATA

	window.localStorage.removeItem('caption_post');
	window.localStorage.removeItem('hashtag_post');
	window.localStorage.removeItem('location_post');
	window.localStorage.removeItem('tagged_post');
	window.localStorage.removeItem('tagged_post_name');
	window.localStorage.removeItem('tagged_media');

  // FUNCTION OPEN POST MODAL & REELS

  function openPostModal(link){
    $('#postModal').modal('show');
    $('#post_zoom').attr('src','../images/'+link);
  }

  function openPostModalVideo(link){
    $('#postModalVideo').modal('show');
    $('#post_zoom_video').attr('src','../images/'+link);
  }

  function openReelsModal(link, id){
    $('#reelsModal').modal('show');
    $('#video_reels').attr('src','../images/'+link);

    // INSERT INTO TABLE VIEWS

    var formData = new FormData();
    formData.append('reels_id', id);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function (){

    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
      console.log(xmlHttp.responseText);
      if(xmlHttp.responseText=="Berhasil"){
        console.log("Berhasil");
      }else{
        console.log("Gagal nih");
      }
    }
  }

    xmlHttp.open("post", "../logics/tab5/insert_reels_views");
    xmlHttp.send(formData);
  }

</script>
</html>