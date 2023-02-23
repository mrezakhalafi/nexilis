<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$rows = array();
    $sql = "
          SELECT 
            c1.POST_ID AS PRODUCT_CODE, 
           1 AS IS_POST,
           COUNT(c1.POST_ID) AS TOTAL_COMMENT
          FROM 
            POST_COMMENT c1 
            LEFT JOIN POST_COMMENT c2 ON c1.REF_COMMENT_ID = c2.COMMENT_ID 
          WHERE 
            (
              c1.REF_COMMENT_ID IS NULL 
              OR c2.ID IS NOT NULL
            ) 
            AND c1.IS_DELETE IS NOT NULL
            GROUP BY c1.POST_ID
      
    ";
    $query = $dbconn->prepare($sql);
    // $query = $dbconn->prepare("SELECT c1.PRODUCT_CODE FROM PRODUCT_COMMENT c1 LEFT JOIN PRODUCT_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.F_PIN = ?");
    // $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[$group["PRODUCT_CODE"]] = array(
            "IS_POST" => $group["IS_POST"],
            "TOTAL_COMMENTS" => $group["TOTAL_COMMENT"]
        );
    }
// //         echo "<pre>";
// // print_r($rows);
// // echo "</pre>";

// $key = "02664679091642490145429";
// // print_r($rows[$key]);
// echo $rows[$key]["TOTAL_COMMENTS"];
return $rows;
?>