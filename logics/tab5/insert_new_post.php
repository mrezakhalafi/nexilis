<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN

session_start();

// if (!isset($f_pin) || $f_pin == null) {
//   $f_pin = $_POST['f_pin'];
//   $f_pin = $_SESSION["f_pin"];
// }

if (isset($_SESSION["f_pin"])) {
  $f_pin = $_SESSION["f_pin"];
} else {
  $f_pin = $_POST['f_pin'];

  $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
  $query = $dbconn->prepare($queryCheckFpin);
  $query->execute();
  $getNewFPIN = $query->get_result()->fetch_assoc();
  $query->close();

  $f_pin = $getNewFPIN["F_PIN"];
  $_SESSION["f_pin"] = $f_pin;
}

// GET OPEN STORE FORM

$caption = $_POST['caption'];
$title = $_POST['title'];
// echo $_POST['title'];
// echo $_POST['caption'];
$time = $_POST['time'];
$time = floor(microtime(true) * 1000);
$post_link = $_POST['post_link'];

$category = $_POST['category'];
$parent_category = $_POST['parent_category'];

// GET NEW LISTING FORM

$price = $_POST['price'];
$stock = $_POST['stock'];

$notif_id = $f_pin . $time;

// SEPARATE

$post_check = $_POST['post_check'];

if (isset($_POST['hashtags'])) {
  $hashtags = json_decode(base64_decode($_POST['hashtags']));

  $tags_query = "INSERT INTO `POST_HASHTAGS` (`POST_ID`, `HASHTAG`) VALUES ";

  $value_arr = array();
  foreach ($hashtags as $tag) {
    $value_arr[] = "('" . $notif_id . "','" . $tag . "')";
  }

  $combined_values = implode(', ', $value_arr);
  $tags_query = $tags_query . $combined_values . ";";
}

// get score vars
$query = $dbconn->prepare("SELECT * FROM POST_SCORE_PARAMETER");
$query->execute();
$score_var = $query->get_result();
$query->close();

$score = array();

while ($sc = $score_var->fetch_assoc()) {
  $score[$sc["PARAM"]] = $sc["VALUE"];
}

$starting_score = $score["TEMP"] + $time;

// SET IMAGE DIRECTORY

$array_upload_photo = $_POST['array_upload_photo'];
$number = 1;
$listing_thumbnail = "";
$thumbnail = "";



// START BIG FOR IMAGE

$new_array_upload_photo = explode(',', $array_upload_photo);

$file_arr = array();
$thumb_arr = array();

