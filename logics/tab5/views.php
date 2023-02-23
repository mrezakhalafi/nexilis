<?php

// SELECT VIEWS FROM THIS DAY

$day_views1 = 0;
$day_views2 = 0;
$day_views3 = 0;
$day_views4 = 0;
$day_views5 = 0;
$day_views6 = 0;

// DAY 1

$day1 = date('Y-m-d');

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day1."%'"); 
$query->execute();
$views_day1 = $query->get_result();
$query->close();

$day_views1 = mysqli_num_rows($views_day1);

// DAY 2

$day2 = date('Y-m-d',strtotime("-1 day"));

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day2."%'"); 
$query->execute();
$views_day2 = $query->get_result();
$query->close();

$day_views2 = mysqli_num_rows($views_day2);

// DAY 3

$day3 = date('Y-m-d',strtotime("-2 day"));

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day3."%'"); 
$query->execute();
$views_day3 = $query->get_result();
$query->close();

$day_views3 = mysqli_num_rows($views_day3);

// DAY 4

$day4 = date('Y-m-d',strtotime("-3 day"));

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day4."%'"); 
$query->execute();
$views_day4 = $query->get_result();
$query->close();

$day_views4 = mysqli_num_rows($views_day4);

// DAY 5

$day5 = date('Y-m-d',strtotime("-4 day"));

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day5."%'"); 
$query->execute();
$views_day5 = $query->get_result();
$query->close();

$day_views5 = mysqli_num_rows($views_day5);

// DAY 6

$day6 = date('Y-m-d',strtotime("-5 day"));

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day6."%'"); 
$query->execute();
$views_day6 = $query->get_result();
$query->close();

$day_views6 = mysqli_num_rows($views_day6);

// DAY 7

$day7 = date('Y-m-d',strtotime("-6 day"));

$query = $dbconn->prepare("SELECT * FROM STORE_VISIT WHERE SHOP_CODE =
                          '$id_shop' AND VISIT_DATE LIKE '%".$day7."%'"); 
$query->execute();
$views_day7 = $query->get_result();
$query->close();

$day_views7 = mysqli_num_rows($views_day7);

?>