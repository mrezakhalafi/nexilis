<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET USER PIN

session_start();

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

// GET FROM POST FORM

$caption = $_POST['caption'];
$title = $_POST['title'];
$time = $_POST['time'];
$post_link = $_POST['post_link'];
$post_id = $_POST['post_id'];

$category = $_POST['category'];
$parent_category = $_POST['parent_category'];

// GET FROM PRODUCT FORM

$old_price = $_POST['price'];
$price = preg_replace('/[^0-9]/', '', $old_price);

$old_quantity = $_POST['stock'];
$quantity = preg_replace('/[^0-9]/', '', $old_quantity);

// SEPARATE

$post_check = $_POST['post_check'];
$old_thumb_id = $_POST['old_thumb_id'];
$old_thumbnail = $_POST['old_thumbnail'];
$array_changed_photo = $_POST['array_changed_photo'];
$array_changed_thumbnail = $_POST['array_changed_thumbnail'];
$deleted_thumb_id = $_POST['deleted_thumb_id'];
$deleted_thumbnail = $_POST['deleted_thumbnail'];

$file_type = 1;


$post_check = $_POST['post_check'];

// $title = substr($caption,0,32);

// FOR OLD COPY FROM POST TO PRODUCT

$query = $dbconn->prepare("SELECT * FROM POST WHERE POST_ID = '$post_id'");
$query->execute();
$edit_data = $query->get_result()->fetch_assoc();
$query->close();

// CHECK IS THIS NEW PRODUCT OR EDITING PRODUCT

$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE CODE = '$post_id'");
$query->execute();
$edit_data_product = $query->get_result()->fetch_assoc();
$query->close();

if (isset($post_link) && $post_link != "" && substr($post_link, 0, 4) != "http") {
    $post_link = "https://" . $post_link;
}

// $test = $edit_data['EC_DATE'] === null;
// echo $test;
// echo ' | ';


// echo $edit_data['EC_DATE'] . ' ';
// echo $edit_data_product['IS_DELETED'];

// $prevStatus = 0;

$prevStatus = 0;

if ($edit_data['EC_DATE'] === null && $edit_data_product['IS_DELETED'] == 1) {
    // echo 'prev0';
    $prevStatus = 0;
} else if ($edit_data['EC_DATE'] !== null && $edit_data_product['IS_DELETED'] == 0) {
    // echo 'prev1';
    $prevStatus = 1;
} else if ($edit_data['EC_DATE'] === null && $edit_data_product['IS_DELETED'] == 0) {
    // echo 'prev2';
    $prevStatus = 2;
}

// echo 'prevstatus ' . $prevStatus;
// echo '<br>';
// echo 'postcheck '. $post_check;

// if ($edit_data && !$edit_data_product) {
//     $prevStatus = 0;
// } else if (!$edit_data && $edit_data_product) {
//     $prevStatus = 1;
// } else if ($edit_data && $edit_data_product) {
//     $prevStatus = 2;
// }

// SET IMAGE DIRECTORY

// $filename = $f_pin . time();

// STATUS
// 0 = post Only
// 1 = product Only
// 2 = post + product

$file_arr = explode("|",$old_thumb_id);
$thumb_arr = explode("|",$old_thumbnail);

$currentTime = time() * 1000;

