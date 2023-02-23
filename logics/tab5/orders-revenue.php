<?php

// SELECT ORDER FROM THIS DAY

$day_income1 = 0;
$day_income2 = 0;
$day_income3 = 0;
$day_income4 = 0;
$day_income5 = 0;
$day_income6 = 0;

// DAY 1

$day1 = date('Y-m-d');

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day1."%'");
$query->execute();
$orders_day1 = $query->get_result();
$query->close();

foreach ($orders_day1 as $day1){
  $day_income1 = $day_income1 + ($day1['PRICE'] * $day1['AMOUNT']);
}

// DAY 2

$day2 = date('Y-m-d',strtotime("-1 day"));

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day2."%'");
$query->execute();
$orders_day2 = $query->get_result();
$query->close();

foreach ($orders_day2 as $day2){
  $day_income2 = $day_income2 + ($day2['PRICE'] * $day2['AMOUNT']);
}

// DAY 3

$day3 = date('Y-m-d',strtotime("-2 day"));

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day3."%'");
$query->execute();
$orders_day3 = $query->get_result();
$query->close();

foreach ($orders_day3 as $day3){
  $day_income3 = $day_income3 + ($day3['PRICE'] * $day3['AMOUNT']);
}

// DAY 4

$day4 = date('Y-m-d',strtotime("-3 day"));

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day4."%'");
$query->execute();
$orders_day4 = $query->get_result();
$query->close();

foreach ($orders_day4 as $day4){
  $day_income4 = $day_income4 + ($day4['PRICE'] * $day4['AMOUNT']);
}

// DAY 5

$day5 = date('Y-m-d',strtotime("-4 day"));

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day5."%'");
$query->execute();
$orders_day5 = $query->get_result();
$query->close();

foreach ($orders_day5 as $day5){
  $day_income5 = $day_income5 + ($day5['PRICE'] * $day5['AMOUNT']);
}

// DAY 6

$day6 = date('Y-m-d',strtotime("-5 day"));

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day6."%'");
$query->execute();
$orders_day6 = $query->get_result();
$query->close();

foreach ($orders_day6 as $day6){
  $day_income6 = $day_income6 + ($day6['PRICE'] * $day6['AMOUNT']);
}

// DAY 7

$day7 = date('Y-m-d',strtotime("-6 day"));

$query = $dbconn->prepare("SELECT * FROM PURCHASE WHERE MERCHANT_ID = '$id_shop' AND 
                          CREATED_AT LIKE '%".$day7."%'");
$query->execute();
$orders_day7 = $query->get_result();
$query->close();

foreach ($orders_day7 as $day7){
  $day_income7 = $day_income7 + ($day7['PRICE'] * $day7['AMOUNT']);
}

?>