<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');


if (isset($_GET['query'])) {
    $dbconn = paliolite();

    $que = "%" .$_GET['query'] . "%";

    $query = $dbconn->prepare("SELECT u.* FROM USER_LIST u left join BUSINESS_ENTITY be on u.BE = be.ID left join SHOP s on be.COMPANY_ID = s.PALIO_ID WHERE (u.BE = 128 OR s.IS_QIOSK = 1) AND CONCAT(u.FIRST_NAME,' ',u.LAST_NAME) LIKE '%".$que."%'");
    $query->execute();
    $users  = $query->get_result();
    $query->close();

    $rows = array();
    while ($shop = $users->fetch_assoc()) {
        $rows[] = $shop;
    };

    return $rows;
}
