<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

// GET FROM SHOP SETTINGS FORM

$name = $_POST['name'];
$description = $_POST['description'];
$website = $_POST['website'];
$code = $_POST['code'];

$old_image = $_POST['old_image'];
$old_banner = $_POST['old_banner'];

$thumb_id = basename($_FILES["shop_thumbnail"]["name"]);
$banner_id = basename($_FILES["shop_banner"]["name"]);

$target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
$target_file = $target_dir . basename($_FILES["shop_thumbnail"]["name"]);
$target_banner = $target_dir . basename($_FILES["shop_banner"]["name"]);

$uploadOk = 1;

$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$bannerFileType = strtolower(pathinfo($target_banner,PATHINFO_EXTENSION));

// IF THERE IS CHANGE IMAGE

if ($thumb_id!=="" && $banner_id!==""){

    if (isset($_POST["submit"])){
        $check = getimagesize($_FILES["shop_thumbnail"]["tmp_name"]);

        if ($check !== false){
          echo("File is an image - " . $check["mime"] . ".");
          $uploadOk = 1;
        }else{
          echo("File is not an image.");
          $uploadOk = 0;
        }
    }

    if (isset($_POST["submit"])){
        $check = getimagesize($_FILES["shop_banner"]["tmp_name"]);

        if ($check !== false) {
          echo("Banner is an image - " . $check["mime"] . ".");
          $uploadOk = 1;
        }else{
          echo("Banner is not an image");
          $uploadOk = 0;
        }
    }

    if (file_exists($target_dir . $old_image)){
        chmod($target_dir . $old_image, 777);
        unlink($target_dir . $old_image);
    }

    if (file_exists($target_dir . $old_banner)){
        chmod($target_dir . $old_banner, 777);
        unlink($target_dir . $old_banner);
    }

    if ($_FILES["shop_thumbnail"]["size"] > 500000){
        echo("Your file size is too large.");
        $uploadOk = 0;
    }

    if ($_FILES["shop_banner"]["size"] > 500000){
        echo("Your banner size is too large.");
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "webp"){
        echo("Only JPG, JPEG, PNG & GIF format file are allowed");
        $uploadOk = 0;
    }

    if ($bannerFileType != "jpg" && $bannerFileType != "png" && $bannerFileType != "jpeg"
        && $bannerFileType != "gif" && $bannerFileType != "webp"){
        echo("Only JPG, JPEG, PNG & GIF format banners are allowed");
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        
        echo("Your file and banners do not match.");

    }else{

        // IF ALL SUITABLE UPDATE FROM SHOP

        print_r($target_file.$old_image);
        print_r($target_banner.$old_image);

        if (move_uploaded_file($_FILES["shop_thumbnail"]["tmp_name"], $target_file) && 
        move_uploaded_file($_FILES["shop_banner"]["tmp_name"], $target_banner)) {

            $query = "UPDATE SHOP SET NAME = '$name', DESCRIPTION = '$description', THUMB_ID = 
                        '$thumb_id', LINK = '$website', BANNER_ID = '$banner_id' WHERE 
                        CODE = '".$code."'";

            if (mysqli_query($dbconn, $query)){
                header("Location: ../../pages/tab5-shop-manager.php");
            }else{
                echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
            }

        }else {
            echo("File match but can't upload.".$_FILES["shop_thumbnail"]["error"]);
        }
    }

}
else if($thumb_id!==""){

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["shop_thumbnail"]["tmp_name"]);

        if ($check !== false){
          echo("File is an image - " . $check["mime"] . ".");
          $uploadOk = 1;
        }else{
          echo("File is not an image.");
          $uploadOk = 0;
        }
    }
      
    if (file_exists($target_dir . $old_image)){
        chmod($target_dir . $old_image, 777);
        unlink($target_dir . $old_image);
    }
    
    if ($_FILES["shop_thumbnail"]["size"] > 500000){
        echo("Your file size is too large.");
        $uploadOk = 0;
    }
    
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "webp"){
        echo("Only JPG, JPEG, PNG & GIF format photos are allowed");
        $uploadOk = 0;
    }

    if ($uploadOk == 0){
        
        echo("Your file doesn't match.");

    }else{

        // IF ALL SUITABLE UPLOAD FILE & UPDATE SHOP

        print_r($target_file.$old_image);

        if (move_uploaded_file($_FILES["shop_thumbnail"]["tmp_name"], $target_file)){

            $query = "UPDATE SHOP SET NAME = '$name', DESCRIPTION = '$description', THUMB_ID = 
                        '$thumb_id', LINK = '$website' WHERE CODE = '".$code."'";

            if (mysqli_query($dbconn, $query)){
                header("Location: ../../pages/tab5-shop-manager.php");
            }else{
                echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
            }

        }else{
            echo("File match but can't upload.".$_FILES["shop_thumbnail"]["error"]);
        }
    }
}else if($banner_id!==""){

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["shop_banner"]["tmp_name"]);

        if ($check !== false){
          echo("File is an image - " . $check["mime"] . ".");
          $uploadOk = 1;
        }else{
          echo("File is not an image.");
          $uploadOk = 0;
        }
    }
      
    if (file_exists($target_dir . $old_banner)){
        chmod($target_dir . $old_banner, 777);
        unlink($target_dir . $old_banner);
    }
    
    if ($_FILES["shop_banner"]["size"] > 500000){
        echo("Your file size is to large.");
        $uploadOk = 0;
    }
    
    if ($bannerFileType != "jpg" && $bannerFileType != "png" && $bannerFileType != "jpeg"
        && $bannerFileType != "gif" && $bannerFileType != "webp"){
        echo("Only JPG, JPEG, PNG & GIF format photos are allowed");
        $uploadOk = 0;
    }

    if ($uploadOk == 0){
        
        echo("Your photo doesn't match.");

    }else{

        // IF ALL SUITABLE UPLOAD FILE AND UPDATE SHOP

        print_r($target_banner.$old_banner);

        if (move_uploaded_file($_FILES["shop_banner"]["tmp_name"], $target_banner)){

            $query = "UPDATE SHOP SET NAME = '$name', DESCRIPTION = '$description', BANNER_ID = 
                        '$banner_id', LINK = '$website' WHERE CODE = '".$code."'";

            if (mysqli_query($dbconn, $query)){
                header("Location: ../../pages/tab5-shop-manager.php");
            }else{
                echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
            }

        }else{
            echo("File match but can't upload.".$_FILES["shop_thumbnail"]["error"]);
        }
    }
}else{

    // IF NO CHANGED OF PHOTO

    $query = "UPDATE SHOP SET NAME = '$name', DESCRIPTION = '$description', LINK = '$website' 
                WHERE CODE = '".$code."'";

    if (mysqli_query($dbconn, $query)){
        header("Location: ../../pages/tab5-shop-manager.php");
    }else{
        echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
    }

}

?>
