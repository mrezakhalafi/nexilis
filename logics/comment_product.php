<?php 

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    // session_start();

    $dbconn = paliolite();

    $product_code = $_POST['product_code'];
    $comment = $_POST['comment'];
    $last_update = $_POST['last_update'];
    $discussion_id = $_POST['discussion_id'];
    if (isset($_SESSION["f_pin"])) {
        $f_pin = $_SESSION["f_pin"];
    } else {
        $f_pin = $_POST['f_pin'];
    
        $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
        $query = $dbconn->prepare($queryCheckFpin);
        $query->execute();
        $getNewFPIN = $query->get_result()->fetch_assoc();
        $query->close();
    
        $f_pin = $getNewFPIN["F_PIN"];
        $_SESSION["f_pin"] = $f_pin;
    }
    // $f_pin = $_SESSION["f_pin"];
    $reply_id = null;
    if (isset($_POST['reply_id'])) {
        $reply_id = $_POST['reply_id'];
    }
    $comment_id = $f_pin . $last_update;
    $is_post = $_POST['is_post'];

    try {

        if ($is_post == 0) {
            // check for first comment
            $query_one = $dbconn->prepare("SELECT COUNT(*) as CNT FROM LINK_COMMENT WHERE LINK_ID = ?");
            $query_one->bind_param("s", $product_code);
            $query_one->execute();
            $q_res = $query_one->get_result()->fetch_assoc();
            $comment_count = $q_res['CNT'];
            $query_one->close();

            // echo 'comment count: ' . $comment_count;
            // echo "<br>";

            // if product doesn't have any comments yet, insert into PRD_DISCUSSION
            if ($comment_count == 0) {
                // $query = $dbconn->prepare("INSERT INTO PRD_DISCUSSION (DISCUSSION_ID, PRODUCT_CODE, F_PIN, CREATED_DATE) VALUES (?,?,?,?)");
                // $query->bind_param("ssss", $discussion_id, $product_code, $f_pin, $last_update);
                // $query->execute();
                // $query->close();

                // $query = $dbconn->prepare("INSERT INTO POST_COMMENT (COMMENT_ID, POST_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                // $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                $query = $dbconn->prepare("INSERT INTO LINK_COMMENT (COMMENT_ID, LINK_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                $status = $query->execute();
                $query->close();

                // echo "blabla";
            } else {
                // $query = $dbconn->prepare("INSERT INTO POST_COMMENT (COMMENT_ID, POST_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                // $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                $query = $dbconn->prepare("INSERT INTO LINK_COMMENT (COMMENT_ID, LINK_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                $status = $query->execute();
                $query->close();

                // echo "else bla";
            }      
        } else {
            $query_one = $dbconn->prepare("SELECT COUNT(*) as CNT FROM POST_COMMENT WHERE POST_ID = ?");
            $query_one->bind_param("s", $product_code);
            $query_one->execute();
            $q_res = $query_one->get_result()->fetch_assoc();
            $comment_count = $q_res['CNT'];
            $query_one->close();

            // echo 'comment count: ' . $comment_count;
            // echo "<br>";

            // if product doesn't have any comments yet, insert into PRD_DISCUSSION
            if ($comment_count == 0) {
                // $query = $dbconn->prepare("INSERT INTO PRD_DISCUSSION (DISCUSSION_ID, PRODUCT_CODE, F_PIN, CREATED_DATE) VALUES (?,?,?,?)");
                // $query->bind_param("ssss", $discussion_id, $product_code, $f_pin, $last_update);
                // $query->execute();
                // $query->close();

                $query = $dbconn->prepare("INSERT INTO POST_COMMENT (COMMENT_ID, POST_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                // $query = $dbconn->prepare("INSERT INTO LINK_COMMENT (COMMENT_ID, LINK_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                // $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                $status = $query->execute();
                $query->close();

                // echo "blabla";
            } else {
                $query = $dbconn->prepare("INSERT INTO POST_COMMENT (COMMENT_ID, POST_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                // $query = $dbconn->prepare("INSERT INTO LINK_COMMENT (COMMENT_ID, LINK_ID, F_PIN, COMMENT, CREATED_DATE, REF_COMMENT_ID) VALUES (?,?,?,?,?,?)");
                // $query->bind_param("ssssss", $comment_id, $product_code, $f_pin, $comment, $last_update, $reply_id);
                $status = $query->execute();
                $query->close();

                // echo "else bla";
            }
        }
        

        echo 'Success Comment';

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>