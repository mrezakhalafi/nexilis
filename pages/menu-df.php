<?php

  include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $dbconn = paliolite();
  $dbNewNus = newnus();

  $f_pin = $_GET['f_pin'];
  // $_SESSION['user_f_pin'] = $f_pin;

  // Background 

  $bg_url = "";

  if (isset($f_pin) && $f_pin != "") {
      $sqlBE =    "SELECT be.COMPANY_ID
                  FROM BUSINESS_ENTITY be
                  LEFT JOIN USER_LIST ul ON ul.BE = be.ID
                  WHERE ul.F_PIN = '$f_pin'";
  } else if (isset($api_key) && $api_key != "") {
      $sqlBE =    "SELECT be.COMPANY_ID
                  FROM BUSINESS_ENTITY be
                  WHERE be.API = '$api_key'";
  }

  $queBE = $dbconn->prepare($sqlBE);
  $queBE->execute();
  $resBE = $queBE->get_result()->fetch_assoc();
  $c_id = $resBE["COMPANY_ID"];
  $queBE->close();

  // print_r($c_id);

  $sqlBG = "SELECT APP_BG FROM WEBFORM WHERE COMPANY_ID = " . $c_id . " AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
  $queBG = $dbNewNus->prepare($sqlBG);
  $queBG->execute();
  $resBG = $queBG->get_result()->fetch_assoc();
  $bg_str = $resBG["APP_BG"];
  $queBG->close();

  if ($bg_str != null && $bg_str != "") {

      $bg_arr = explode(",", $bg_str);
      $rand_bg = $bg_arr[array_rand($bg_arr, 1)];
      $bg_url = "https://newuniverse.io/dashboardv2/uploads/background/" . $rand_bg;

  } else {

      $rand_bg = rand(1, 9) . ".png";
      $bg_url = "../assets/img/lbackground_" . $rand_bg;

  }

  // AVATAR

  // $sqlData = "SELECT * FROM USER_LIST WHERE F_PIN = '$f_pin'";

  // $queDATA = $dbconn->prepare($sqlData);
  // $queDATA->execute();
  // $data = $queDATA->get_result()->fetch_assoc();
  // $queDATA->close();

  // $be = $data['BE'];

  $sqlData = "SELECT COMPANY_LOGO FROM WEBFORM WHERE COMPANY_ID = '$c_id' ORDER BY 1 DESC LIMIT 1";

  $queDATA = $dbNewNus->prepare($sqlData);
  $queDATA->execute();
  $dataImage = $queDATA->get_result()->fetch_assoc();
  $queDATA->close();

  // FORM

  $sqlData = "SELECT * FROM FORM WHERE CREATED_BY = '$f_pin'";

  $queDATA = $dbconn->prepare($sqlData);
  $queDATA->execute();
  $dataForm = $queDATA->get_result();
  $queDATA->close();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu DF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <style>

      html {
        height: 100%;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        -moz-background-size: cover;
        -webkit-background-size: cover;
        -o-background-size: cover;
        -ms-background-size: cover;
        background-position: center center;
      }

      body {
        background-image: url('<?php echo $bg_url; ?>');
        background-size: 100% auto;
        background-repeat: repeat;
        font-family: 'Poppins', sans-serif;
      }

    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

  </head>

  <body>

    <div class="row m-4 pt-3 pb-3">
      <div class="col-12 text-center">
        <!-- <img src="https://qmera.io/filepalio/images/<?= $dataImage['COMPANY_LOGO'] ?>" style="width: 150px; height: auto"> -->
        <img src="https://newuniverse.io/dashboardv2/uploads/logo/<?= $dataImage['COMPANY_LOGO'] ?>" style="width: 150px; height: auto">
      </div>
    </div>

    <div class="container">

    <?php foreach ($dataForm as $df): ?>

      <div class="single-form row mt-4">
          <div class="col-1"></div>
          <div class="col-10">
              <a href="output-formbuilder?form_code=<?= $df['FORM_ID'] ?>&f_pin=<?= $_GET['f_pin'] ?>">
                <div class="btn btn-dark w-100" style="line-height: 2.5; border-radius: 15px; opacity: 0.7; border: 5px solid grey; font-weight: 700">
                  
                  <?php 
                  $title = explode("|", $df['TITLE']);
                  ?>

                  <?= $title[0] ?>
                  
                </div>
              </a>
          </div>
          <div class="col-1"></div>
      </div>

        <?php endforeach; ?>

    </div>

      <img onclick="closeAndroid()" src="../assets/img/close.png" 
      style="position: fixed;
            width: 60px;
            height: 60px;
            bottom: 75px;
            right: 20px;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;">
    </div>

    <div style="width: 100%; height: 125px; background-color: transparent">

    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>

      function closeAndroid(){

        if (window.Android){
          window.Android.finishGaspolForm();
        }else{
          history.back();
        }
      }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>