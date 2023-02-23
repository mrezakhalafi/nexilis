<?php

// SELECT VIEWS FROM THIS DAY

$day_website1 = 0;
$day_website2 = 0;
$day_website3 = 0;
$day_website4 = 0;
$day_website5 = 0;
$day_website6 = 0;

// DAY 1

$day1 = date('Y-m-d');

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day1."%'"); 
$query->execute();
$website_day1 = $query->get_result();
$query->close();

$day_website1 = mysqli_num_rows($website_day1);

// DAY 2

$day2 = date('Y-m-d',strtotime("-1 day"));

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day2."%'"); 
$query->execute();
$website_day2 = $query->get_result();
$query->close();

$day_website2 = mysqli_num_rows($website_day2);

// DAY 3

$day3 = date('Y-m-d',strtotime("-2 day"));

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day3."%'"); 
$query->execute();
$website_day3 = $query->get_result();
$query->close();

$day_website3 = mysqli_num_rows($website_day3);

// DAY 4

$day4 = date('Y-m-d',strtotime("-3 day"));

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day4."%'"); 
$query->execute();
$website_day4 = $query->get_result();
$query->close();

$day_website4 = mysqli_num_rows($website_day4);

// DAY 5

$day5 = date('Y-m-d',strtotime("-4 day"));

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day5."%'"); 
$query->execute();
$website_day5 = $query->get_result();
$query->close();

$day_website5 = mysqli_num_rows($website_day5);

// DAY 6

$day6 = date('Y-m-d',strtotime("-5 day"));

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day6."%'"); 
$query->execute();
$website_day6 = $query->get_result();
$query->close();

$day_website6 = mysqli_num_rows($website_day6);

// DAY 7

$day7 = date('Y-m-d',strtotime("-6 day"));

$query = $dbconn->prepare("SELECT * FROM SHOP_WEBSITE WHERE SHOP_CODE =
                          '$id_shop' AND CLICK_DATE LIKE '%".$day7."%'"); 
$query->execute();
$website_day7 = $query->get_result();
$query->close();

$day_website7 = mysqli_num_rows($website_day7);

?>