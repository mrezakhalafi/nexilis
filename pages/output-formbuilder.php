<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$form_code = $_GET['form_code'];
$dbconn = paliolite();
$ver = time();

// FOR LOOP QUESTION

$queryform = $dbconn->prepare("SELECT * FROM FORM LEFT JOIN FORM_ITEM ON FORM.FORM_ID = FORM_ITEM.FORM_ID WHERE FORM.FORM_ID = '$form_code' ORDER BY FORM_ITEM.ID");
$queryform->execute();
$form = $queryform->get_result();
$queryform->close();

// FOR TITLE AND DESC 

$queryforms = $dbconn->prepare("SELECT * FROM FORM WHERE FORM_ID = '$form_code' ORDER BY ID DESC");
$queryforms->execute();
$forms = $queryforms->get_result()->fetch_assoc();
$queryforms->close();

// FORM COLOR THEME

$color = "#f66701";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Digital Form</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/material-design-iconic-font.min.css">

    <style>
        
        .modal {
            z-index: 9999;
        }

        #modal-payment{
            width: 100%;
        }

        .form-submit {
            margin-top: 10px;
        }

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input[type="radio"]{
            accent-color: <?= $color ?>;
        }

        input[type="checkbox"]{
            accent-color: <?= $color ?>;
        }

        .text-danger{
            font-size: 13px;
            font-weight: 700;
        }

    </style>
</head>

