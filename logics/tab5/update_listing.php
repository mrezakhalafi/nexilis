<?php 

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// CHANGE THIS IP ACCORDING TO NEEDS

// $ip_address = "http://192.168.0.56/nexilis/images/";
// $ip_address = "http://202.158.33.26/nexilis/images/";
$ip_address = "";

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

$f_pin = $_SESSION['user_f_pin'];

// GET NEW LISTING FORM

$product_title = $_POST['product_title'];
$product_description = $_POST['product_description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category = $_POST['category'];
$variation = $_POST['variation'];
$weight = $_POST['weight'];

$id_product = $_POST['id_product'];
$old_thumb_id = $_POST['old_thumb_id'];
$array_changed_photo = $_POST['array_changed_photo'];
$deleted_thumb_id = $_POST['deleted_thumb_id'];
$post_check = $_POST['post_check'];

// CHECK IF THERE IS CHANGED OF PHOTO

if ($array_changed_photo > 0){

    // CHANGED PHOTO

    $number = 0;
    $listing_thumbnail = "";

    // START BIG FOR IMAGE

    $new_array_changed_photo = explode(',', $array_changed_photo);

    // COUNT AS CHANGED SLOT NUMBER

    for ($number=0; $number<count($new_array_changed_photo); $number++){

      // SET IMAGE DIRECTORY

      $array_loop = $new_array_changed_photo[$number];

      $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
      $imageFileType = strtolower(pathinfo($_FILES["listing_thumbnail-$array_loop"]["name"],PATHINFO_EXTENSION));
      $target_file = $target_dir . $f_pin . time() . $array_loop . "." . $imageFileType;
      $uploadOk = 1;

      // CHECK IF REAL IMAGE

      if (isset($_POST["submit"])){

        $check = getimagesize($_FILES["listing_thumbnail-$array_loop"]["tmp_name"]);

        if ($check !== false){
          echo("File is an image - " . $check["mime"] . ".");
          $uploadOk = 1;
        } else {
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
        echo("Only JPG, JPEG, PNG, WEBP & GIF format photos and MP4 video formats are allowed. Now : ".$_FILES["listing_thumbnail-$array_loop"]);

        $uploadOk = 0;
      }

      // CHECK IMAGE VALIDATION AND UPLOAD IT

      if ($uploadOk == 0) {
        echo("Your file doesn't match.");
      }else{

        if (move_uploaded_file($_FILES["listing_thumbnail-$array_loop"]["tmp_name"], $target_file)) {

          $allImagesUploaded = 1;

        }else{
          echo("File match but can't upload.".$_FILES["listing_thumbnail-$array_loop"]["error"]);
        }
      }

      // DATABASE THUMB_ID FILLED WITH OLD THUMB_ID AND NEW NAME

      $new_listing_thumbnail = $old_thumb_id . "|" . $ip_address . $f_pin . time() . $array_loop . "." . $imageFileType;
      $old_thumb_id = $new_listing_thumbnail;

    // END BIG FOR IMAGE

    }

    // IF USER UPLOAD NEW IMAGE AND DELETE IMAGE TOO

    if ($deleted_thumb_id != null){

      $new_listing_thumbnail = str_replace($deleted_thumb_id."|","",$old_thumb_id);
      $new_listing_thumbnail = str_replace("|".$deleted_thumb_id,"",$old_thumb_id);
      // $new_listing_thumbnail = str_replace($deleted_thumb_id."|","",$new_listing_thumbnail);

      // IF NOTHING CHANGES BECAUSE RANDOM DELETE (EX = 1 AND 4)

      if ($new_listing_thumbnail == $old_thumb_id){

        $delete_explode = explode('|', $deleted_thumb_id);

        foreach ($delete_explode as $explode){

          $new_listing_thumbnail = str_replace($explode,"",$old_thumb_id);
          $old_thumb_id = $new_listing_thumbnail;

        }
      }

      // IF THERE IS | IN CENTER

      while (strpos($new_listing_thumbnail, '||') !== false){
        $new_listing_thumbnail = str_replace("||","|",$new_listing_thumbnail);
      }

      // IF THERE IS | IN BEGINNING

      while ($new_listing_thumbnail[0] == "|"){
        $new_listing_thumbnail = substr($new_listing_thumbnail, 1);
      }

      // IF THERE IS | IN LAST

      while (substr($new_listing_thumbnail, -1) == "|"){
        $new_listing_thumbnail = substr($new_listing_thumbnail, 0, -1);
      }
    }

    // DELETE IMAGE FROM DATABASE

    $delete_explode = explode('|', $deleted_thumb_id);
    chmod($target_dir . $explode, 777);

    foreach ($delete_explode as $explode){

      unlink($target_dir . $explode);
      // print_r($target_dir . $explode);

    }

    // AFTER ALL SUITABLE THEN UPLOAD FILE AND UPDATE PRODUCT & PRODUCT SHIPMENT DETAIL

    if ($allImagesUploaded==1){

      $query = "UPDATE PRODUCT SET NAME = '$product_title', SHOP_CODE = '$id_shop', DESCRIPTION =
                '$product_description', THUMB_ID = '$new_listing_thumbnail', CATEGORY = '$category', 
                PRICE = '$price', QUANTITY = '$stock', VARIATION = '$variation', IS_POST = '$post_check',
                WHERE CODE = '".$id_product."'";

      $queryDetails = "UPDATE PRODUCT_SHIPMENT_DETAIL SET WEIGHT = '$weight' WHERE PRODUCT_CODE = '".$id_product."'";

      if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $queryDetails)){
        header("Location: ../../pages/tab3-commerce.php?f_pin=".$f_pin);
      }else{
        echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn)); 
      }

      print_r($listing_thumbnail);

    }else{
      echo("Failed to update database in the product & shipment query (Not Images)");
    }
      
}else if($deleted_thumb_id != null){

  $new_listing_deleted = str_replace($deleted_thumb_id."|","",$old_thumb_id);
  $new_listing_deleted = str_replace("|".$deleted_thumb_id,"",$old_thumb_id);
  // $new_listing_deleted = str_replace($deleted_thumb_id."|","",$new_listing_deleted);

  // IF NOTHING CHANGES BECAUSE RANDOM DELETE (EX = 1 AND 4)

   if ($new_listing_deleted == $old_thumb_id){

    $delete_explode = explode('|', $deleted_thumb_id);

    foreach ($delete_explode as $explode){

      $new_listing_deleted = str_replace($explode,"",$old_thumb_id);
      $old_thumb_id = $new_listing_deleted;

    }
  }

  // IF THERE IS | IN CENTER

  while (strpos($new_listing_deleted, '||') !== false){
    $new_listing_deleted = str_replace("||","|",$new_listing_deleted);
  }
  
  // IF THERE IS | IN BEGINNING

  while ($new_listing_deleted[0] == "|"){
    $new_listing_deleted = substr($new_listing_deleted, 1);
  }

  // IF THERE IS | IN LAST

  while (substr($new_listing_deleted, -1) == "|"){
    $new_listing_deleted = substr($new_listing_deleted, 0, -1);
  }

  // DELETE IMAGE FROM DATABASE

  $delete_explode = explode('|', $deleted_thumb_id);
  chmod($target_dir . $explode, 777);

  foreach ($delete_explode as $explode){

    unlink($target_dir . $explode);
    // print_r($target_dir . $explode);

  }

  // AFTER COMPLETE UPDATE DATABASE

  $query = "UPDATE PRODUCT SET NAME = '$product_title', SHOP_CODE = '$id_shop', DESCRIPTION =
            '$product_description', THUMB_ID = '$new_listing_deleted', CATEGORY = '$category', 
            PRICE = '$price', QUANTITY = '$stock', VARIATION = '$variation', IS_POST = '$post_check' 
            WHERE CODE = '".$id_product."'";

  $queryDetails = "UPDATE PRODUCT_SHIPMENT_DETAIL SET WEIGHT = '$weight' WHERE PRODUCT_CODE = '".$id_product."'";

  if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $queryDetails)){
    header("Location: ../../pages/tab3-commerce.php?f_pin=".$f_pin);
  }else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn)); 
  }
}
else{

  // NO CHANGES ANY PHOTO AT ALL

  $query = "UPDATE PRODUCT SET NAME = '$product_title', SHOP_CODE = '$id_shop', DESCRIPTION =
            '$product_description', CATEGORY = '$category', PRICE = '$price', QUANTITY = '$stock', VARIATION = '$variation',
            IS_POST = '$post_check' WHERE CODE = '".$id_product."'";
  
  $queryDetails = "UPDATE PRODUCT_SHIPMENT_DETAIL SET WEIGHT = '$weight' WHERE PRODUCT_CODE = '".$id_product."'";

  if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $queryDetails)){
    header("Location: ../../pages/tab3-commerce.php?f_pin=".$f_pin);
  }else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
  }
}

?>