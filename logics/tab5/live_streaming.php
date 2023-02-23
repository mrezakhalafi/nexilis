<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP & USER

session_start();
$id_shop = $_SESSION['id_shop'];
$f_pin = $_SESSION['user_f_pin'];

// GET FROM LIVE STREAMING FORM

$ls_title = $_POST['ls_title'];
$ls_desc = $_POST['ls_desc'];
$ls_product = $_POST['ls_product'];

if (isset($ls_product)){
  $ls_product = $_POST['ls_product'];
}else{
  $ls_product = "1";
}

// SELECT PALIO ID/COMPANY ID
	
$query = $dbconn->prepare("SELECT * FROM SHOP WHERE CODE = '".$id_shop."'");
$query->execute();
$company_id = $query->get_result()->fetch_assoc();
$query->close();

$companyId = $company_id['PALIO_ID'];

// SET IMAGE DIRECTORY

$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["ls_thumbnail"]["name"],PATHINFO_EXTENSION));
$target_file = $target_dir . $f_pin . time() . "." . $imageFileType;

// CHECK IF REAL IMAGE & FILE SIZE & FORMAT FILE

if (isset($_POST["submit"])){
  $check = getimagesize($_FILES["ls_thumbnail"]["tmp_name"]);

  if ($check !== false) {
    echo("File is an image - " . $check["mime"] . ".");
    $uploadOk = 1;
  } else {
    echo("File is not an image.");
    $uploadOk = 0;
  }

}else if(file_exists($target_file)){

  echo("Sorry, file already exists.");
  $uploadOk = 0;
  
}else if($_FILES["ls_thumbnail"]["size"] > 500000){

  echo("Your file size is too large.");
  $uploadOk = 0;

}else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif"){

  echo("Only JPG, JPEG, PNG & GIF photo formats are allowed. Now :");
  $uploadOk = 0;
  
}else{

  // IF ALL SUITABLE UPLOAD FILE AND INSERT INTO SHOP LS

  $ls_thumbnail = $f_pin.time() . "." . $imageFileType;

  if (move_uploaded_file($_FILES["ls_thumbnail"]["tmp_name"], $target_file)){

      copy($target_file, '/apps/lcs/paliolite/server/image/' . $ls_thumbnail);

      $queryLS = "INSERT INTO SHOP_LS (F_PIN,COMPANY_ID) VALUES ('".$f_pin."','".$companyId."')";

      $queryLS_INFO = "INSERT INTO SHOP_LS_INFO (F_PIN, STORE_CODE, TITLE, COVER_ID, DESCRIPTION, 
                        FEATURED_PRODUCTS) VALUES ('".$f_pin."','".$id_shop."','".$ls_title."','"
                        .$ls_thumbnail."','".$ls_desc."','".$ls_product."')";

      if (mysqli_query($dbconn, $queryLS) && mysqli_query($dbconn, $queryLS_INFO)){
        
        ?>

        <script type="text/javascript">

          // RESET LIVE STREAM DATA

          window.localStorage.removeItem('title_ls');
          window.localStorage.removeItem('thumbnail_ls');
          window.localStorage.removeItem('feature_product_temp');
          window.localStorage.removeItem('feature_product_temp_code');

        </script>

        <!-- GO TO SHOP MANAGER TO START LIVE STREAMING -->

        <?php
        header("Location: ../../pages/tab5-shop-manager?live_streaming=on&title=".$ls_title);
      
      }else{
        echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
      }
  }else{
    echo("Photos match but can't upload.".$_FILES["ls_thumbnail"]["error"]);
  }
}
