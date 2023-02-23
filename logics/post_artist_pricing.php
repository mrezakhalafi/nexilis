<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();

    $artist_code = $_POST['artist_code'];
    $price_weekday = $_POST['price_weekday'];
    $price_weekend = $_POST['price_weekend'];

    try {
        $query = $dbconn->prepare("INSERT INTO ARTIST_PRICING (`ARTIST_CODE`,`TIME_INDEX`,`PRICE`) VALUES (?,'weekday',?) ON DUPLICATE KEY UPDATE PRICE = ?");
        $query->bind_param("sss", $artist_code, $price_weekday, $price_weekday);
        $status = $query->execute();
        $query->close();

        $query = $dbconn->prepare("INSERT INTO ARTIST_PRICING (`ARTIST_CODE`,`TIME_INDEX`,`PRICE`) VALUES (?,'weekend',?) ON DUPLICATE KEY UPDATE PRICE = ?");
        $query->bind_param("sss", $artist_code, $price_weekend, $price_weekend);
        $status = $query->execute();
        $query->close();

        echo 'Success';

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>