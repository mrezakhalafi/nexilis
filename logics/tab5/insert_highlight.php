<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN

session_start();
$f_pin = $_SESSION['user_f_pin'];

// GET FROM CREATE HIGHLIGHT FORM

$title = $_POST['title'];
$desc = $_POST['desc'];
$product_code = $_POST['product_code'];
$mute = $_POST['mute'];
$text = $_POST['text'];

// SET IMAGE DIRECTORY

$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
$imageFileType = strtolower(pathinfo($_FILES["media"]["name"],PATHINFO_EXTENSION));
$uploadOk = 1;
$target_file = $target_dir . $f_pin . time() . "." . $imageFileType;

// CHECK IF REAL IMAGE

if (isset($_POST["submit"])){
  $check = getimagesize($_FILES["media"]["tmp_name"]);
  if ($check !== false){
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// CHECK IF IMAGE EXIST

if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// CHECK IMAGE SIZE

if ($_FILES["media"]["size"] > 5000000) {
  echo "Your file size is too large.";
  $uploadOk = 0;
}

// CHECK IMAGE FORMAT

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"){
  echo "Only JPG, JPEG, PNG & GIF photo formats and MP4 video formats are allowed. Now :";
  $uploadOk = 0;
}

// CHECK IMAGE VALIDATION

$media = $f_pin . time() . "." . $imageFileType;

if ($uploadOk == 0) {
  echo("File anda belum sesuai.");
}else{

  if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)){

    // INSERT INTO HIGHLIGHT

    // $query = "INSERT INTO USER_HIGHLIGHT (F_PIN, TITLE, DESCRIPTION, PRODUCT_CODE, THUMB_ID, MUTE, TEXT) VALUES 
    //           ('".$f_pin."','".$title."','".$desc."','".$product_code."','".$media."','".$mute."','".$text."')";

    $bytes = random_bytes(8);
    $hexbytes = strtoupper(bin2hex($bytes));
    $code = substr($hexbytes, 0, 15);

    $query = "INSERT INTO USER_HIGHLIGHT (CODE, F_PIN, TITLE, DESCRIPTION, IS_QIOSK) VALUES 
    ('".$code."','".$f_pin."','".$title."','".$desc."','1')";

    $query_sub = "INSERT INTO USER_HIGHLIGHT_DETAILS (HIGHLIGHT_CODE, THUMB_ID, TEXT, MUTE, PRODUCT_CODE) 
                  VALUES ('".$code."','".$media."','".$text."','".$mute."','".$product_code."')";

    if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $query_sub)){
      echo("Berhasil");
    }else{
      echo("Data failed to add. $sql. " . mysqli_error($dbconn));
    }

  }else{
    echo("The file is suitable but not uploaded successfully.".$_FILES["media"]["error"]);
  }
}

?>