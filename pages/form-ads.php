<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $dbconn = paliolite();
    $ver = time();

    if (isset($_GET['f_pin'])){
        $f_pin = $_GET['f_pin'];
    }

    // NEW F_PIN SET METHOD

    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();

    $f_pin = $getNewFPIN['F_PIN'];

    // print_r($f_pin);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Ads Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    
    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>

        .modal {
            z-index: 9999;
        }

        .form-submit {
            margin-top: 10px;
        }

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input, select {
            padding-left: 12px;
        }

        .alert-secondary {
            background-color: #e9eef1 !important;
            border: none !important;
        }

    </style>

</head>
<body class="d-none">
    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form h-100" id="ads-form" style="padding: 0px" enctype="multipart/form-data">

            <div class="p-3 shadow-sm fixed-top" style="border-bottom: 1px solid #e4e4e4; background-color: white">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b id="text-header" style="font-size: 14px">Create Ads Form</b>
                    </div>
                </div>
            </div>

            <div class="container mx-auto" style="margin-top: 80px">
                <div class="mt-4 mx-auto">

                    <div class="row gx-0" id="ads_title-section">
                        <div class="col-2" style="margin-top: 11px">
                            <span><b id="title-text">TITLE</b></span>
                        </div>
                        <div class="col-10">
                            <input type="text" name="ads_title" id="ads_titlex" placeholder="Ads Title" maxlength="45" required />
                            <div class="d-flex justify-content-end">
                                <span id="counter-word-title" style="font-size: 10px" class="text-grey">0</span><span style="font-size: 10px" class="text-grey">/45</span>
                            </div>
                        </div>
                        <span class="ads_title text-danger" style="position: absolute; margin-top: 10px; margin-left: 13%; width: 10px">
                            *
                        </span>
                    </div>

                    <div class="row gx-0" id="ads_desc-section">
                        <div class="col-2" style="margin-top: 11px">
                            <span><b id="decription-text">DESC</b></span>
                        </div>
                        <div class="col-10">
                            <input type="placeholder" name="ads_desc" id="ads_desc" placeholder="Ads Description" maxlength="200" required />
                            <div class="d-flex justify-content-end">
                                <span id="counter-word-desc" style="font-size: 10px" class="text-grey">0</span><span style="font-size: 10px" class="text-grey">/200</span>
                            </div>
                        </div>
                        <span class="ads_desc text-danger" style="position: absolute; margin-top: 10px; margin-left: 16%; width: 10px">
                            *
                        </span>
                    </div>

                    <!-- <div class="row gx-0" id="ads_animate-section">
                        <div class="col-2" style="margin-top: 17px">
                        <span><b id="style_text">STYLE</b></span>
                        </div>
                        <div class="col-10" style="margin-top: 15px">
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected value="1">Horizontal</option>
                                <option value="2">Vertical</option>
                            </select>
                        </div>
                    </div> -->

                    <p class="mb-3 mt-3"><b id="text-adsPhoto">ADS MEDIA</b> &nbsp;</p>
                    <div class="row">
                        <div id="section-media" class="col-5">
                            <img id="imageProfile" src="../assets/img/default-image.png" style="width: 130px; height: 130px; border-radius: 10px; object-fit: cover; object-position: center">
                            <div id="loading-image" class="spinner-border d-none" role="status" style="position:absolute; position: absolute; margin-left: -80px; margin-top: 50px">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="row mt-4 pt-2 d-flex justify-content-center text-center">
                                <label for="ads_photo" id="profileLabelBtn" style="font-size: 15px; color: black; width: 80%; border-radius: 20px; background-color: white; border: 1px solid black; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Upload Photo</label>
                                <small id="text-pp" class="text-secondary" style="font-weight: 500; font-size: 8.5px">* Please upload the proper photos.</small>
                            </div>
                            <div class="row">
                                <input type="file" style="display:none;" accept="image/*" name="ads_photo" id="ads_photo" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)"/>
                            </div>
                        </div>
                    </div>
                    <span id="ads_photo-error" class="error" style="color: red"></span>

                    <div class="row gx-0 mt-4" id="ads_url-section">
                        <div class="col-2" style="margin-top: 11px">
                            <span><b>URL</b></span>
                        </div>
                        <div class="col-2" style="margin-top: 11px">
                        <span style="background-color: #e9eef1; padding: 11px; border-radius: 10px; margin-left: -20px"><b>https://</b></span>
                        </div>
                        <div class="col-8">
                            <input type="text" name="ads_url" id="ads_url" maxlength="100" placeholder="Ads URL" required />
                            <div class="d-flex justify-content-end">
                                <span id="counter-word-url" style="font-size: 10px" class="text-grey">0</span><span style="font-size: 10px" class="text-grey">/100</span>
                            </div>
                        </div>
                        <span class="ads_url text-danger" style="position: absolute; margin-top: 10px; margin-left: 19%; width: 10px">
                            *
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-5" style="width: 100%; height: 5px; background-color: #e5e5e5"></div>
                    
            <div class="container mx-auto">
                <div class="mt-4 mx-auto">
                    <div class="row gx-0 mt-3" id="ads_appear-section">
                        <div class="col-3" style="margin-top: 11px">
                            <span><b id="appear-text">APPEAR</b></span>
                        </div>
                        <div class="col-9" style="margin-top: 11px">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioStartPosition" value="0" id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2" id="left-text">
                                            Left
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioStartPosition" value="1" id="flexRadioDefault3">
                                        <label class="form-check-label" for="flexRadioDefault3" id="right-text">
                                            Right
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioStartPosition" value="2" id="flexRadioDefault">
                                        <label class="form-check-label" for="flexRadioDefault" id="top-text">
                                            Top
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioStartPosition" value="3" id="flexRadioDefault4">
                                        <label class="form-check-label" for="flexRadioDefault4" id="bottom-text">
                                            Bottom
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-secondary" role="alert" id="alert-1">
                        The animation will appear from this direction and will move to opposite direction.
                    </div>

                    <div class="row gx-0 mt-5" id="dissapear-section">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="dissapearCheckbox">
                            <label class="form-check-label ms-2" for="dissapearCheckbox">
                                <b id="dissapear-done-text">Dissapear ads after animation is done</b>
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-secondary" role="alert" id="alert-2">
                        If you check this, the animation will dissapear after moving from its direction to opposite direction, but if not, the animation will keep alive and moving from it's direction to opposite direction .
                    </div>

                    <div class="row gx-0 mt-4" id="ads_url-section">
                        <div class="col-2" style="margin-top: 11px">
                            <span><b id="end_time_text">EXPIRY</b></span>
                        </div>
                        <div class="col-10">
                            <input type="date" name="end_time" id="end_time" value="2023-01-01" min="2023-01-01" required style="background-color: white"/>
                        </div>
                        <!-- <span class="end_time text-danger" style="position: absolute; margin-top: 10px; margin-left:148px; width: 10px">
                            *
                        </span> -->
                    </div>

                </div>
            </div>
            
            <div class="mt-5" style="width: 100%; height: 5px; background-color: #e5e5e5"></div>
            <div style="background-color: #e9eef1; padding-top: 25px">
                <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                    <button type="submit" class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #0089ff; width: 50%; height: 50px; color: white" onclick="selectizeValid()"><b id="text-payNow">SUBMIT</b></button>
                </div>
            </div>

        </form>

        <!-- <div style="height: 80px; background-color: #e9eef1"></div> -->

        <div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center pb-3" id="modalLoading">
                        <img src="../assets/img/loading_build.gif" style="width: 75%"><br />
                        <p><b id="submit-data-text">Submitting data in progress</b></p>
                        <p  id="please-wait-text">Please wait...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSuccess" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center" id="modalSuccess">
                        <img src="../assets/img/success.png" style="width: 100px">
                        <h1 class="mt-3" id="insert-success-text">Insert Ads Success!</h1>
                        <p id="success-desc-text" class="mt-3">Your ads data has been uploaded.</p>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center">
                                <button onclick="finishForm()" type="button" class="btn btn-dark mt-3" style="background-color: #0089ff; border: 1px solid #0089ff">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-error-body">
                    <p id="error-modal-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 150px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p style="font-size: 12px" id="validation-text"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

