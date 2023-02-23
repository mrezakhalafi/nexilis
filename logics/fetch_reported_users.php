<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$rows = array();
    $sql = " 
    SELECT 
    rp.F_PIN_REPORTED, 
   COUNT(rp.F_PIN_REPORTED) AS TOTAL_REPORTS
  FROM 
    REPORT_USER rp 
GROUP BY rp.F_PIN_REPORTED
        ";
        // echo $sql;
    $query = $dbconn->prepare($sql);
    // $query = $dbconn->prepare("SELECT c1.PRODUCT_CODE FROM PRODUCT_COMMENT c1 LEFT JOIN PRODUCT_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.F_PIN = ?");
    // $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[$group["F_PIN_REPORTED"]] = array(
            "TOTAL_REPORTS" => $group["TOTAL_REPORTS"]
        );
    }
// //         echo "<pre>";
// // print_r($rows);
// // echo "</pre>";

// $key = "02664679091642490145429";
// // print_r($rows[$key]);
// echo $rows[$key]["TOTAL_COMMENTS"];
return $rows;
