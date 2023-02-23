<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

// GET FROM DETAIL ORDERS FORM

$product_code = $_POST['product_code'];
$collection_code = $_POST['collection_code'];
$is_post = $_POST['is_post'];

// INSERT TO COLLECTION PRODUCT

$query = "INSERT INTO COLLECTION_PRODUCT (COLLECTION_CODE, PRODUCT_CODE, IS_POST) VALUES ('".$collection_code."',
            '".$product_code."','".$is_post."')";

if (mysqli_query($dbconn, $query)){

    if ($_GET['src'] == 'wishlist'){
        header("Location: ../../pages/tab5-collection-wishlist?collection_code=".$collection_code);
    }else{
        header("Location: ../../pages/tab5-collection-self?collection_code=".$collection_code);
    }

}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>