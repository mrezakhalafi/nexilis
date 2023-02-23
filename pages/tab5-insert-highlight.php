<?php

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();
session_start();

// ID SHOP GET

if (!isset($_SESSION['user_f_pin'])) {
    $id_user = $_GET['f_pin'];
    $_SESSION['user_f_pin'] = $id_user;
} else {
    $id_user = $_SESSION["user_f_pin"];
}

// CHECK USER

if (!isset($id_user)) {
    die("ID User Tidak Diset.");
}

// SELECT ORDER FROM PURCHASE

$userOrders = [];

$query = $dbconn->prepare("SELECT PURCHASE.*, SHOP.*, PRODUCT.THUMB_ID, PRODUCT.CODE AS 
                                P_CODE FROM PURCHASE LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = 
                                SHOP.CODE LEFT JOIN PRODUCT ON PURCHASE.PRODUCT_ID = 
                                PRODUCT.CODE WHERE FPIN ='" . $id_user . "' AND PURCHASE.PRODUCT_ID IS NOT NULL ORDER BY 
                                PURCHASE.CREATED_AT DESC");
$query->execute();
$userOrdersPurchase = $query->get_result();
$query->close();

while ($row = $userOrdersPurchase->fetch_array(MYSQLI_ASSOC)) {
    $userOrders[] = $row;
}

$query = $dbconn->prepare("SELECT PURCHASE.*, SHOP.*, POST.THUMB_ID, POST.POST_ID AS 
                                P_CODE FROM PURCHASE LEFT JOIN SHOP ON PURCHASE.MERCHANT_ID = 
                                SHOP.CODE LEFT JOIN POST ON PURCHASE.POST_ID = 
                                POST.POST_ID WHERE FPIN ='" . $id_user . "' AND PURCHASE.POST_ID IS NOT NULL ORDER BY 
                                PURCHASE.CREATED_AT DESC");
$query->execute();
$userOrdersPost = $query->get_result();
$query->close();

while ($row = $userOrdersPost->fetch_array(MYSQLI_ASSOC)) {
    $userOrders[] = $row;
}

// FOR DELIVERY PRICE

$delivery = 8000;

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
        /* FOR IMAGE ZOOMING */

        body {
            margin: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
            padding-right: 0px;
            position: fixed;
        }

        /* FOR RED DOT FORM */

        .form-group {
            position: relative;
        }

        .form-control:focus {
            background-color: #FAFAFF;
            box-shadow: none;
        }

        .palceholder {
            position: absolute;
            color: #797979;
            display: none;
        }

        .star {
            color: red;
            margin-left: -3px;
        }

        /* FOR FULL WIDTH MODAL */

        .modal-dialog {
            max-width: 100%;
            margin: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            margin-top: 10px;
        }
    </style>
</head>

<body class="bg-white-background" id="main-menu">
    <div id="header"></div>

    <script>
        if (localStorage.lang == 1) {
            document.getElementById("main-menu").style.visibility = "hidden";
        }
    </script>

    <input id="file-input-1" style="position: absolute; top:0; visibility: hidden" type="file" name="story_upload" onchange="loadFile(event)" />

    <!-- FOR STRETCH DELETE OBJECT-FIT COVER -->

    <label for="file-input-1">
        <img src="../assets/img/tab5/Add-(Grey)-small.png" id="media" style="display:block; width:100%; height:100%;
        object-fit: cover;">
    </label>

    <!-- <div id="input_draggable" style="position: fixed; padding: 20px; margin-bottom: 230px; width: 100%; height: 100%"> -->
    <textarea id="text_draggable" disabled type="text" rows="3" style="font-size: 25px; padding: 20px; margin-bottom: 175px; text-align: center; width: 100%; height: 150px; bottom: 0; position: fixed; border: none; outline: none; background-color: transparent; color: white"></textarea>
    <!-- </div> -->

    <a href="tab5-main">
        <img src="../assets/img/tab5/plus-black.png" id="close" style="width: 40px; height: 40px; position: absolute; top: 0; margin-top: 10px; margin-left: 10px; transform: rotate(45deg);">
    </a>

    <img src="../assets/img/tab5/aa-black.png" id="aa" onclick="insertText()" style="position: absolute; top: 0; margin-top: 13px; right: 0; margin-right: 20px" class="navbar-back-white">
    <img src="../assets/img/tab5/tagged-black.png" id="tagged" onclick="openModal()" style="position: absolute; top: 0; margin-top: 14px; right: 0; margin-right: 60px; width: 23px; height: 23px" class="navbar-back-white">
    <img src="../assets/img/tab5/sound-black.png" id="mute" onclick="muteVideo()" style="position: absolute; top: 0; margin-top: 14px; right: 0; margin-right: 95px; width: 32px; height: 23px" class="navbar-back-white">
    <img src="../assets/img/tab5/sound-red.png" id="unmute" onclick="unmuteVideo()" style="position: absolute; top: 0; margin-top: 14px; right: 0; margin-right: 95px; width: 32px; height: 23px; display: none" class="navbar-back-white">

    <div id="btn-place" style="z-index: 1030" class="fixed-bottom">
        <div class="row text-center" style="z-index: 1030">
            <button class="btn-app-notification" id="btn-1" style="border:none; z-index: 1030; background-color: grey" onclick="openModal()" data-translate="tab5inserthighlight-1">Add to Highlight</button>
            <button class="btn-app-notification" id="btn-2" style="border:none; display:none; z-index: 1030" data-translate="tab5inserthighlight-2">Submit</button>
        </div>
    </div>

    <!-- CREATE HIGHLIGHT MODAL -->

    <div class="modal fade" id="createHighlight" tabindex="-1" aria-labelledby="createHighlightLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
            <div class="modal-content" style="height: 100%; width: 100%;">
                <div class="row d-flex justify-content-center">
                    <hr class="shop-modal-line">
                </div>
                <div class="modal-body">
                    <div class="live-stream-title" style="border-bottom: none !important">
                        <div class="form-group">
                            <div class="palceholder live-stream-title-input">
                                <label for="name" class="title_placeholder" data-translate="tab5inserthighlight-3">Title</label>
                                <span class="star">*</span>
                            </div>
                            <input type="text" class="form-control live-stream-title-input" id="title" name="title" value="" required>
                        </div>
                    </div>
                    <div class="live-stream-desc">
                        <div class="form-group">
                            <div class="palceholder live-stream-desc-input">
                                <label for="desc" data-translate="tab5inserthighlight-4">Short description (Optional)</label>
                            </div>
                            <textarea class="upload-listing-input form-control" id="desc" rows="3" maxlength="200" name="desc"></textarea>
                            <div class="d-flex justify-content-end">
                                <span id="counter-word" class="smallest-text text-grey">0</span><span class="smallest-text text-grey">/200</span>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="collection_id" value="<?= $collection_code ?>">
                <div class="d-flex justify-content-center">
                    <button class="btn-edit-collection" onclick="openModal2()" data-translate="tab5inserthighlight-5">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT PRODUCT MODAL -->

    <div class="modal fade" id="recentProductModal" tabindex="-1" aria-labelledby="recentProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="align-items: flex-end;">
            <div class="modal-content" style="height: 100%; width: 100%">
                <div class="mod-header">
                    <div class="row d-flex justify-content-center">
                        <hr class="shop-modal-line">
                    </div>
                </div>
                <div class="modal-body">

                    <?php if (count($userOrders) > 0) : ?>

                        <!-- FOR LOOP SORTING DIFFERENT PURCHASE BUT SAME F_PIN -->

                        <?php

                        $arrayResult = array();

                        foreach ($userOrders as $singleOrder) {

                            if (!isset($arrayResult[$singleOrder['TRANSACTION_ID']])) {
                                $current = new stdClass();
                                $current->TRANSACTION_ID = $singleOrder['TRANSACTION_ID'];
                                $current->PRICE = $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
                                $current->AMOUNT = $singleOrder['AMOUNT'];
                                $current->F_PIN = $singleOrder['FPIN'];
                                $current->NAME = $singleOrder['NAME'];
                                $current->IS_VERIFIED = $singleOrder['IS_VERIFIED'];
                                $current->THUMB_ID = $singleOrder['THUMB_ID'];
                                $current->CREATED_AT = $singleOrder['CREATED_AT'];
                                $current->STATUS = $singleOrder['STATUS'];
                                $current->P_CODE = $singleOrder['P_CODE'];
                                $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
                            } else {
                                $current = $arrayResult[$singleOrder['TRANSACTION_ID']];
                                $current->PRICE += $singleOrder['PRICE'] * $singleOrder['AMOUNT'];
                                $current->AMOUNT += $singleOrder['AMOUNT'];
                                $current->THUMB_ID .= "|" . $singleOrder['THUMB_ID'];
                                $current->P_CODE = $current->P_CODE . "|" . $singleOrder['P_CODE'];
                                $arrayResult[$singleOrder['TRANSACTION_ID']] = $current;
                            }
                        }

                        $arrayResult = array_values($arrayResult);

                        ?>

                        <!-- FOR LOOP PURCHASE SORTERED BY F_PIN -->

                        <?php foreach ($arrayResult as $resultOrders) :

                            $recentOrders = json_decode(json_encode($resultOrders), true);

                            $total_price = $recentOrders['PRICE'];
                            $order_date = date_create($recentOrders['CREATED_AT']);

                        ?>

                            <div class="section-orders">
                                <a onclick="sendData('<?= $recentOrders['P_CODE'] ?>')">
                                    <div class="container orders-list">
                                        <div class="row orders-header">

                                            <?php if ($recentOrders['IS_VERIFIED'] == 1) : ?>
                                                <div class="col-8 col-md-8 col-lg-6">
                                                    <img src="../assets/img/tab5/verified-black.png" class="verified" style="width:20px">
                                                    <div class="orders-shop-title" style="margin-left:24px; margin-top:-20px"><b><?= $recentOrders["NAME"] ?></b></div>
                                                </div>
                                            <?php else : ?>
                                                <div class="col-6 col-md-6 col-lg-5" style="margin-left: 40px">
                                                    <div class="orders-shop-title"><b><?= $recentOrders["NAME"] ?></b></div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="col-4 col-md-4 col-lg-6">

                                                <?php if (($recentOrders['STATUS'] == 1) || ($recentOrders['STATUS'] == 0)) : ?>
                                                    <span class="float-end badge rounded-pill orange-badge text-light">
                                                        <span class="small-text" data-translate="tab5orders-2">Processing</span>
                                                    </span>
                                                <?php elseif ($recentOrders['STATUS'] == 2) : ?>
                                                    <span class="float-end badge rounded-pill orange-badge text-light">
                                                        <span class="small-text" data-translate="tab5orders-3">Shipped</span>
                                                    </span>
                                                <?php elseif ($recentOrders['STATUS'] == 3) : ?>
                                                    <span class="float-end badge rounded-pill orange-badge text-light">
                                                        <span class="small-text" data-translate="tab5orders-4">Out of Delivery</span>
                                                    </span>
                                                <?php elseif ($recentOrders['STATUS'] == 4) : ?>
                                                    <span class="float-end badge rounded-pill orange-badge text-light" style="background-color: darkgreen">
                                                        <span class="small-text" data-translate="tab5orders-5">Delivered</span>
                                                    </span>
                                                <?php elseif ($recentOrders['STATUS'] == 5) : ?>
                                                    <span class="float-end badge rounded-pill orange-badge text-light" style="background-color: darkred">
                                                        <span class="small-text" data-translate="tab5orders-10">Declined</span>
                                                    </span>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3 col-md-3 col-lg-2">

                                                <?php $product_image = explode('|', $recentOrders['THUMB_ID']); ?>

                                                <!-- IF ARRAY IS VIDEO, MOVE TO NEXT ARRAY TO GET IMAGE THUMBNAIL -->

                                                <?php $i = 0; ?>

                                                <?php if (substr($product_image[$i], -3) == "mp4") : ?>

                                                    <!-- LOOP VIDEO FILE UNTIL GET PHOTO FOR THUMBNAIL -->

                                                    <?php
                                                    while (substr($product_image[$i], -3) == "mp4") :
                                                        $product_image_video = $product_image[$i + 1];
                                                        $i++;
                                                    endwhile;
                                                    ?>

                                                    <!-- IF ALL MEDIA IS VIDEO, OKAY THEN MAKE COVER IS VIDEO THUMBNAIL -->

                                                    <?php if ($product_image_video) : ?>
                                                        <img src="../images/<?= $product_image_video ?>" class="orders-thumbnail" style="border-radius: 10px">
                                                    <?php else : ?>
                                                        <video src="../images/<?= $product_image[0] ?>#t=0.5" style="object-fit: none; border-radius: 10px" type="video/mp4" class="orders-thumbnail"></video>
                                                    <?php endif; ?>

                                                <?php else : ?>

                                                    <img src="../images/<?= $product_image[$i] ?>" class="orders-thumbnail" style="border-radius: 10px">

                                                <?php endif; ?>

                                                <?php $total_sub_items = explode("|", $recentOrders['P_CODE']); ?>

                                                <?php if (count($total_sub_items) > 1) : ?>
                                                    <div style="position: relative">
                                                        <span class="orders-thumbnail" style="position: absolute; border-radius: 10px; background-color:  #000000; opacity: 0.6; margin-top: -75px"></span>
                                                        <p style="position: absolute; margin-top: -50px; margin-left: 25px; color: #FFFFFF">+<?= count($total_sub_items) - 1 ?></p>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                            <div class="col-9 col-md-9 col-lg-10" style="padding-left: 18px">
                                                <div class="text-grey smallest-text">
                                                    <span data-translate="tab5orders-6">Order </span>#<?= $recentOrders['TRANSACTION_ID'] ?>
                                                </div>
                                                <div class="orders-text" style="font-weight:600">
                                                    <?= date_format($order_date, "F d, Y"); ?>
                                                </div>
                                                <div class="orders-text" style="font-weight:600">
                                                    Rp <?= number_format($total_price + $delivery, 0, ",", ",") ?>
                                                </div>
                                                <div class="orders-text" style="font-weight:600">
                                                    <?= $recentOrders['AMOUNT'] ?> <span data-translate="tab5orders-7">Items<span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="orders-button">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-5 col-md-5 col-lg-5">
                                                    <p class="orders-small-text" data-translate="tab5orders-8" style="font-weight:600">Help with order</p>
                                                </div>
                                                <div class="col-7 col-md-7 col-lg-7">
                                                    <p class="orders-small-text float-end" style="font-weight:600">Anteraja - 1-000-195-108-5070</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        <?php endforeach; ?>
                    <?php else : ?>

                        <p class="text-center small-text" data-translate="tab5orders-9">Anda belum memesan produk apapun.</p>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="mute_send" value="0">
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.13.0-rc.2/jquery-ui.js" integrity="sha256-bLjSmbMs5XYwqLIj5ppZFblCo0/9jfdiG/WjPhg52/M=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>
    function countDescLength() {
        var textarea = document.querySelector(".upload-listing-input#desc")
        var textCounter = document.getElementById("counter-word");

        textarea.addEventListener("input", function() {
            var currentLength = this.value.length;

            textCounter.innerHTML = currentLength;
        });
    }

    // SCRIPT CHANGE LANGUAGE

    $(document).ready(function() {

        function changeLanguage() {

            var lang = localStorage.lang;
            change_lang(lang);

        }

        changeLanguage();

        if (localStorage.lang == 1) {
            document.getElementById("main-menu").style.visibility = "visible";
        }
    });

    // OPEN CREATE HIGHLIGHT MODAL

    // $('#btn-2').hide();

    var is_insert_media = 0;

    function openModal() {

        if (is_insert_media == 1) {
            $('#createHighlight').modal('show');
            countDescLength();
        }
    }

    function openModal2() {

        if ($('#title').val() != "") {
            $('#createHighlight').modal('hide');

            $('body').css('height', '900px');
            $('body').css('position', 'static');
            window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 1));
            $('#recentProductModal').modal('show');

            $("#btn-1").css("display", "none");
            $("#btn-2").css("display", "block");
        }

        $('.modal').css('overscroll-behavior-y', 'contain');
        $('.modal-dialog').css('margin-top', '50px');
        $('.modal-dialog').css('padding-bottom', '50px');
    }

    // SCRIPT FOR RED DOT FORM

    $('.palceholder').click(function() {
        $(this).siblings('textarea').focus();
    });

    $('.form-control').focus(function() {
        $(this).siblings('.palceholder').hide();
    });

    $('.form-control').blur(function() {
        var $this = $(this);
        if ($this.val().length == 0) {
            $(this).siblings('.palceholder').show();
        }
    });

    $('.form-control').blur();

    // SCRIPT SET IMAGE FROM ANDROID/IOS

    var loadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            ;
            setImage(reader.result);
            $('#file-input-1').attr('disabled', 'disabled');
            $('.btn-app-notification').css('background-color', '#000000');
            $('#close').attr("src", "../assets/img/icons/Plus-(White).png");
            $('#aa').attr("src", "../assets/img/tab5/aa.png");
            $('#tagged').attr("src", "../assets/img/tab5/tagged.png");
            $('#mute').attr("src", "../assets/img/tab5/sound.png");
            is_insert_media = 1;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    var media_link;

    function setImage(link) {

        var strings = link.split("/");

        if (strings[0] == 'data:video') {
            $('#media').replaceWith('<video src="' + link + '#t=0.5" id="media" autoplay muted loop style="display:block; width:100%; height:100%; object-fit: cover" type="video/mp4"></video>');
            $("#unmute").css("display", "block");
            $("#mute").css("display", "none");
        } else {
            var output = document.getElementById('media');
            output.src = link;
        }

        media_link = link;
    }

    // SCRIPT CONVERT BASE64 TO OBJECT

    function dataURLtoFile(dataurl, filename) {
        var arr = dataurl.split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]),
            n = bstr.length,
            u8arr = new Uint8Array(n);

        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], filename, {
            type: mime
        });
    }

    // SUBMIT FORM

    function sendData(code) {
        var title = $('#title').val();
        var desc = $('#desc').val();
        var text = $('#text_draggable').val();
        var mute = $('#mute_send').val();

        var file = media_link;
        var format = file.split(";");

        // SORT JPEG

        if (format[0].slice(-4) == "jpeg" || format[0].slice(-4) == "webp") {
            var ext = format[0].slice(-4);
        } else {
            var ext = format[0].slice(-3);
        }

        var converted_link = dataURLtoFile(file, "." + ext);

        var formData = new FormData();

        formData.append('title', title);
        formData.append('desc', desc);
        formData.append('product_code', code);
        formData.append('text', text);
        formData.append('media', converted_link);
        formData.append('mute', mute);

        console.log("Ini" + converted_link);

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == "Berhasil") {
                    window.location.href = "/nexilis/pages/tab5-main";
                } else {
                    console.log("Gagal nih");
                }
            }
        }

        xmlHttp.open("post", "../logics/tab5/insert_highlight");
        xmlHttp.send(formData);
    }

    document.getElementById("btn-2").onclick = function() {
        sendData();
    }

    // $('#input_draggable').draggable();

    // SCRIPT SLIDER TAGGED PRODUCT

    const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
    const fixedPos = JSON.parse(JSON.stringify(initPos));
    let init = parseInt(fixedPos.replace('px', ''));

    $('[data-bs-target="#recentProductModal"]').click(function() {
        $('#recentProductModal .modal-dialog').css('top', fixedPos);
        $('#recentProductModal .modal-dialog').css('height', window.innerHeight - fixedPos);
    })

    $('#recentProductModal').draggable({
        handle: ".mod-header",
        axis: "y",
        drag: function(event, ui) {

            console.log('init: ' + init);
            if (ui.position.top < init) {
                ui.position.top = init;
            }

            let dialog = ui.position.top + window.innerHeight;
            if (dialog - window.innerHeight > 50) {
                $('#recentProductModal').modal('hide');
            }
        }
    });

    $('#recentProductModal').on('hidden.bs.modal', function() {
        $('.modal').css('overflow', 'auto');
        $('.modal').css('overscroll-behavior-y', 'auto');
        $('.modal').css('top', '0px');
        $('.modal').css('left', '0px');
        $('body').css('position', 'fixed');
        $('body').css('height', '100%');
        // $('.modal-dialog').css('margin-top', '-50px');
        // $('.modal-dialog').css('padding-bottom', '-50px');
    })

    // FOR INPUT INSIDE DRAGGABLE CONTENT FOCUSED

    $(':input').bind('click', function() {
        $(this).focus();
    });

    // FOR INSERT TEXT 

    function insertText() {
        $('#text_draggable').prop("disabled", false)
        $('#text_draggable').focus();
    }

    // FOR MUTE BUTTON

    function muteVideo() {
        $("#unmute").css("display", "block");
        $("#mute").css("display", "none");
        $('#mute_send').val(1);
        $("video").prop('muted', true);
    }

    function unmuteVideo() {
        $("#unmute").css("display", "none");
        $("#mute").css("display", "block");
        $('#mute_send').val(0);
        $("video").prop('muted', false);
    }
</script>

</html>