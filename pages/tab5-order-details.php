<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

	// ID TRANSACTION CHECK

	$transaction_id = $_GET["transaction_id"];

	if (!isset($transaction_id)) {
		die("ID Transaction Tidak Diset.");
	}

	// SELECT ORDER FROM PURCHASE
	
	$query = $dbconn->prepare("SELECT * FROM PURCHASE LEFT JOIN USER_LIST ON PURCHASE.FPIN = 
                              USER_LIST.F_PIN LEFT JOIN PRODUCT ON PURCHASE.PRODUCT_ID = 
                              PRODUCT.CODE LEFT JOIN USER_LIST_EXTENDED ON USER_LIST.F_PIN = 
                              USER_LIST_EXTENDED.F_PIN WHERE TRANSACTION_ID = '".$transaction_id."' AND PURCHASE.PRODUCT_ID IS NOT NULL");
	$query->execute();
	$detailOrdersPurchase = $query->get_result();
	$query->close();

  $detailOrder = array();
  while ($group = $detailOrdersPurchase->fetch_assoc()) {
    $detailOrder[] = $group;
  };

  $query = $dbconn->prepare("SELECT PURCHASE.*, POST.THUMB_ID, POST.TITLE AS NAME FROM PURCHASE LEFT JOIN USER_LIST ON PURCHASE.FPIN = 
                              USER_LIST.F_PIN LEFT JOIN POST ON PURCHASE.POST_ID = 
                              POST.POST_ID LEFT JOIN USER_LIST_EXTENDED ON USER_LIST.F_PIN = 
                              USER_LIST_EXTENDED.F_PIN WHERE TRANSACTION_ID = '".$transaction_id."' AND PURCHASE.POST_ID IS NOT NULL");
  $query->execute();
  $detailOrderPost = $query->get_result();
  $query->close();

  while ($group = $detailOrderPost->fetch_assoc()) {
    $detailOrder[] = $group;
  };

  // FORMAT TRANSACTION DATE

  $order_date = date_create($detailOrder['CREATED_AT']);

  // COUNT TOTAL PRICE

  $total_price = 0;
  $total_amount = 0;

  foreach($detailOrder as $data){
    $total = $total + ($data['PRICE'] * $data['AMOUNT']);
    $total_amount = $total_amount + $data['AMOUNT'];
  }

  $delivery = 8000;

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
  <style>
    .form-check-input {
      border: 1px solid #6945A5 !important;
    }
  </style>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_6.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-your-orders.php?src=od">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5orderdetails-1">Order Details</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img src="" class="navbar-brang-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION ORDER DETAILS PRODUCT -->

  <div class="order-details-navbar">
    <div class="container">
      <div class="row">
        <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-start">
          <p class="small-text text-grey"><span data-translate="tab5orderdetails-2">Order no.</span> <?= $detailOrder[0]['TRANSACTION_ID'] ?></p>
        </div>
        <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
          <p class="small-text text-grey"><?= date_format($order_date,"d F Y H:i"); ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="order-details-product">
    <div class="container">
      <div class="row">
        <p class="order-details-name">
          <b><?= $detailOrder[0]['FIRST_NAME'] ?></b>
        </p>

        <?php foreach ($detailOrder as $singleOrder) :

        $total_price = $singleOrder["PRICE"] * $singleOrder["AMOUNT"]; ?>
          
        <div class="row order-details-single-product">
          <div class="col-3 col-md-3 col-lg-3">

          <?php $product_image = explode('|', $singleOrder['THUMB_ID']); ?>

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
              <img src="<?= $product_image_video ?>" class="order-details-image" style="border-radius: 10px">
            <?php else: ?>
              <video src="<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="order-details-image"></video>
            <?php endif; ?>

          <?php else: ?>

            <img src="<?= $product_image[$i] ?>" class="order-details-image" style="border-radius: 10px">

          <?php endif; ?>

          </div>
          <div class="col-9 col-md-9 col-lg-9">
            <div class="small-text">
              <b><?= $singleOrder['NAME'] ?></b>
            </div>
            <div class="small-text text-grey">Rp <?= number_format($total_price,0,",",",") ?></div>
            <input type="text" id="receipt-quantity" readonly class="text-center" size="1" value="<?= $singleOrder['AMOUNT'] ?>">
          </div>
        </div>

        <?php endforeach; ?>

      </div>
    </div>
    <div class="order-details-product-total">
      <div class="container">
        <div class="row">
      <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-start">
        <p class="small-text" data-translate="tab5orderdetails-3">Total</p>
      </div>
      <div class="col-6 col-md-6 col-lg-6 d-flex justify-content-end">
        <p class="small-text">Rp <?= number_format($total,0,",",",") ?></p>
      </div>
    </div>
  </div>

  <div class="order-details-separator"></div>

  <!-- SECTION ORDER DETAILS DELIVERY ADDRESS -->

  <div class="section-delivery-address">
    <div class="container">
      <div class="row">
        <div class="col-6  col-md-6 col-lg-6 mb-3">
          <b class="order-details-title" data-translate="tab5orderdetails-4">Delivery Address</b>
        </div>
        <div class="col-6 col-md-6 col-lg-6">

          <?php if (($singleOrder['STATUS'] == 1) || ($singleOrder['STATUS'] == 0)): ?>
            <span class="float-end badge rounded-pill orange-badge text-light "><span class="small-text" data-translate="tab5orderdetails-5">Waiting to be Shipped</span>
          <?php elseif($singleOrder['STATUS'] == 2): ?>
            <span class="float-end badge rounded-pill orange-badge text-light "><span class="small-text" data-translate="tab5orderdetails-6">Shipped</span>
          <?php elseif($singleOrder['STATUS'] == 3): ?>
            <span class="float-end badge rounded-pill orange-badge text-light "><span class="small-text" data-translate="tab5orderdetails-7">Out for Delivery</span>
          <?php elseif($singleOrder['STATUS'] == 4): ?>
            <span class="float-end badge rounded-pill orange-badge text-light "><span class="small-text" data-translate="tab5orderdetails-8">Delivered</span>
          <?php elseif ($singleOrder['STATUS'] == 5): ?>
            <span class="float-end badge rounded-pill orange-badge text-light " style="background-color: darkred"><span class="small-text" data-translate="tab5orderdetails-20">Declined</span>
          <?php endif; ?>

        </div>
        <div class="row">
          <div class="col-6 col-md-6 col-lg-6">
            <b class="small-text"><?= $detailOrder[0]['FIRST_NAME'] ?></b>
            <div class="small-text text-grey">

              <?php if (isset($detailOrder[0]['ADDRESS'])): ?>
                <?= $detailOrder[0]['ADDRESS'] ?>
              <?php else: ?>
                Alamat lengkap user ini kosong pada database.
              <?php endif; ?>

            </div>
            <div class="small-text text-grey">

            <?php if (isset($detailOrder[0]['MSISDN'])): ?>
                <?= $detailOrder[0]['MSISDN'] ?>
              <?php else: ?>
                Nomor telepon user ini kosong pada database.
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="order-details-separator"></div>

  <!-- SECTION ORDER DETAILS DELIVERY METHOD -->

  <div class="section-delivery-method">
    <div class="container">
      <div class="order-details-title mb-2">
        <b data-translate="tab5orderdetails-9">Delivery Method</b>
      </div>
      <b class="small-text">Anteraja</b>
    </div>
  </div>
  <div class="order-details-separator"></div>

  <!-- SECTION ORDER DETAILS PAYMENT METHOD -->

  <div class="section-order-details-payment-method">
    <div class="order-details-voucher-qiosk">
      <div class="container">  
        <div class="row">
            <p class="small-text">Voucher NexilisSport</p>
          </div>
        </div>
      </div>
    </div>
    <div class="order-details-pm">
      <div class="container">
        <div class="row order-details-title">
          <div class="col-6 col-md-6 col-lg-6 mt-3 d-flex justify-content-start">
            <p><b data-translate="tab5orderdetails-10">Payment Method</b></p>
          </div>
          <div class="col-6 col-md-6 col-lg-6 mt-3 d-flex justify-content-end">
            <p><b>QPay ></b></p>
          </div>
        </div>
      </div>
    </div>
    <div class="order-details-payment">
      <div class="container">
        <div class="row">
          <div class="col-6 col-md-6 col-lg-6">
            <div class="order-details-small-text"><span data-translate="tab5orderdetails-11">Sub-total</span> (<?= $total_amount ?> <span data-translate="tab5orderdetails-12">items</span>)</div>
            <div class="order-details-small-text" data-translate="tab5orderdetails-13">Delivery</div>
            <div class="order-details-small-text" data-translate="tab5orderdetails-14">Total (Tax Included)</div>
          </div>
          <div class="col-6 col-md-6 col-lg-6">
            <div class="order-details-small-text d-flex justify-content-end">
              Rp <?= number_format($total,0,",",",") ?>
            </div>
            <div class="order-details-small-text d-flex justify-content-end">
              Rp <?= number_format($delivery,0,",",",") ?>
            </div>
            <div class="d-flex justify-content-end">
              <b>Rp <?= number_format($total+$delivery,0,",",",") ?></b>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION ORDER DETAILS BUTTON -->

  <div class="section-order-details-button">
    <div class="container">
      <div class="row">
        <div class="col-6 col-md-6 col-lg-6">
          <button class="btn-order-details" style="border-radius: 10px" data-translate="tab5orderdetails-15">Cancel Order</button>
        </div>
        <div class="col-6 col-md-6 col-lg-6">
          <button class="btn-order-details" style="border-radius: 10px" data-translate="tab5orderdetails-16">Contact Buyer</button>
        </div>
      </div>
    </div>
  </div>

  <?php if ($detailOrder[0]['STATUS'] == 0): ?>
    <div class="row text-center fixed-bottom gx-0">
      <div class="col-6 col-md-6 col-lg-6">
        <form action="../logics/tab5/accept_orders" method="POST">
          <input id="transaction_id" type="hidden" name="transaction_id" value="<?= $transaction_id ?>">
          <button class="btn-arange-shipment" type="submit" style="border: none; background-color: darkgreen" type="submit" data-translate="tab5orderdetails-21">Accept</button>
        </form>
      </div>
      <div class="col-6 col-md-6 col-lg-6">
        <button class="btn-arange-shipment" onclick="openModal()" style="border: none; background-color: darkred" type="submit" data-translate="tab5orderdetails-22">Decline</button>
      </div>
    </div>
  <?php elseif ($detailOrder[0]['STATUS'] == 1): ?>
    <form action="../logics/tab5/arrange_shipment" method="POST">
      <input type="hidden" name="transaction_id" value="<?= $detailOrder[0]['TRANSACTION_ID'] ?>">
      <div class="row text-center fixed-bottom">
        <button class="btn-arange-shipment" style="border: none" type="submit" data-translate="tab5orderdetails-17">Arrange Shipment</button>
      </div>
    </form>
  <?php elseif($detailOrder[0]['STATUS'] == 2): ?>
    <form action="../logics/tab5/delivering_product" method="POST">
    <input type="hidden" name="transaction_id" value="<?= $detailOrder[0]['TRANSACTION_ID'] ?>">
    <div class="row text-center fixed-bottom">
      <button class="btn-arange-shipment" style="border: none" type="submit" data-translate="tab5orderdetails-18">Delivery</button>
    </div>
  </form>
  <?php elseif ($detailOrder[0]['STATUS'] == 3): ?>
    <div class="row text-center fixed-bottom">
      <button class="btn-arange-shipment-2" style="border: none" type="button" disabled data-translate="tab5orderdetails-18">Delivery</button>
    </div>
  <?php elseif ($detailOrder[0]['STATUS'] == 4): ?>
    <div class="row text-center fixed-bottom">
      <button class="btn-arange-shipment-2" style="border: none" type="button" disabled data-translate="tab5orderdetails-19">Delivered</button>
    </div>
  <?php endif; ?>


  <!-- DECLINE MODAL -->

  <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
      <div class="modal-content" style="height: 100%">
      <div class="row d-flex justify-content-center">
        <hr class="shop-modal-line">
      </div>
      <div class="modal-body">
        <div class="container small-text">
          <p style="font-size: 12px" data-translate="tab5yourorders-18">Decline Reason: </p>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option1" checked>
            <label class="form-check-label" for="option1" style="margin-left: 5px" data-translate="tab5yourorders-12">
              Out of Stock
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option2">
            <label class="form-check-label" for="option2" style="margin-left: 5px" data-translate="tab5yourorders-13">
              Buyer requested due to change of mind
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option3">
            <label class="form-check-label" for="option3" style="margin-left: 5px" data-translate="tab5yourorders-14">
              Buyer requested due to change in payment method/product
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option4">
            <label class="form-check-label" for="option4" style="margin-left: 5px" data-translate="tab5yourorders-15">
              Buyer unreachable for order validation
            </label>
          </div>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="option5">
            <label class="form-check-label" for="option5" style="margin-left: 5px" data-translate="tab5yourorders-16">
              Buyer shipping address is incorrect/incomplete
            </label>
          </div>
          <div class="btn-continue-ads text-center" onclick="declineOrders()" data-translate="tab5yourorders-17">Decline Orders</div>
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
    $('body').show();
	});

   // OPEN MODAL AND DISABLE A HREF

   function openModal(){
    $('#declineModal').modal("show");
    
    $("a").click(function() {
      return false;
    });
  }

  // WHEN CLOSE MODAL ACTIVE A HREF

  $('#declineModal').on('hidden.bs.modal', function () {
    $("a").prop("onclick", null).off("click");
  })

  // FUNCTION DECLINE ORDERS

  function declineOrders(){

    var formData = new FormData();
    var transaction_id = $('#transaction_id').val();

    formData.append('transaction_id', transaction_id);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function (){

      if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
        console.log(xmlHttp.responseText);
        if(xmlHttp.responseText=="Berhasil"){
        window.location.href = "/nexilis/pages/tab5-your-orders";
        }else{
          console.log("Gagal nih");
        }
      }
    }

    xmlHttp.open("post", "../logics/tab5/decline_orders");
    xmlHttp.send(formData);
  }

</script>
</html>