// CHECK IF IMAGE EXIST
if ($array_changed_photo != "") {

    // CHANGED PHOTO

    $number = 0;
    $listing_thumbnail = "";

    // START BIG FOR IMAGE

    $new_array_changed_photo = explode(',', $array_changed_photo);

    // COUNT AS CHANGED SLOT NUMBER
    

    for ($number = 0; $number < count($new_array_changed_photo); $number++) {

        // SET IMAGE DIRECTORY

        $array_loop = $new_array_changed_photo[$number];

        $filename = $f_pin . $time . "-$array_loop";
        $thumbname = "T_" . $f_pin . $time . "-$array_loop";

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
                echo ("File is an image - " . $check["mime"] . ".");
                $uploadOk = 1;
            } else {
                echo ("File is not an image.");
                $uploadOk = 0;
            }
        }

        // CHECK IF IMAGE EXIST

        if (file_exists($target_file)) {
            echo ("Sorry, file already exists.");
            $uploadOk = 0;
        }

        // CHECK IMAGE SIZE

        if ($_FILES["listing_thumbnail-$array_loop"]["size"] > 5000000) {
            echo ("Your file size is too large.");
            $uploadOk = 0;
        }

        // CHECK IMAGE FORMAT

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "webp" && $imageFileType != "mp4"
        ) {
            echo ("Only JPG, JPEG, PNG, WEBP & GIF format photos and MP4 video formats are allowed. Now : " . $_FILES["listing_thumbnail-$array_loop"]);

            $uploadOk = 0;
        }

        // CHECK IMAGE VALIDATION AND UPLOAD IT

        if ($uploadOk == 0) {
            echo ("Your file doesn't match.");
        } else {

            if (move_uploaded_file($_FILES["listing_thumbnail-$array_loop"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["thumbnail-$array_loop"]["tmp_name"], $target_thumb)) {
                array_push($file_arr, $filename . "." . $imageFileType);
                array_push($thumb_arr, $thumbname . "." . $thumbFileType);
                $allImagesUploaded = 1;
            } else {
                echo ("File match but can't upload." . $_FILES["listing_thumbnail-$array_loop"]["error"]);
            }
        }

        // DATABASE THUMB_ID FILLED WITH OLD THUMB_ID AND NEW NAME

        // echo "ADD NEW \n";
        $new_listing_thumbnail = implode("|", $file_arr);
        $new_listing_thumb = implode("|", $thumb_arr);
        // echo $new_listing_thumbnail . "\n";
        // echo $new_listing_thumb . "\n";
        // END BIG FOR IMAGE

    }

    // IF USER UPLOAD NEW IMAGE AND DELETE IMAGE TOO

    if ($deleted_thumb_id != null) {

        $deleted_arr = explode("|", $deleted_thumb_id);
        $deleted_thumb_arr = explode("|", $deleted_thumbnail);


        $new_arr = array_diff($file_arr, $deleted_arr);
        $new_arr_th = array_diff($thumb_arr ,$deleted_thumb_arr);

        $new_listing_thumbnail = implode("|", $new_arr);
        $new_listing_thumb = implode("|", $new_arr);

        // echo "DELETE \n";
        // echo $new_listing_thumbnail . "\n";
        // echo $new_listing_thumb . "\n";
    }

    // DELETE IMAGE FROM DATABASE

    // AFTER ALL SUITABLE THEN UPLOAD FILE AND UPDATE PRODUCT & PRODUCT SHIPMENT DETAIL

    if ($allImagesUploaded == 1) {

        if ($prevStatus == 0) {
            if ($post_check == 0) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                if (mysqli_query($dbconn, $queryPost)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            } else if ($post_check == 1) {

                $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            } else if ($post_check == 2) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            }
        } else if ($prevStatus == 1) {
            if ($post_check == 0) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                $queryProduct = "UPDATE PRODUCT SET IS_DELETED = '1', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            } else if ($post_check == 1) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";
                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            } else if ($post_check == 2) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            }
        } else if ($prevStatus == 2) {
            if ($post_check == 0) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";
                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '1', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryProduct) && mysqli_query($dbconn, $queryPost)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            } else if ($post_check == 1) {
                $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            } else if ($post_check == 2) {
                $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_thumbnail', THUMB_ID = '$new_listing_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

                $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

                if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                    echo ("Berhasil");
                } else {
                    echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
                }
            }
        }
    } else {
        echo ("The file is suitable but not uploaded successfully." . $_FILES["post_photo"]["error"]);
    }
} else if ($deleted_thumb_id != null) { // deleted image
    
    $deleted_arr = explode("|", $deleted_thumb_id);
    $deleted_thumb_arr = explode("|", $deleted_thumbnail);

    $new_arr = array_diff($file_arr, $deleted_arr);
    $new_arr_th = array_diff($thumb_arr ,$deleted_thumb_arr);

    $new_listing_deleted = implode("|", $new_arr);
    $new_listing_deleted_thumb = implode("|", $new_arr_th);

    // echo "DELETEONLY \n";
    // echo $new_listing_deleted . "\n";
    // echo $new_listing_deleted_thumb . "\n";
    

    if ($prevStatus == 0) {
        if ($post_check == 0) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            if (mysqli_query($dbconn, $queryPost)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 1) {

            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 2) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    } else if ($prevStatus == 1) {
        if ($post_check == 0) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET IS_DELETED = '1', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 1) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";
            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 2) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    } else if ($prevStatus == 2) {
        if ($post_check == 0) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";
            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '1', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryProduct) && mysqli_query($dbconn, $queryPost)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 1) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', FILE_TYPE = $file_type, NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 2) {
            $queryPost = "UPDATE POST SET FILE_ID = '$new_listing_deleted', THUMB_ID = '$new_listing_deleted_thumb', TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_deleted', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            // echo $queryProduct;

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    }
} else { // without image

    if ($prevStatus == 0) {
        if ($post_check == 0) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            if (mysqli_query($dbconn, $queryPost)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 1) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $new_listing_thumbnail = $edit_data['FILE_ID'];
            $file_type = $edit_data['FILE_TYPE'];

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            // $queryProduct =     "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
            //                 DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
            //                 REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('" . $post_id . "','0','" . $title . "','" . (time() * 1000) . "',
            //                 '" . $f_pin . "','" . $caption . "','" . $edit_data['FILE_ID'] . "','0','0',
            //                 '0','" . $price . "','1','" . $edit_data['FILE_TYPE'] . "','0','" . $quantity . "','Regular','" . $post_check . "')";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 2) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $new_listing_thumbnail = $edit_data['FILE_ID'];
            $file_type = $edit_data['FILE_TYPE'];

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            // $queryProduct =     "INSERT INTO PRODUCT (CODE, MERCHANT_CODE, NAME, CREATED_DATE, SHOP_CODE, 
            //                 DESCRIPTION, THUMB_ID, CATEGORY, SCORE, TOTAL_LIKES, PRICE, IS_SHOW, FILE_TYPE, 
            //                 REWARD_POINT, QUANTITY, VARIATION, IS_POST) VALUES ('" . $post_id . "','0','" . $title . "','" . (time() * 1000) . "',
            //                 '" . $f_pin . "','" . $caption . "','" . $edit_data['FILE_ID'] . "','0','0',
            //                 '0','" . $price . "','1','" . $edit_data['FILE_TYPE'] . "','0','" . $quantity . "','Regular','" . $post_check . "')";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    } else if ($prevStatus == 1) {
        if ($post_check == 0) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT IS_DELETED = '1', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 1) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";
            $new_listing_thumbnail = $edit_data['FILE_ID'];
            $file_type = $edit_data['FILE_TYPE'];

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 2) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL WHERE POST_ID = '$post_id'";
            $new_listing_thumbnail = $edit_data['FILE_ID'];
            $file_type = $edit_data['FILE_TYPE'];

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";
            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    } else if ($prevStatus == 2) {
        if ($post_check == 0) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            // $queryProduct = "UPDATE PRODUCT SET NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '1' WHERE CODE = '$post_id'";
            $new_listing_thumbnail = $edit_data['FILE_ID'];
            $file_type = $edit_data['FILE_TYPE'];

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '1', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 1) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = $time, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            // $queryProduct = "UPDATE PRODUCT SET NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0' WHERE CODE = '$post_id'";
            $new_listing_thumbnail = $edit_data['FILE_ID'];
            $file_type = $edit_data['FILE_TYPE'];

            $queryProduct = "UPDATE PRODUCT SET THUMB_ID = '$new_listing_thumbnail', FILE_TYPE = '$file_type', NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        } else if ($post_check == 2) {
            $queryPost = "UPDATE POST SET TITLE = '$title', DESCRIPTION = '$caption', LINK = '$post_link', LAST_UPDATE = $time, EC_DATE = NULL, CATEGORY = '$category', PARENT = '$parent_category' WHERE POST_ID = '$post_id'";

            $queryProduct = "UPDATE PRODUCT SET NAME = '$title', DESCRIPTION = '$caption', PRICE = '$price', QUANTITY = '$quantity', IS_DELETED = '0', CATEGORY = '$category', PARENT = '$parent_category' WHERE CODE = '$post_id'";

            if (mysqli_query($dbconn, $queryPost) && mysqli_query($dbconn, $queryProduct)) {
                echo ("Berhasil");
            } else {
                echo ("Data failed to add. $sql. " . mysqli_error($dbconn));
            }
        }
    }
}
