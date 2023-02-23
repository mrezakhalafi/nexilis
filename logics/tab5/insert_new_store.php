<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN

session_start();
$store_created_by = $_SESSION['user_f_pin'];
$f_pin = $_SESSION['user_f_pin'];

// GET OPEN STORE FORM

$store_name = $_POST['store_name'];
$phone_number = $_POST['phone_number'];
$location = $_POST['location'];
$website = $_POST['website'];
$description = $_POST['description'];

$palio_id = "00000";

// CHECK IF SHOP NAME AVAILABLE

$query = $dbconn->prepare("SELECT * FROM SHOP WHERE NAME = '$store_name'");
$query->execute();
$check_store_name = $query->get_result()->fetch_assoc();
$query->close();

// SET IMAGE DIRECTORY

$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
$imageFileType = strtolower(pathinfo($_FILES["shop_thumbnail"]["name"],PATHINFO_EXTENSION));
$uploadOk = 1;
$target_file = $target_dir . $f_pin . $time() . "." . $imageFileType;

// CHECK IF REAL IMAGE

if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["shop_thumbnail"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File tersebut bukan gambar";
    $uploadOk = 0;
  }
}

// CHECK IF IMAGE EXIST

if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

 // CHECK IMAGE SIZE

if ($_FILES["shop_thumbnail"]["size"] > 500000) {
  echo "Ukuran foto anda terlalu besar,";
  $uploadOk = 0;
}

// CHECK IMAGE FORMAT

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Hanya foto format JPG, JPEG, PNG & GIF yang diperbolehkan";
    $uploadOk = 0;
}

// CHECK IMAGE VALIDATION

$thumb_id = $f_pin . time() . "." . $imageFileType;

if ($uploadOk == 0) {
    echo "Foto anda belum sesuai.";
} else if(isset($check_store_name["NAME"])){
    echo "Nama toko Sudah Ada";
}
else {
    if (move_uploaded_file($_FILES["shop_thumbnail"]["tmp_name"], $target_file)) {

        // INSERT INTO SHOP

        $bytes = random_bytes(8);
        $hexbytes = strtoupper(bin2hex($bytes));
        $code = substr($hexbytes, 0, 15);

        $queryShop = "INSERT INTO palio_lite.SHOP (CODE, NAME, CREATED_DATE, DESCRIPTION, FILE_TYPE, THUMB_ID, 
                LINK, CATEGORY, USE_ADBLOCK, SCORE, PALIO_ID, CAN_DELETE, TOTAL_FOLLOWER, IS_VERIFIED, 
                TOTAL_VISITOR, IS_LIVE_STREAMING, CREATED_BY, TOTAL_LIKES, SHOW_FOLLOWS) 
                VALUES ('".$code."','".$store_name."','1','".$description."','1','".$thumb_id."',
                '".$website."','1','1','1','$palio_id','1','0','1','0','0','".$store_created_by."','0','1')";

        $queryAddress = "INSERT INTO palio_lite.SHOP_SHIPPING_ADDRESS (STORE_CODE, ADDRESS, VILLAGE, DISTRICT,
                        CITY, PROVINCE, ZIP_CODE, PHONE_NUMBER, COURIER_NOTE) VALUES ('".$random_number."','".$location."','1','1',
                        '1','1','1','".$phone_number."','1')"; 

        if (mysqli_query($dbconn, $queryShop) && mysqli_query($dbconn, $queryAddress)){
            header("Location: ../../pages/tab5-success-open-shop.php");
        }else{
            echo "Data gagal ditambahkan ke database. $sql. " . mysqli_error($dbconn);
        }

    }else {
      echo "Foto sesuai tapi tidak berhasil diupload.".$_FILES["shop_thumbnail"]["error"];
    }
}

?>