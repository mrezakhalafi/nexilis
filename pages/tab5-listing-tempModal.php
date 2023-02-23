<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();

	//GET ID SHOP

	session_start();
	$id_shop = $_SESSION["id_shop"];

	if (!isset($id_shop)) {
		die("ID Shop Tidak Diset.");
	}

	//SELECT SHOP DATA

	$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE SHOP_CODE = '$id_shop'");
	$query->execute();
	$listing_data = $query->get_result();
	$query->close();

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Project</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

<body class="bg-white-background">

<!-- NAVBAR -->

<nav class="navbar navbar-light navbar-shop-manager">
  <div class="container">
    <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
    </a>
    <p class="navbar-title-2 navbar-listing-title">Your Listing</p>
    <div class="navbar-brand pt-2 navbar-brand-listing">
      <a href="tab5-upload-listing.php">
        <img src="../assets/img/tab5/Add-(Purple).png" class="navbar-listing-add">
      </a>
        <img src="../assets/img/tab5/Search-(Purple).png" class="navbar-listing-search">
    </div>
    </div>
 </div>
</nav>

<!-- SECTION LISTING SWITCH -->

<div class="section-listing-nav-switch">
  <div class="container">
    <div class="row text-center">
      <div class="col-6 col-md-6 col-lg-6 listing-nav-single-left">
        <p class="small-text">
          <b>Active</b>
        </p>
      </div>
      <div class="col-6 col-md-6 col-lg-6 listing-nav-single-right">
        <p class="small-text">
          <b>Archived</b>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- SECTION LISTING PRODUCT -->

<div class="section-listing-active">
  <div class="container">
    <div class="row gx-0">

      <!-- CHECK IF SHOP HAVE LISTING -->

      <?php if(mysqli_num_rows($listing_data)>0) : ?>

      <!-- LOOP SHOP LISTING -->

      <?php foreach ($listing_data as $resultListing) : ?>

      <div class="col-6 col-md-6 col-md-6 col-lg-6 shadow-sm col-listing-product">
        <div class="single-listing-product">
          <label for="myCheck<?= $resultListing['ID'] ?>">
            <div class="form-check form-check-listing">
              <input class="form-check-input check-form-input-listing" type="checkbox" value="" id="myCheck<?= $resultListing['ID'] ?>">

              <?php if (strpos($resultListing['THUMB_ID'],"http://") !== false): ?> 
              
              <img src="<?= $resultListing['THUMB_ID'] ?>" class="listing-image">
              
              <?php else: ?>

              <img src="../../palio_browser/images/<?= $resultListing['THUMB_ID'] ?>" class="listing-image">

              <?php endif; ?>

              <img src="../assets/img/tab5/Settings-(White).png" class="listing-product-settings">
              <div class="row listing-product-desc">
                <span class="small-text listing-product-title">
                  <b><?= $resultListing["NAME"] ?></b>
                </span>
                <div class="row listing-product-desc-row">
                  <div class="col-6 col-md-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                    <span class="smallest-text text-grey">
                      <?= $resultListing["QUANTITY"] ?> in stock
                    </span>
                  </div>
                  <div class="col-6 col-md-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                    <span class="smallest-text text-grey">
                    Rp <?= number_format($resultListing['PRICE'],0,",",",") ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </label>
        </div>
      </div>

      <?php endforeach; ?>
      <?php else: ?>

      <p class="text-center small-text mt-5">Anda belum memiliki produk.</p>

      <?php endif; ?>

      </div>
    </div> 
  </div>
</div>

<div class="section-listing-archived">
  <div class="container">
    <div class="row gx-0">
      <div class="col-6 col-md-6 col-lg-6 shadow-sm col-listing-product">
        <div class="single-listing-product">
          <label for="myCheck7">
            <div class="form-check form-check-listing">
              <input class="form-check-input check-form-input-listing" type="checkbox" value="" id="myCheck7">
              <img src="../assets/img/tab5/product-3.jpg" class="listing-image">
              <img src="../assets/img/tab5/Settings-(White).png" class="listing-product-settings">
              <div class="row listing-product-desc">
                <span class="small-text listing-product-title">
                  <b>Rak Sudut 5 Tingkat</b>
                </span>
                <div class="row listing-product-desc-row">
                  <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                    <span class="smallest-text text-grey">50 in stock</span>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                    <span class="smallest-text text-grey">Rp 1,625,000</span>
                  </div>
                </div>
              </div>
            </div>
          </label>
        </div>
      </div>
      <div class="col-6 col-md-6 col-lg-6 shadow-sm col-listing-product">
        <div class="single-listing-product">
          <label for="myCheck8">
            <div class="form-check form-check-listing">
              <input class="form-check-input check-form-input-listing" type="checkbox" value="" id="myCheck8">
              <img src="../assets/img/tab5/product-4.jpg" class="listing-image">
              <img src="../assets/img/tab5/Settings-(White).png" class="listing-product-settings">
              <div class="row listing-product-desc">
                <span class="small-text listing-product-title">
                  <b>Kursi Kakek</b>
              </span>
                <div class="row listing-product-desc-row">
                  <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-start">
                    <span class="smallest-text text-grey">50 in stock</span>
                  </div>
                  <div class="col-6 col-md-6 col-lg-6 gx-0 d-flex justify-content-end">
                    <span class="smallest-text text-grey">Rp 855,000</span>
                  </div>
                </div>
              </div>
            </div>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

<!-- Dinyalakan Jika Ada Data Backend -->

<div class="modal fade" id="congratsAdsModal" tabindex="-1" aria-labelledby="successListingModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
	  <div class="modal-content">
		<div class="row d-flex justify-content-center">
			<hr class="shop-modal-line">
		</div>
		<div class="modal-body text-center">
      <img src="../assets/img/tab5/Congratulations-Success.png" class="success-ads-image">
      <div class="text-purple"><b>Success!</b></div>
      <div class="small-text text-grey">Your listing has been successfuly updated.</div>
      <div class="btn-continue-ads" data-bs-dismiss="modal">Continue</div>
		</div>
	</div>
</div>

<!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script>

  // NAV SWITCH SCRIPT

  $(".section-listing-archived").hide();

  $(".listing-nav-single-right").click(function() {
    $(".section-listing-archived").show();
    $(".section-listing-active").hide();
    $(".listing-nav-single-right").css({"border-bottom": "2px solid #000000"});
    $(".listing-nav-single-left").css({"border-bottom": "none"});
  });

  $(".listing-nav-single-left").click(function() {
    $(".section-listing-archived").hide();
    $(".section-listing-active").show();
    $(".listing-nav-single-left").css({"border-bottom": "2px solid #000000"});
    $(".listing-nav-single-right").css({"border-bottom": "none"});
  });

</script>

<script>

  $(document).ready(function() {
    $('#congratsAdsModal').modal('show');
  });

</script>

</html>
