<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dbconn = paliolite();
if(isset($code)) {
    $product_code = $code;
} else if(isset($_REQUEST['product_code'])){
    $product_code = $_REQUEST['product_code'];
}
if(isset($sub) || isset($comment_id)) {
    if(isset($sub)) {
        $queryReply = ${"comment_id_reff" . $sub};
    } else {
        $queryReply = $comment_id;
    }
}
// if(isset($_REQUEST['product_code'])){
//     $product_code = $_REQUEST['product_code'];
// } else {
//     $product_code = $code;
// }
if(isset($code) && !isset($_REQUEST['product_code'])) {
    // $query = $dbconn->prepare("SELECT c1.* FROM POST_COMMENT c1 LEFT JOIN POST_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.POST_ID = '$product_code'");
    $query = $dbconn->prepare("SELECT c1.* FROM LINK_COMMENT c1 LEFT JOIN LINK_COMMENT c2 on c1.REF_COMMENT_ID = c2.COMMENT_ID WHERE (c1.REF_COMMENT_ID IS NULL OR c2.ID IS NOT NULL) AND c1.LINK_ID = '$product_code'");
}
else if(!isset($queryReply)) {
    $query = $dbconn->prepare("SELECT * FROM LINK_COMMENT WHERE LINK_ID = '$product_code' AND REF_COMMENT_ID IS NULL");
} else {
    $query = $dbconn->prepare("SELECT * FROM LINK_COMMENT WHERE LINK_ID = '$product_code' AND REF_COMMENT_ID = '$queryReply'");
}
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

// echo json_encode($rows);

return $rows;
?>