for ($number=0; $number<count($new_array_upload_photo); $number++){

  // SET IMAGE DIRECTORY

  $array_loop = $new_array_upload_photo[$number];

  $filename = $notif_id . "-$array_loop";
  $thumbname = "T_" . $notif_id . "-$array_loop";

  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
  $imageFileType = strtolower(pathinfo($_FILES["listing_thumbnail-$array_loop"]["name"],PATHINFO_EXTENSION));
  $target_file = $target_dir . $filename . "." . $imageFileType;
  $thumbFileType = strtolower(pathinfo($_FILES["thumbnail-$array_loop"]["name"],PATHINFO_EXTENSION));
  $target_thumb = $target_dir . $thumbname . "." . $thumbFileType;
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

  if ($_FILES["listing_thumbnail-$array_loop"]["size"] > 32000000){
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

    if (move_uploaded_file($_FILES["listing_thumbnail-$array_loop"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["thumbnail-$array_loop"]["tmp_name"], $target_thumb)) {
      array_push($file_arr, $filename . "." . $imageFileType);
      array_push($thumb_arr, $thumbname . "." . $thumbFileType);
      $allImagesUploaded = 1;

    }else{
      $allImagesUploaded = 0;
      echo("The file is suitable but not uploaded successfully.".$_FILES["listing_thumbnail-$array_loop"]["error"]);
    }
  }
  
  // INSERT MULTIPLE FILE INTO DATABASE TEXT

  // if ($listing_thumbnail!== ""){
  //   $listing_thumbnail .= "|" . $ip_address . $filename . "_" . $array_loop . "." . $imageFileType;
  // }else{
  //   $listing_thumbnail .=  $ip_address . $filename . "_" . $array_loop . "." . $imageFileType; 
  // }

// END BIG FOR IMAGE

}

if (isset($post_link) && $post_link != "" && substr($post_link, 0, 4) != "http") {
  $post_link = "https://" . $post_link;
}

$queryOK = 0;

if ($allImagesUploaded != 1) {
  echo ("File anda belum sesuai.");
} else {

  $file_list = implode("|", $file_arr);
  $thumb_list = implode("|", $thumb_arr);

  // if (move_uploaded_file($_FILES["post_photo"]["tmp_name"], $target_file) && $uploadThumbnail) {

    // INSERT INTO POST

    // $bytes = random_bytes(8);
    // $hexbytes = strtoupper(bin2hex($bytes));
    // $notif_id = substr($hexbytes, 0, 15);

    // FOR FILE TYPE

    // if ($imageFileType == "mp4") {
    //   $file_type = 2;
    // } else {
      $file_type = 1;
    // }

    // $title = substr($caption,0,32);

    // INSERT POST ONLY (QUERY 1)

    if ($post_check == 0) {

      $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
                    PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, SCORE, LINK, CATEGORY, PARENT) VALUES 
                    ('" . $notif_id . "','" . $f_pin . "','" . $title . "','" . $caption . "','2','" .
        $time . "','3','" . $file_type . "','$thumb_list','$file_list',
                    '" . $time . "', $starting_score, '" . $post_link . "', '$category', '$parent_category')";

      $queryProduct =  "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
          DESCRIPTION, THUMB_ID, CATEGORY, PARENT, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
          REWARD_POINT, QUANTITY, VARIATION, IS_DELETED, IS_POST) VALUES ('" . $notif_id . "','0','" . $title . "','" . $time . "',
          '" . $f_pin . "','" . $caption . "','" . $file_list . "','$category', '$parent_category','0',
          '0',0,'1','" . $file_type . "',0,1,'Regular', 1, '" . $post_check . "')";

      if (!isset($tags_query)) {
        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
          echo ("Berhasil");
          // $queryOK = 1;
        } else {
          echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
      } else {
        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct) && mysqli_query($dbconn, $tags_query)) {
          echo ("Berhasil");
          // $queryOK = 1;
        } else {
          echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
      }
    } else if ($post_check == 1) {

      // INSERT POST AND PRODUCT (QUERY 2)

      $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
                    PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, EC_DATE, SCORE, LINK) VALUES 
                    ('" . $notif_id . "','" . $f_pin . "','" . $title . "','" . $caption . "','2','" .
        $time . "','3','" . $file_type . "','$thumb_list','$file_list',
                    '" . $time . "',$time, $starting_score, '" . $post_link . "')";

      $queryProduct =  "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
                        DESCRIPTION, THUMB_ID, CATEGORY, PARENT, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
                        REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('" . $notif_id . "','0','" . $title . "','" . $time . "',
                        '" . $f_pin . "','" . $caption . "','" . $file_list . "','$category', '$parent_category','0',
                        '0','" . $price . "','1','" . $file_type . "','0','" . $stock . "','Regular','" . $post_check . "')";

      if (!isset($tags_query)) {
        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
          echo ("Berhasil");
          // $queryOK = 1;
        } else {
          echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
      } else {
        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct) && mysqli_query($dbconn, $tags_query)) {
          echo ("Berhasil");
          // $queryOK = 1;
        } else {
          echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
      }
    } else if ($post_check == 2) {
      $queryPost = "INSERT INTO POST (POST_ID, F_PIN, TITLE, DESCRIPTION, TYPE, CREATED_DATE, 
      PRIVACY, FILE_TYPE, THUMB_ID, FILE_ID, LAST_UPDATE, SCORE, LINK, CATEGORY, PARENT) VALUES 
      ('" . $notif_id . "','" . $f_pin . "','" . $title . "','" . $caption . "','2','" .
        $time . "','3','" . $file_type . "','$thumb_list','$file_list',
      '" . $time . "', $starting_score, '" . $post_link . "', '$category', '$parent_category')";

      $queryProduct =  "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
                    DESCRIPTION, THUMB_ID, CATEGORY, PARENT, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
                    REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('" . $notif_id . "','0','" . $title . "','" . $time . "',
                    '" . $f_pin . "','" . $caption . "','" . $file_list . "','$category', '$parent_category','0',
                    '0','" . $price . "','1','" . $file_type . "','0','" . $stock . "','Regular','" . $post_check . "')";
      // echo $queryPost;
      if (!isset($tags_query)) {
        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
          echo ("Berhasil");
          // $queryOK = 1;
        } else {
          echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
      } else {
        if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct) && mysqli_query($dbconn, $tags_query)) {
          echo ("Berhasil");
          // $queryOK = 1;
        } else {
          echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
        }
      }
    }

    // if ($queryOK == 1 && !isset($tags_query)) {
    //   echo ("Berhasil");
    // } else if ($queryOK == 1 && isset($tags_query)) {
    //   if (mysqli_query($dbconn, $tags_query)) {
    //     echo ("Berhasil");
    //   } else {
    //     echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
    //   }
    // }

  // } else {
  //   echo ("The file is suitable but not uploaded successfully." . $_FILES["post_photo"]["error"]);
  // }
}