<script>

    var F_PIN = "<?php echo $f_pin; ?>";
    var global_ads_photo = '';
    var global_media_type = '';
    var global_end_time = '';
    var global_thumbnail = '';
    var global_is_gif = 0;
    var global_server = 0;
    var global_is_dissapear = 0;
    var global_start_position = 0;

    var $image_type_arr = ["jpg", "jpeg", "png", "webp", "gif"];
    var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];

    var loadFile = function(event) {

        $('#loading-image').removeClass('d-none');

        var fileFormat = event.target.files[0].type.split('/')[1];
        console.log("Format :", fileFormat)

        var img, vid, canvas, ctx;
        var reader = new FileReader();
        reader.onload = createImage;
        reader.readAsDataURL(event.target.files[0]);

        function createImage() {

            let size = event.target.files[0].size;
            console.log(size);

            if ($image_type_arr.includes(fileFormat)) {

                if (fileFormat == 'gif'){
                    global_is_gif = 1;
                }

                if (size > 2000000){

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Photos size cannot exceeded 2 MB',
                    })

                    $('#loading-image').addClass('d-none');

                }else{

                    let html = `<img id="imageProfile" src="`+reader.result+`" style="width: 130px; height: 130px; border-radius: 10px; object-fit: cover; object-position: center">`;

                    $('#section-media').html(html);

                    global_media_type = 1;

                    img = new Image();
                    img.onload = imageLoaded;
                    img.src = reader.result;

                    $('#loading-image').addClass('d-none');

                }

            } else if ($video_type_arr.includes(fileFormat)) {

                if (size > 10000000){

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Videos size cannot exceeded 10 MB',
                    })

                    $('#loading-image').addClass('d-none');

                }else{
                
                    let html = `<video id="imageProfile" autoplay muted loop src="`+reader.result+`" style="width: 130px; height: 130px; border-radius: 10px; object-fit: cover; object-position: center"></video>`;

                    $('#section-media').html(html);

                    global_media_type = 2;

                    vid = document.createElement('video');
                    vid.onloadedmetadata = videoLoaded;
                    vid.src = reader.result;

                    $('#loading-image').addClass('d-none');

                }
            }
        }

        function imageLoaded() {

            let imgDataURL;
            canvas = document.createElement('canvas');
            ctx = canvas.getContext("2d");

            if (event.target.files[0].size > 204800) {

                // set size proportional to image
                canvas.height = canvas.width * (img.height / img.width);

                // step 1 - resize to 50%
                var oc = document.createElement('canvas'),
                    octx = oc.getContext('2d');

                oc.width = img.width * 0.8;
                oc.height = img.height * 0.8;
                octx.drawImage(img, 0, 0, oc.width, oc.height);

                // step 2
                octx.drawImage(oc, 0, 0, oc.width, oc.height);

                // step 3, resize to final size
                ctx.drawImage(oc, 0, 0, oc.width, oc.height,
                    0, 0, canvas.width, canvas.height);

            } else {

                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                ctx.drawImage(img, 0, 0);

            }

            imgDataURL = canvas.toDataURL("image/webp");
            global_thumbnail = imgDataURL;
            console.log("PIC THUMB", imgDataURL);

        }

        function videoLoaded() {

            console.log(2);

            let thumbnail;
            console.log('VIDEO LOADED');
            canvas = document.createElement('canvas');
            ctx = canvas.getContext("2d");

            if (vid.duration < 1) {
                reject("video is too short.");
                return;
            }

            vid.addEventListener('seeked', () => {

                console.log('video is now paused at 0.1');
                canvas.width = vid.videoWidth;
                canvas.height = vid.videoHeight;
                ctx.drawImage(vid, 0, 0, canvas.width, canvas.height);
                thumbnail = canvas.toDataURL('image/webp', 0.8);
                global_thumbnail = thumbnail;
                console.log("VID THUMB", thumbnail);

                $('#thumbnail').attr('src',thumbnail);

            });

            setTimeout(() => {
                vid.currentTime = 1;
            }, 1000);

        }
    };

    function dataURLtoFile(dataurl, filename) {

        // console.log(dataurl)

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

    $("#ads_photo").change(function() {

        var ppimg = $(this).val();

        if (ppimg) {
            $(".starppimg").hide();
        }

        else {
            $(".starppimg").show();
        }

    });

    $("#ads_titlex").bind("change paste keyup", function () {

        var title = $(this).val();

        if (title) {
            $(".ads_title").hide();
        }

        else {
            $(".ads_title").show();
        }

    });

    $("#ads_desc").bind("change paste keyup", function () {

        var desc = $(this).val();

        if (desc) {
            $(".ads_desc").hide();
        }

        else {
            $(".ads_desc").show();
        }

    });

    $("#ads_url").bind("change paste keyup", function () {

        var ads_url = $(this).val();

        if (ads_url) {
            $(".ads_url").hide();
        }

        else {
            $(".ads_url").show();
        }

    });

    // FOR SELECTIZED VALIDATION

    // function selectizeValid(){

    //     var ads_photo = $('#ads_photo').val();

    //     if (!ads_photo){

    //         if (localStorage.lang == 1) {
    //             $('#ads_photo-error').text("Kolom ini wajib diisi.");
    //         }
    //         else {
    //             $('#ads_photo-error').text("This field is required.");
    //         }

    //     }else{

    //         $('#ads_photo-error').text("");

    //     }

    // }

    var file;

    // SCRIPT CONVERT BASE64 TO OBJECT

    function dataURLtoFile(dataurl, filename){

        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);

        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], filename, {type:mime});

    }

    function closeAndroid(){

        if (window.Android){

            if (window.Android.finishForm) {
                window.Android.finishForm();
            } else {
                window.history.back();
            }

        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishForm) {

            window.webkit.messageHandlers.finishForm.postMessage({
                param1: ""
            });
            return;

        }else{

            history.back();

        }
    }

    var nameError = '';

    if (localStorage.lang == 1) {
        nameError = "Kolom ini wajib diisi.";    
    }
    else {
        nameError = "This field is required.";    
    }

    var is_submit = 0;

    $('#ads-form').validate({

        rules: {
            fotoProfil: {
                photoSize: 4000000,
                checkPhoto: true
            },
        },
        messages: {
            'ads_title': {
                required: nameError
            },
            'ads_desc': {
                required: nameError
            },
            'ads_url': {
                required: nameError
            }
        },
        submitHandler: function (form, event) {

            event.preventDefault();

            // VALIDATION

            let ads_title = $('#ads_titlex').val();
            let ads_photo = $('#ads_photo').val();
            let ads_url = $('#ads_url').val();
            let media_type = global_media_type;
            let end_time = $('#end_time').val();

            if ($('#dissapearCheckbox').is(':checked') ){
                global_is_dissapear = 0;
            }
            else{
                global_is_dissapear = 1;
            }

            global_start_position = document.querySelector('input[name="radioStartPosition"]:checked').value;

            let date = new Date(end_time);
            global_end_time = date.getTime(); 

            console.log("ads_title = "+ads_title);
            console.log("ads_photo = "+ads_photo);
            console.log("ads_url = "+ads_url);
            console.log("media_type = "+media_type);
            console.log("end_time = "+global_end_time);
            console.log("is_gif = "+global_is_gif);
            console.log("is_dissapear = "+global_is_dissapear);
            console.log("start_position = "+global_start_position);


            if (ads_title && ads_url) {

                if (is_submit == 0){
                    is_submit = 1;
                    submitForm();
                }

            } else {

                if (localStorage.lang == 1) {
                    $('#validation-text').text("Harap isi semua form");    
                }
                else {
                    $('#validation-text').text("Please fill all required form");
                }

                $('#modal-validation').modal('show');

            }

            $("#modal-validation").on('hidden.bs.modal', function(e) {
                $("html").attr("style", "");
            });
        }
    });

    function submitForm() {

        var myform = $("#ads-form")[0];
        let fd = new FormData(myform);

        fd.append("f_pin", F_PIN);
        fd.append("end_date_milis", global_end_time);
        fd.append("media_type", global_media_type);
        fd.append("is_gif", global_is_gif);
        fd.append("server", global_server);
        fd.append("is_dissapear", global_is_dissapear);
        fd.append("start_position", global_start_position);

        var format = global_thumbnail.split(";")[0].split('/')[1];
        console.log("Format :"+format);

        if (global_thumbnail){
            fd.append("thumbnail", dataURLtoFile(global_thumbnail, "." + format));
        }else{
            global_media_type = 3;
        }

        $('#modalLoading').modal('show');
        $('html').css('overflow-y','hidden');

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/insert_ads",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {

                console.log("Success to insert Form!");

                global_ads_photo = response;
               
                $("#submit").prop("disabled", false);
                $('#modalLoading').modal('hide');
                $('html').css('overflow-y','');

                $('#modalSuccess').modal('show');
                $('html').css('overflow-y','hidden');

            },
            error: function (response) {
               
                $("#submit").prop("disabled", false);
                alert(response.responseText);
            }
        });
    }

    function finishForm(){

        if (window.Android) {
            $('html').css('overflow-y','');
            window.Android.finishForm();
        }
        else {
            window.location.reload();
        }

        // window.location.reload();

    }

    function countMaxTitle() {
        var textarea = document.querySelector("#ads_titlex")
        var textCounter = document.getElementById("counter-word-title");

        textarea.addEventListener("input", function() {
            var currentLength = this.value.length;
            textCounter.innerHTML = currentLength;
        });
    }

    function countMaxDesc() {
        var textarea = document.querySelector("#ads_desc")
        var textCounter = document.getElementById("counter-word-desc");

        textarea.addEventListener("input", function() {
            var currentLength = this.value.length;
            textCounter.innerHTML = currentLength;
        });
    }

    function countMaxURL() {
        var textarea = document.querySelector("#ads_url")
        var textCounter = document.getElementById("counter-word-url");

        textarea.addEventListener("input", function() {
            var currentLength = this.value.length;
            textCounter.innerHTML = currentLength;
        });
    }

    countMaxTitle();
    countMaxDesc();
    countMaxURL();

    if (localStorage.lang == 1){

        $('#text-header').text('Formulir Pembuatan Iklan')
        $('#ads_titlex').attr('placeholder','Judul Iklan');
        $('#ads_desc').attr('placeholder','Deskripsi Iklan');
        $('#text-adsPhoto').text('MEDIA IKLAN');
        $('#profileLabelBtn').text('Unggah Foto');
        $('#text-pp').text('* Unggah foto yang pantas.');
        $('#text-payNow').text('KIRIM');
        $('#title-text').text("JUDUL");
        $('#style_text').text("GAYA");
        $('.ads_title').css('margin-left','149px');
        $('#end_time_text').text('AKHIR');
        $('#decription-text').text('DESK');
        $('#ads_url').attr('placeholder','URL Iklan');
        $('.ads_url').css('margin-left','204px');
        $('#submit-data-text').text('Pengiriman data sedang berlangsung');
        $('#please-wait-text').text('Harap Tunggu...');
        $('#insert-success-text').text('Input Iklan Berhasil!');
        $('#success-desc-text').text('Data iklan anda berhasil diunggah.');
        $('#appear-text').text('TAMPIL');
        $('#left-text').text('Kiri');
        $('#top-text').text('Atas');
        $('#right-text').text('Kanan');
        $('#bottom-text').text('Bawah');
        $('#dissapear-done-text').text('Hilangkan iklan setelah animasi selesai');
        $('#alert-1').text('Animasi akan muncul dari arah ini dan akan bergerak ke arah berlawanan.');
        $('#alert-2').text('Jika Anda mencentang ini, animasi akan hilang setelah berpindah dari arahnya ke arah yang berlawanan, tetapi jika tidak, animasi akan tetap hidup dan bergerak berlawanan dari arahnya.');

        $('body').removeClass('d-none');

    }else{

        $('#text-header').text('Create Ads Form');
        $('#ads_titlex').attr('placeholder','Ads Title');
        $('#ads_desc').attr('placeholder','Ads Description');
        $('#text-adsPhoto').text('ADS MEDIA');
        $('#profileLabelBtn').text('Upload Photo');
        $('#text-pp').text('* Please upload the proper photos.');
        $('#text-payNow').text('SUBMIT');
        $('#title-text').text("TITLE");
        $('#style_text').text("STYLE");
        $('.ads_title').css('margin-left','13%');
        $('#end_time_text').text('EXPIRY');
        $('#decription-text').text('DESC');
        $('#ads_url').attr('placeholder','Ads URL');
        $('.ads_url').css('margin-left','19%');
        $('#submit-data-text').text('Submitting data in progress');
        $('#please-wait-text').text('Please Wait...');
        $('#insert-success-text').text('Insert Ads Success!');
        $('#success-desc-text').text('Your ads data has been uploaded.');

        $('#appear-text').text('APPEAR');
        $('#left-text').text('Left');
        $('#top-text').text('Top');
        $('#right-text').text('Right');
        $('#bottom-text').text('Bottom');
        $('#dissapear-done-text').text('Dissapear ads after animation is done');
        $('#alert-1').text('The animation will appear from this direction and will move to opposite direction.');
        $('#alert-2').text('If you check this, the animation will dissapear after moving from its direction to opposite direction, but if not, the animation will keep alive and moving from it`s direction to opposite direction.');

        $('body').removeClass('d-none');

    }

    // SET DATE TO +1 TODAY

    var today = new Date();
    var minDate = new Date(today.setDate(today.getDate() + 1)).toISOString().split("T")[0];

    $('#end_time').val(minDate);
    $('#end_time').attr('min', minDate);

    function getServer(){

        let link = window.location.href.split("/");

        if (link.includes('palio.web')){

            console.log('Ini Localhost');
            global_server = 3;

        }else if(link.includes('newuniverse.io')){

            console.log('Ini CBN');
            global_server = 1;

        }else if(link.includes('108.137.84.148')){

            console.log('Ini AWS');
            global_server = 2;

        }
    }

    getServer();

</script>