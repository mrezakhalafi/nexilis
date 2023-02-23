<?php

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();
    session_start();
    
    // GET ORDER ID

    $id_order = $_GET['id'];
    $id_user = $_SESSION["user_f_pin"];

    // CEK ID ORDER

    if (!isset($id_order)) {
        die("ID Order Tidak Diset.");
    }

    // SELECT PRODUCT FROM PAYMENT

    $receiptOrder = [];
    
    $query = $dbconn->prepare("SELECT * FROM PURCHASE LEFT JOIN PRODUCT ON PURCHASE.PRODUCT_ID = 
                                PRODUCT.CODE WHERE TRANSACTION_ID='".$id_order."' AND PURCHASE.PRODUCT_ID IS NOT NULL");
    $query->execute();
    $receiptOrderPurchase = $query->get_result();
    $query->close();

    while ($row = $receiptOrderPurchase->fetch_array(MYSQLI_ASSOC))
    {
      $receiptOrder[] = $row;
    }

    $query = $dbconn->prepare("SELECT PURCHASE.*, POST.THUMB_ID, POST.TITLE AS NAME FROM PURCHASE LEFT JOIN POST ON PURCHASE.POST_ID = 
                                POST.POST_ID WHERE TRANSACTION_ID='".$id_order."' AND PURCHASE.POST_ID IS NOT NULL");
    $query->execute();
    $receiptOrderPost = $query->get_result();
    $query->close();

    while ($row = $receiptOrderPost->fetch_array(MYSQLI_ASSOC))
    {
        $receiptOrder[] = $row;
    }

    // COUNT TOTAL PRICE

    $total_price = 0;
    $total_amount = 0;
    $delivery = 8000;

    foreach ($receiptOrder as $merchant){
        $total_price = $total_price + ($merchant['PRICE'] * $merchant['AMOUNT']);
        $total_amount = $total_amount + $merchant['AMOUNT'];
        $merchant_code = $merchant['MERCHANT_ID'];
    }

    // SELECT STORE IDENTITY FROM PURCHASE

    $query = $dbconn->prepare("SELECT * FROM SHOP WHERE CODE ='".$merchant_code."'");
    $query->execute();
    $shopData = $query->get_result()->fetch_assoc();
    $query->close();

    // FOR DOT PROCESSING

    foreach ($receiptOrder as $dot){
        $process_dot = $dot['STATUS'];
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
  <script src="https://kit.fontawesome.com/7d7a80b22c.js" crossorigin="anonymous"></script>
</head>

<body class="bg-white-background" style="display:none">
    <img src="../assets/img/lbackground_12.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

    <!-- NAVBAR -->

    <nav class="navbar navbar-light" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>">
        <div class="container">
            <a href="tab5-orders.php?src=receipt">
                <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
            </a>
            <p class="navbar-title" data-translate="tab5receipt-1" style="color: #000000">Receipt</p>
            <div class="navbar-brand pt-2 navbar-brand-slot">
                <img class="navbar-img-slot">
            </div>
        </div>
    </nav>

    <!-- SECTION RECEIPT PROGRESS BAR -->

    <!-- SECTION RECEIPT PROGRESS BAR -->

    <div class="section-receipt">
        <div class="progress-receipt">
            <div class="row gx-0">
                <div class="col-3 col-md-3 col-lg-3 text-center">
                    <?php if (($process_dot == 1) || ($process_dot == 0)): ?>
                        <div class="orange-dot"></div>
                    <?php elseif ($process_dot == 2): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 3): ?>
                        <div class="grey-dot"></div>                       
                    <?php elseif ($process_dot == 4): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 5): ?>
                        <div class="grey-dot" style="background-color: darkred; border: 1px solid darkred"></div>
                    <?php endif; ?>
                    <hr class="vertical-process">
                    <div class="receipt-progress-text" data-translate="tab5receipt-2">Processing</div>
                </div>
                <div class="col-3 col-md-3 col-lg-3 text-center">
                    <?php if (($process_dot == 1) || ($process_dot == 0)): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 2): ?>
                        <div class="orange-dot"></div>
                    <?php elseif ($process_dot == 3): ?>
                        <div class="grey-dot"></div>                       
                    <?php elseif ($process_dot == 4): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 5): ?>
                        <div class="grey-dot"></div>
                    <?php endif; ?>
                <hr class="vertical-process">
                    <div class="receipt-progress-text" data-translate="tab5receipt-3">Shipped</div>
                </div>
                <div class="col-3 col-md-3 col-lg-3 text-center">
                    <?php if (($process_dot == 1) || ($process_dot == 0)): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 2): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 3): ?>
                        <div class="orange-dot"></div>                       
                    <?php elseif ($process_dot == 4): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 5): ?>
                        <div class="grey-dot"></div>
                    <?php endif; ?>
                    <hr class="vertical-process">
                    <div class="receipt-progress-text" data-translate="tab5receipt-4">Out for Delivery</div>
                </div>
                <div class="col-3 col-md-3 col-lg-3 text-center">
                    <?php if (($process_dot == 1) || ($process_dot == 0)): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 2): ?>
                        <div class="grey-dot"></div>
                    <?php elseif ($process_dot == 3): ?>
                        <div class="grey-dot"></div>                       
                    <?php elseif ($process_dot == 4): ?>
                        <div class="orange-dot"></div>
                    <?php elseif ($process_dot == 5): ?>
                        <div class="grey-dot"></div>                       
                    <?php endif; ?>
                    <div class="receipt-progress-text" data-translate="tab5receipt-5">Delivered</div>
                </div>
            </div>
        </div>

        <!-- SECTION RECEIPT ORDERS -->

        <div class="section-orders">
            <div class="container orders-list">
                <div class="row orders-header">

                    <?php if ($shopData['IS_VERIFIED'] == 1): ?>
                    <div class="col-6 col-md-8 col-lg-10">
                        <img src="../assets/img/tab5/verified-black.png" class="verified" style="width:20px">
                        <div class="receipt-shop-title" style="margin-left:24px; margin-top:-20px">
                            <b><?= $shopData['NAME'] ?></b>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-6 col-md-6 col-lg-9" style="padding-left: 60px">
                        <div class="receipt-shop-title">
                            <b><?= $shopData['NAME'] ?></b>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="tab3-profile.php?store_id=<?= $shopData['CODE'] ?>&f_pin=<?= $id_user ?>" class="d-flex justify-content-end">
                            <img src="../assets/img/tab5/store-black.png" class="view-store-image">
                            <span class="smallest-text m-1 view-store-receipt">
                                <b data-translate="tab5receipt-6">View store</b>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- LOOP PRODUCT FROM PAYMENT -->

                <?php foreach ($receiptOrder as $singleProduct): 

                    $product_image = explode('|', $singleProduct['THUMB_ID']);
                    $product_price = $singleProduct['PRICE'] * $singleProduct['AMOUNT'];

                ?>

                <div class="row mt-3">
                    <div class="col-3 col-md-3 col-lg-2">

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
                            <img src="../images/<?= $product_image_video ?>" class="receipt-item-image" style="border-radius: 10px">
                        <?php else: ?>
                            <video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="receipt-item-image"></video>
                        <?php endif; ?>

                    <?php else: ?>

                        <img src="../images/<?= $product_image[$i] ?>" class="receipt-item-image" style="border-radius: 10px">

                    <?php endif; ?>

                    </div>
                    <div class="col-9 col-md-9 col-lg-10">
                        <div class="receipt-item-title">
                            <b><?= $singleProduct['NAME'] ?></b>
                        </div>
                        <div class="receipt-item-price">Rp <?= number_format($product_price,0,",",",") ?></div>
                        <input type="text" id="receipt-quantity" readonly class="text-center" size="1" value="<?= $singleProduct['AMOUNT'] ?>">
                    </div>
                </div>

                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- SECTION PAYMENT -->

    <div class="section-payment payment-desc">
        <div class="payment-for-total">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item" data-translate="tab5receipt-7">Total</div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item float-end">
                            Rp <?= number_format($total_price,0,",",",") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="small-text payment-desc-item" data-translate="tab5receipt-8">Payment method</div>
                </div>
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="small-text payment-desc-item float-end">QPay</div>
                </div>
            </div>
        </div>
        <div class="payment-desc-2">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item-2">
                            <span data-translate="tab5receipt-9">Sub-total</span> ( <?= $total_amount ?> <span data-translate="tab5receipt-10">items</span> )
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item-2 float-end">
                            Rp <?= number_format($total_price,0,",",",") ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item" data-translate="tab5receipt-11">Delivery</div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item float-end">
                        Rp <?= number_format($delivery,0,",",",") ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="total-receipt">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="small-text payment-desc-item" data-translate="tab5receipt-12">
                            Total (Tax included)
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <b class="payment-desc-item receipt-price float-end">
                            Rp <?= number_format($total_price+$delivery,0,",",",") ?>
                        </b>            
                    </div>
                </div>
            </div>
        </div>

        <?php if ($process_dot == 3): ?>
            <form action="../logics/tab5/product_received" method="POST">
                <input type="hidden" id="transaction_id" name="transaction_id" value="<?= $id_order ?>">
                <div class="d-flex justify-content-center">
                    <button class="btn-help-with-order" data-translate="tab5receipt-14">Product Received</button>
                </div>
            </form>
        <?php endif; ?>

        <div class="d-flex justify-content-center" style="margin-bottom: 60px">
            <button class="btn-help-with-order" data-translate="tab5receipt-13">Help with Order</button>
        </div>
    </div>

    <!-- STAR MODAL -->

    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center mt-2">
                    <div class="row">
                        <p class="small-text" data-translate="tab5receipt-15">Beri rating untuk produk ini.</p>
                        <div class="col">
                        <i class="fas fa-star star-1" style="color: #8a898b; font-size:35px"></i>
                        <i class="fas fa-star star-2" style="color: #8a898b; font-size:35px"></i>
                        <i class="fas fa-star star-3" style="color: #8a898b; font-size:35px"></i>
                        <i class="fas fa-star star-4" style="color: #8a898b; font-size:35px"></i>
                        <i class="fas fa-star star-5" style="color: #8a898b; font-size:35px"></i>
                        </div>
                    </div>
                    <div class="btn-continue-ads" data-bs-dismiss="modal" data-translate="tab5receipt-16">Kirimkan</div>
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

  // SHOW MODAL AFTER SUCCESS UPLOAD LISTING

  <?php

  if ($_GET['success'] =='true'){

    echo('$(function() {
        $("#ratingModal").modal("show");
        });');

    // FOR NOT DUPLICATE MODAL

    echo("history.pushState(null, null, '/nexilis/pages/tab5-receipt?id=".$id_order."');");
    
  }

  ?>

  // FOR STAR ANIMATION

  $('.star-1').click(function(){
    $('.star-1').css('color','#FFA03E');
    $('.star-2').css('color','#8a898b');
    $('.star-3').css('color','#8a898b');
    $('.star-4').css('color','#8a898b');
    $('.star-5').css('color','#8a898b');
  });

  $('.star-2').click(function(){
    $('.star-1').css('color','#FFA03E');
    $('.star-2').css('color','#FFA03E');
    $('.star-3').css('color','#8a898b');
    $('.star-4').css('color','#8a898b');
    $('.star-5').css('color','#8a898b');
  });

  $('.star-3').click(function(){
    $('.star-1').css('color','#FFA03E');
    $('.star-2').css('color','#FFA03E');
    $('.star-3').css('color','#FFA03E');
    $('.star-4').css('color','#8a898b');
    $('.star-5').css('color','#8a898b');
  });

  $('.star-4').click(function(){
    $('.star-1').css('color','#FFA03E');
    $('.star-2').css('color','#FFA03E');
    $('.star-3').css('color','#FFA03E');
    $('.star-4').css('color','#FFA03E');
    $('.star-5').css('color','#8a898b');
  });

  $('.star-5').click(function(){
    $('.star-1').css('color','#FFA03E');
    $('.star-2').css('color','#FFA03E');
    $('.star-3').css('color','#FFA03E');
    $('.star-4').css('color','#FFA03E');
    $('.star-5').css('color','#FFA03E');
  });

</script>
</html>