<body>

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" id="digital-form" style="padding: 0px" action="/nexilis/logics/" enctype="multipart/form-data">
            <div class="row p-2" style="border-bottom: 2px #e5e5e5 solid; background-image: url(../assets/img/lbackground_6.png)">
                <div class="col-1 d-flex justify-content-start">
                    <a href="menu-df?f_pin=<?= $_GET['f_pin'] ?>"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                </div>
                <div class="col-11 d-flex justify-content-center">
                    <h2 style="margin-bottom: 0px; font-size: 20px; font-weight: 700; margin-top: 5px">DIGITAL FORM</h2>
                </div>
            </div>

            <!-- TITLE FORM AND DESCRIPTION -->

            <div class="container pt-4 pb-3">

            <?php 
                $title = explode("|", $forms['TITLE']);
            ?>
            
                <h2 style="font-size: 22px; text-transform: uppercase; font-weight: bold; color: <?= $color ?>" class="text-center mt-2"><?= str_replace("Formulir", "<span style=\"color: #000000;\"> Formulir</span>", $title[0]); ?> </h2>

                <?php if (isset($title[1])): ?>
                    <p style="font-size: 16px; color: #626262; text-transform: uppercase; font-weight: bold" class="text-center pt-2">(<?= $title[1] ?>)</p>
                <?php endif; ?>

            </div>

            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>


            <!-- FORM SECTION -->

            <div class="container px-4 pt-2 pb-4">
                <div class="col-12">
                    
                    <?php
                    foreach ($form as $key=>$f) {

                        // INPUT TEXT

                        if ($f['TYPE'] == 6) {
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-0"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div id="input" class="col-12 d-flex justify-content-center">
                                    <input type="text" name="question-<?= $key ?>" id="question-<?= $key ?>" placeholder="Write Answer" style="border: none; border-bottom: 2px solid #ebebeb; width: 100%" />
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT RADIO

                        if ($f['TYPE'] == 7) {
                            ?>
                            <div class="row mt-3">
                                <div id="radio_list" class="col-12 d-flex justify-content-start">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                </div>
                            </div>

                            <div class="row gx-0">
                                
                                <?php 
                                
                                $gender = explode("|", $f['VALUE']);
                                $i = 1;

                                foreach($gender as $g):
                                ?>
                                    <div class="col-6 justify-content-start">
                                        <input type="radio" id="question-<?= $key ?>" name="question-<?= $key ?>" class="radio" value="<?= $g ?>" <?php if($i == 1): ?> checked <?php endif ; ?>>
                                        <label style="font-size: 13px; font-weight: bold" for="question-<?= $key ?>">&nbsp;&nbsp;
                                            <?= $g ?>
                                        </label>
                                    </div>
                                <?php 
                                $i++;
                                endforeach; ?>

                                <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">   
                                <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                                <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                            </div>
                            
                        <?php
                        }

                        // INPUT SINGLE CHECKBOX

                        if ($f['TYPE'] == 12) {
                            ?>
                            <div class="row mt-3">
                                <div id="check_list" class="col-12">
                                    
                                    <div class="row gx-0">

                                        <div class="col-12">
                                            <input type="checkbox" name="question-<?= $key ?>" id="question-<?= $key ?>" style="background-color: white"/>&nbsp; &nbsp; <?= $f['VALUE'] ?>
                                            <span id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?></span>
                                        </div>

                                        <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                                        <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                                        <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                                    </div>
                                </div>
                            </div>
                        
                        <?php
                        }

                        // INPUT DATE

                        if ($f['TYPE'] == 1) {
                            ?>
                            <div class="row mt-3">
                                <div id="date" class="col-12 d-flex justify-content-start">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                </div>
                            </div>

                            <div class="row gx-0 mt-2">
                                <div class="col-12 d-flex justify-content-center">
                                    <input type="date" name="question-<?= $key ?>" id="question-<?= $key ?>" value="1970-01-01" style="background-color: white; border: none; border-bottom: 2px solid #ebebeb; width: 100%"/>
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT PHOTO / IMAGE

                        if ($f['TYPE'] == 15) {
                            ?>
                            <div class="row mt-3">
                                <div id="input_file" class="col-12">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                    
                                    <div class="row" style="margin-bottom:5px; font-size: 13px; font-weight: bold">
                                        <div class="col-6">
                                            <input type="radio" id="input-photo-file-<?= $key ?>" name="radio-file-chooser-<?= $key ?>" class="radio" value="File" checked>
                                            <label for="input-photo-file-<?= $key ?>">&nbsp;&nbsp;From File</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" id="input-photo-ocr-<?= $key ?>" name="radio-file-chooser-<?= $key ?>" class="radio" value="OCR">
                                            <label for="input-photo-ocr-<?= $key ?>">&nbsp;&nbsp;Take Photo</label><br>
                                        </div>
                                    </div>

                                    <div class="row gx-0">
                                        <div class="col-12 mt-2">
                                            <label for="question-<?= $key ?>" id="inputfile_button-<?= $key ?>" style="color: #FFFFFF; background-color: <?= $color ?>; margin-right: 10px; margin-bottom: 10px" class="btn">Choose File</label>
                                            <p id="file-text-question-<?= $key ?>" style="display: inline; font-size: 13px; font-weight: bold">No file chosen</p>
                                            <input type="file" style="display:none;" accept="image/*,profile_file/*" name="question-<?= $key ?>" id="question-<?= $key ?>" class="photo" onchange="loadFile(event)"/>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT SELECT DROPDOWN

                        if ($f['TYPE'] == 4) {
                            ?>
                            <div class="row mt-3">
                                <div id="drop" class="col-12">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                    <div class="row gx-0">
                                        <?php
                                        $dropdown = explode("|", $f['VALUE'])
                                        ?>

                                        <div class="col-12">
                                            <select class="mb-2" style="margin-left: -5px" id="question-<?= $key ?>" name="question-<?= $key ?>" aria-label="" style="font-size: 16px">
                                                <option value="" selected>Select Option</option>

                                                <?php foreach($dropdown as $d): ?>
                                                    <option><?= $d ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT CANVAS

                        if ($f['TYPE'] == 20) {
                            ?>
                            <div class="uploadTtdCanvas mt-3">
                                <p id="question-<?= $key ?>-text" class="mb-0" style="font-size: 12px; font-weight: 700"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                <canvas class="mt-2" id="canvasSignature-<?= $key ?>" name="question-<?= $key ?>" width="325px" height="300px" style="border:2px solid <?= $color ?>; width: 100%; height: 343px"></canvas><br>
                                <button style="width: 100%" class="btn btn-secondary" type="button" name="clearTtd" id="clearTtd" onclick="clearCanvas()">Clear</button>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                            <input id="question-<?= $key ?>" type="hidden">
                        
                        <?php
                        }

                        // INPUT OCR FILE

                        if ($f['TYPE'] == 24) {
                            ?>
                            <div class="row mt-3">
                                <div id="input_file" class="col-12">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                    
                                    <div class="row" style="margin-bottom:5px; font-size: 13px; font-weight: bold">
                                        <div class="col-6">
                                            <input type="radio" id="input-photo-file-<?= $key ?>" name="radio-file-chooser-<?= $key ?>" class="radio" value="File" checked>
                                            <label for="input-photo-file-<?= $key ?>">&nbsp;&nbsp;From File</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" id="input-photo-ocr-<?= $key ?>" name="radio-file-chooser-<?= $key ?>" class="radio" value="OCR">
                                            <label for="input-photo-ocr-<?= $key ?>">&nbsp;&nbsp;Take Photo</label><br>
                                        </div>
                                    </div>

                                    <div class="row gx-0">
                                        <div class="col-12 mt-2">
                                            <label for="question-<?= $key ?>" id="inputfile_button-<?= $key ?>" style="color: #FFFFFF; background-color: <?= $color ?>; margin-right: 10px; margin-bottom: 10px" class="btn">Take Photo</label>
                                            <p id="file-text-question-<?= $key ?>" style="display: inline; font-size: 13px; font-weight: bold">No file chosen</p>
                                            <input type="file" style="display:none;" accept="image/*,ocr_file/*" name="question-<?= $key ?>" id="question-<?= $key ?>" class="photo" onchange="loadFile(event)"/>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT TEXTAREA

                        if ($f['TYPE'] == 11) {
                            ?>
                            <div class="mt-3 mb-3">
                                <label id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" for="exampleFormControlTextarea1" class="form-label"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </label>
                                <textarea id="question-<?= $key ?>" class="form-control" name="question-<?= $key ?>" placeholder="Write Here..." id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT DATE & TIME

                        if ($f['TYPE'] == 2) {
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p id="question-<?= $key ?>-text" class="mb-0" style="font-size: 12px; font-weight: 700"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                </div>
                                <div class="col-12 mt-3">
                                    <input type="date" id="question-<?= $key ?>" name="question-<?= $key ?>" style="width: 100%; border: none; border-bottom: 2px solid #ebebeb">
                                </div>
                                <div class="col-12 mt-3">
                                    <input type="time" id="question-<?= $key ?>-second" name="question-<?= $key ?>-second" style="width: 100%; border: none; border-bottom: 2px solid #ebebeb">
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT TIME

                        if ($f['TYPE'] == 3) {
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p id="question-<?= $key ?>-text" class="mb-0" style="font-size: 12px; font-weight: 700"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                </div>
                                <div class="col-12 mt-3">
                                    <input type="time" id="question-<?= $key ?>" name="question-<?= $key ?>" style="width: 100%; border: none; border-bottom: 2px solid #ebebeb">
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT FILE ATTACHMENT (PDF)

                        if ($f['TYPE'] == 14) {
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-2"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>

                                    <label for="question-<?= $key ?>" id="docBtn" style="color: #FFFFFF; background-color: <?= $color ?>; margin-right: 10px; margin-bottom: 10px" class="btn mt-2">Choose File</label>
                                    <p id="file-text-question-<?= $key ?>" style="display: inline;">No file chosen</p>
                                    <input type="file" style="display:none;" accept="file_extension/*,document/*" name="question-<?= $key ?>" id="question-<?= $key ?>" class="photo" onchange="loadFile(event)" />
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                        // INPUT NUMBER

                        if ($f['TYPE'] == 5) {
                            ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-0"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?> </p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div id="input" class="col-12 d-flex justify-content-center">
                                    <input type="number" name="question-<?= $key ?>" id="question-<?= $key ?>" placeholder="Enter Number" style="border: none; border-bottom: 2px solid #ebebeb; width: 100%" />
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                        
                        <?php
                        }

                            // HEADER

                            if ($f['TYPE'] == 0) {
                            ?>
                           
                            <div class="row">
                                <div class="col-12 mt-3 mb-1">
                                    <b id="header-<?= $key ?>" style="font-size: 18px"><?= $f['LABEL'] ?></b>
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">   
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                        
                        <?php
                        }

                        // PRICING

                        if ($f['TYPE'] == 25) {
                            ?>

                                </div>
                            </div>
                            <div class="row mt-2">
                                <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
                                    <div class="col-12 pt-3 px-4">

                                        <?php
                                            $label = explode("|", $f['LABEL']);
                                        ?>

                                        <div class="row">
                                            <div class="col-6">
                                                <p id="pricing-<?= $key ?>" style="font-weight: bold; font-size: 13px; color: #626262; padding-left: 10px; margin-top: 5px"><?= $label[0] ?></p>
                                            </div>

                                            <?php if (isset($label[1])): ?>
                                                
                                                <div class="col-6">
                                                    <p id="pricing-<?= $key ?>" class="text-end" style="font-weight: bold; font-size: 20px; color: #000000; padding-left: 10px"><?= $label[1] ?></p>
                                                </div>

                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">   
                            <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                            
                        <?php
                        }

                        // INPUT MULTIPLE CHECKBOX

                        if ($f['TYPE'] == 8) {
                            ?>
                            <div class="row mt-3">
                                <div id="check_list" class="col-12">
                                    
                                    <div class="row gx-0">
                                    <span id="question-<?= $key ?>-text" style="font-size: 12px; font-weight: 700" class="mb-1"><?= str_replace("*", "<span style=\"color:red;\"> *</span>", $f['LABEL']); ?></span>

                                        <?php
                                        $checkbox = explode("|", $f['VALUE']);

                                        foreach ($checkbox as $c) {
                                            ?>
                                            <div class="col-12">
                                                <input type="checkbox" name="question-<?= $key ?>[]" id="question-<?= $key ?>" value="<?= $c ?>" style="background-color: white"/>&nbsp; &nbsp; <?= $c ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <input type="hidden" name="question-<?= $key ?>-type" value="<?= $f['TYPE'] ?>">
                                        <input type="hidden" name="question-<?= $key ?>-label" value="<?= $f['KEY'] ?>">
                                        <small id="question-<?= $key ?>-error" class="text-danger d-none">This field is required.</small>
                                    </div>
                                </div>
                            </div>
                        
                        <?php
                        }

                    }
                    ?>
                </div>
            </div>

            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>
            <input type="hidden" id="total-question" name="total-question" value="<?= mysqli_num_rows($form) ?>">
            
            <div class="row p-3 pt-4 pb-5" style="background-image: url(../assets/img/lbackground_6.png)">
                <div class="col-12 d-flex justify-content-center">
                    <button style="width: 100%; background-color: <?= $color ?>; border: 1px solid <?= $color ?>; width: 40%; height: 50px" type="button" class="btn btn-dark text-light" onclick="validation()">SUBMIT</button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="modalProgress" tabindex="-1" role="dialog" aria-labelledby="modalProgress" aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 200px">
                <div class="modal-content">
                    <div class="modal-body pt-4" id="modalProgress">
                    <p>Upload in progress...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 200px">
                <div class="modal-content">
                    <div class="modal-body text-center pt-4" id="modalSuccess">
                        <img src="../assets/img/success.png" style="width: 100px">
                        <h1 class="mt-3">Form Submit Success!</h1>
                        <p class="mt-2">Verifying your information, usually takes within 24 hours or less.</p>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center">
                                <a href="menu-df.php?f_pin=<?= $_GET['f_pin'] ?>"><button type="button" class="btn btn-dark mt-3" style="background-color: <?= $color ?>; border: 1px solid <?= $color ?>">Main Menu</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body pt-4" id="modal-payment-body">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 200px">
                <div class="modal-content">
                    <div class="modal-body pt-4 text-center" id="modal-error-body">
                        <p id="error-modal-text">An Error Accured.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
            <div class="modal-dialog" role="document" style="margin-top: 200px">
                <div class="modal-content">
                    <div class="modal-body pt-4 text-center" id="modal-validation-body">
                        <p id="validation-text"></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<!-- JS -->

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
<script src="../assets/js/form-kta-mobility.js?v=<?php echo $ver; ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="../assets/js/output-formbuilder.js?v=<?php echo $ver; ?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script>

    var total_question = $('#total-question').val();

    $(document).ready(function () {

        // LOOP REQUIRED JS SCRIPT PER TYPE

        for (var i=0; i<total_question; i++){

            var type = $("input[name=question-"+i+"-type]").val();

            // CANVAS

            if (type == 20){
                initialize(i);
            }

            // PHOTO/VIDEO

            if (type == 15){
                changeInputMethod(i);
            }

            // OCR

            if (type == 24){
                changeInputMethodOCR(i);
            }

            // SELECT

            if (type == 4){
                $('#question-'+i).selectize();
            }
        }
    });

    // CHANGE FILE TEXT NAME

    var file_array = [];

    function loadFile(event){

        var files = event.target.files;
        file_array.push({name: event.target.getAttribute('name'), value: files[0].name });
        $('#file-text-'+event.target.id).text(files[0].name);

        // console.log(file_array);
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

    var fd;

    // VALIDATION

    function validation(){

        var total_required = 0;
        var filled_required = 0;

        for (var i=0; i<total_question; i++){

            var text = $('#question-'+i+'-text').text();

            // IF FIELD REQUIRED

            if (text.includes("*")){

                total_required = total_required + 1;
                
                var value = $('#question-'+i).val();
                var type = $("input[name=question-"+i+"-type]").val();

                // IF REQUIRED FIELD IS FILL

                if (value){

                    if (type == 12 || type == 8){
                        
                        // IF CHECKBOX

                        if ($("input[name=question-"+i+"]").is(':checked') || $("input[name='question-"+i+"[]']").is(':checked')){

                            $('#question-'+i+'-error').addClass('d-none');
                            filled_required = filled_required + 1;

                        }else{

                            $('#question-'+i+'-error').removeClass('d-none');

                        }

                    }else if (type == 2){
                    
                        // IF DATE & TIME

                        var date = $('#question-'+i).val();
                        var time = $('#question-'+i+'-second').val();

                        if (date && time ){

                            $('#question-'+i+'-error').addClass('d-none');
                            filled_required = filled_required + 1;

                        }else{

                            $('#question-'+i+'-error').removeClass('d-none');

                        }

                    }else{

                        $('#question-'+i+'-error').addClass('d-none');
                        filled_required = filled_required + 1;

                    }

                }else{

                    // IF FIELD IS NULL

                    $('#question-'+i+'-error').removeClass('d-none');

                }
            }
        }

        console.log("Filled Required = "+filled_required+" From = "+total_required);

        // CHECK IF ALL REQUIRED FORM ARE FILLABLE

        if (total_required == filled_required){

            submitForm();

        }else{

            $('#validation-text').text("Please fill all required form");
            $('#modal-validation').modal('show');

        }
    }

    // INSERT INTO DB

    function submitForm(){

        fd = new FormData($('#digital-form')[0]);
        fd.append('form_id', new URLSearchParams(window.location.search).get("form_code"));
        fd.append('f_pin', new URLSearchParams(window.location.search).get("f_pin"));

        // LOOP CANVAS BY ORDER

        for (var i=0; i<total_question; i++){

            var type = $("input[name=question-"+i+"-type]").val();

            if (type == 20){
                var blob = canvasToBlob(i);
                
                var format = blob.split(";");
                var canvasName = $('#canvasSignature-'+i).attr('name');

                // SORT JPEG

                if (format[0].slice(-4) == "jpeg" || format[0].slice(-4) == "webp"){
                    var ext = format[0].slice(-4);
                }else{
                    var ext = format[0].slice(-3);
                }

                var fileCanvas = dataURLtoFile(blob, "." +ext);

                fd.append(canvasName, fileCanvas);

                console.log(canvasName);
                console.log(blob);
            }
        }

        postForm();

    }

    function postForm(){

        console.log(fd);
        $('#modalProgress').modal('show');

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/answer-formbuilder",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {

                $('#modalProgress').modal('hide');
                $('#modalSuccess').modal('show');

            },
            error: function (response) {

                $('#modalProgress').modal('hide');
                $('#modal-error').modal('show');

            }
        });     
    }

</script>