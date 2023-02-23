<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();

if (!isset($_POST['f_pin'])){
  $id_user = $_SESSION["user_f_pin"];
}else{
  $id_user = $_POST['f_pin'];
  $_SESSION['user_f_pin'] = $id_user;
}

// GET NEW POST FORM 

$product_title = $_POST['product_title'];
$product_description = $_POST['product_description'];
$post_link = $_POST['url'];

// GET NEW LISTING FORM

$price = $_POST['price'];
$stock = $_POST['stock'];

// SEPARATE

$post_check = $_POST['post_check'];

// DECLARE VARIABLE FOR IMAGE LOOP

$array_upload_photo = $_POST['array_upload_photo'];
$number = 1;
$listing_thumbnail = "";

// START BIG FOR IMAGE

$new_array_upload_photo = explode(',', $array_upload_photo);

for ($number=0; $number<count($new_array_upload_photo); $number++){

  // SET IMAGE DIRECTORY

  $array_loop = $new_array_upload_photo[$number];

  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
  $imageFileType = strtolower(pathinfo($_FILES["listing_thumbnail-$array_loop"]["name"],PATHINFO_EXTENSION));
  $target_file = $target_dir . $f_pin . time() . $array_loop . "." . $imageFileType;
  $uploadOk = 1;

  // CHECK IF REAL IMAGE

  if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["listing_thumbnail-$array_loop"]["tmp_name"]);

    if ($check !== false) {
      echo("File is an image - " . $check["mime"] . ".");
      $uploadOk = 1;
    }else{
      echo("File is not an image.");
      $uploadOk = 0;
    }
  }

  // CHECK IF IMAGE EXIST

  if (file_exists($target_file)){
    echo("Sorry, file already exists.");
    $uploadOk = 0;
  }

  // CHECK IMAGE SIZE

  if ($_FILES["listing_thumbnail-$array_loop"]["size"] > 5000000){
    echo("Your file size is too large.");
    $uploadOk = 0;
  }

  // CHECK IMAGE FORMAT

  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"){
    echo "Only JPG, JPEG, PNG & GIF photo formats and MP4 video formats are allowed. Now :".$_FILES["listing_thumbnail-$array_loop"]["name"];
    
    $uploadOk = 0;
  }

  // CHECK IMAGE VALIDATION AND UPLOAD IT

  if ($uploadOk == 0) {
    echo("Your file does not match.");
  }else{

    if (move_uploaded_file($_FILES["listing_thumbnail-$array_loop"]["tmp_name"], $target_file)) {

      $allImagesUploaded = 1;

    }else{
      echo("The file is suitable but not uploaded successfully.".$_FILES["listing_thumbnail-$array_loop"]["error"]);
    }
  }
  
  // INSERT MULTIPLE FILE INTO DATABASE TEXT

  if ($listing_thumbnail!== ""){
    $listing_thumbnail .= "|" . $ip_address . $f_pin . time() . $array_loop . "." . $imageFileType;
  }else{
    $listing_thumbnail .=  $ip_address . $f_pin . time() . $array_loop . "." . $imageFileType; 
  }

// END BIG FOR IMAGE

}

if ($allImagesUploaded==1){

  // IF THERE IS | IN CENTER

  while (strpos($listing_thumbnail, '||') !== false){
    $listing_thumbnail = str_replace("||","|",$listing_thumbnail);
  }

  // IF THERE IS | IN BEGINNING

  while ($listing_thumbnail[0] == "|"){
    $listing_thumbnail = substr($listing_thumbnail, 1);
  }

  // IF THERE IS | IN LAST

  while (substr($listing_thumbnail, -1) == "|"){
    $listing_thumbnail = substr($listing_thumbnail, 0, -1);
  }

  $bytes = random_bytes(8);
  $hexbytes = strtoupper(bin2hex($bytes));
  $code = substr($hexbytes, 0, 15);

  // FOR FILE TYPE

  if ($imageFileType == "mp4"){
    $file_type = 2;
  }else{
    $file_type = 1;
  }

  if ($post_check == 0){

    $query = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
              PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, LINK) VALUES 
              ('".$code."','".$id_user."','".$product_title."','".$product_description."','2','".
              (time()*1000)."','3','".$file_type."','".$listing_thumbnail."','".$listing_thumbnail."',
              '".(time()*1000)."','".$post_link."')";

    if (mysqli_query($dbconn, $query)){
      header("Location: ../../pages/tab3-commerce.php?f_pin=".$id_user);
    } else{
      echo("ERROR: Data failed to add in query. $sql. " . mysqli_error($dbconn));
    }

  }else if($post_check == 1){

    $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
                  PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, LINK) VALUES 
                  ('".$code."','".$id_user."','".$product_title."','".$product_description."','2','".
                  (time()*1000)."','3','0','".$listing_thumbnail."','".$listing_thumbnail."',
                  '".(time()*1000)."','".$post_link."')";

    $queryProduct =  "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
                      DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
                      REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('".$code."','0','".$product_title."','".(time()*1000)."',
                      '".$id_user."','".$product_description."','".$listing_thumbnail."','0','0',
                      '0','".$price."','1','".$file_type."','0','".$stock."','Regular','".$post_check."')";

    if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)){
      header("Location: ../../pages/tab3-commerce.php?f_pin=".$id_user);
    } else{
      echo("ERROR: Data failed to add in query. $sql. " . mysqli_error($dbconn));
    }

  }

}else{
  echo("Failed to insert database in the product & shipment query (Not File)");
}

?>