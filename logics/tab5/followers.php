<?php

// SELECT FOLLOWERS FROM THIS DAY

$day_followers1 = 0;
$day_followers2 = 0;
$day_followers3 = 0;
$day_followers4 = 0;
$day_followers5 = 0;
$day_followers6 = 0;

// DAY 1

$day1 = date('Y-m-d');

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day1."%'"); 
$query->execute();
$followers_day1 = $query->get_result();
$query->close();

$day_followers1 = mysqli_num_rows($followers_day1);

// DAY 2

$day2 = date('Y-m-d',strtotime("-1 day"));

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day2."%'"); 
$query->execute();
$followers_day2 = $query->get_result();
$query->close();

$day_followers2 = mysqli_num_rows($followers_day2);

// DAY 3

$day3 = date('Y-m-d',strtotime("-2 day"));

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day3."%'"); 
$query->execute();
$followers_day3 = $query->get_result();
$query->close();

$day_followers3 = mysqli_num_rows($followers_day3);

// DAY 4

$day4 = date('Y-m-d',strtotime("-3 day"));

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day4."%'"); 
$query->execute();
$followers_day4 = $query->get_result();
$query->close();

$day_followers4 = mysqli_num_rows($followers_day4);

// DAY 5

$day5 = date('Y-m-d',strtotime("-4 day"));

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day5."%'"); 
$query->execute();
$followers_day5 = $query->get_result();
$query->close();

$day_followers5 = mysqli_num_rows($followers_day5);

// DAY 6

$day6 = date('Y-m-d',strtotime("-5 day"));

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day6."%'"); 
$query->execute();
$followers_day6 = $query->get_result();
$query->close();

$day_followers6 = mysqli_num_rows($followers_day6);

// DAY 7

$day7 = date('Y-m-d',strtotime("-6 day"));

$query = $dbconn->prepare("SELECT * FROM palio_lite.SHOP_FOLLOW WHERE STORE_CODE =
                          '$id_shop' AND FROM_UNIXTIME(FOLLOW_DATE/1000,'%Y-%m-%d') 
                          LIKE '%".$day7."%'"); 
$query->execute();
$followers_day7 = $query->get_result();
$query->close();

$day_followers7 = mysqli_num_rows($followers_day7);

?>