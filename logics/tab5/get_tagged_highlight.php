<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET DATA FROM FORM

$product_code = $_POST['product_code'];

// SELECT TAGGED PRODUCT FROM PRODUCT

$query = $dbconn->prepare("SELECT PRODUCT.*, SHOP.NAME AS SNAME FROM PRODUCT LEFT JOIN SHOP ON SHOP.CODE = PRODUCT.SHOP_CODE WHERE PRODUCT.CODE = '$product_code'");
$query->execute();
$tagged_product = $query->get_result();
$query->close();

// CONVERT OBJECT TO ROWS

$rows = [];
while ($row = $tagged_product->fetch_assoc()){
    $rows[] = $row;
}

// SELECT TAGGED PRODUCT FROM POST

$query = $dbconn->prepare("SELECT POST.*, SHOP.NAME AS SNAME, POST.TITLE AS NAME, POST.PRICING_MONEY AS PRICE FROM POST LEFT JOIN SHOP ON SHOP.CODE = POST.MERCHANT WHERE POST.POST_ID = '$product_code'");
$query->execute();
$tagged_product = $query->get_result();
$query->close();

// CONVERT OBJECT TO ROWS

while ($row = $tagged_product->fetch_assoc()){
    $rows[] = $row;
}

// IF DATA EXIST RETURN DATA

if (isset($rows)){
    echo(json_encode($rows));
}else{
    echo("");
}

?>