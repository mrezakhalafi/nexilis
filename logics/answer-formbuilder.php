<?php 

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $form_id = $_POST['form_id'];
    $f_pin = $_POST['f_pin'];

    // GET FROM DF INPUT

    $bytes = random_bytes(8);
    $hexbytes = strtoupper(bin2hex($bytes));
    $ref_id = substr($hexbytes, 0, 15);

    // INSERT INTO FORM

    $query = "INSERT INTO FORM_SUBMIT (REF_ID, FORM_ID, F_PIN, CREATED_DATE, INSERT_DATE, SQ_NO) VALUES 
                ('".$ref_id."','".$form_id."','".$f_pin."','".date("Y-m-d h:i:s")."','".date("Y-m-d")."','1')";

    if (mysqli_query($dbconn, $query)){
        $last_id = $dbconn->insert_id;
        echo("Form Success");
    }else{
        echo("Form Failed");
        http_response_code(400);
    }

    // INSERT INTO FORM DETAIL

    $arr = array();
    $number = 0;
    $total_question = $_POST['total-question'];

    // LOOP THROUGH MANY QUESTION

    for ($i=0; $i<$total_question; $i++){

        if (isset($_POST['question-'.$i])) {
            $question = $_POST['question-'.$i];
        } else {
            $question = "off";
        }

        $question_type = $_POST['question-'.$i.'-type'];
        $question_label = $_POST['question-'.$i.'-label'];

        // IF TYPE MULTIPLE CHECKBOX

        if ($question_type == 8){

            $checkbox;

            foreach($question as $q){
                
                if ($checkbox){
                    $checkbox .= "|".$q;
                }else{
                    $checkbox = $q;
                }

            }
            $question = $checkbox;
        }

        if ($question_type == "14" || $question_type == "15" || $question_type == "24" || $question_type == "20"){

            // IF UPLOADED MEDIA / CONTENT / CANVAS DATA

            $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
            $imageFileType = strtolower(pathinfo($_FILES['question-'.$i]["name"], PATHINFO_EXTENSION));
            $target_file = $target_dir . $f_pin . time() . $number . "." . $imageFileType;

            $thumb_id = $f_pin . time() . $number . "." . $imageFileType;

            if (move_uploaded_file($_FILES['question-'.$i]["tmp_name"], $target_file)) {

                array_push($arr, "('" . $last_id . "', '" . $question_label . "', '" . $thumb_id . "')");
                $number = $number + 1;

            }else{

                array_push($arr, "('" . $last_id . "', '" . $question_label . "', '')");

            }

        }else{

            // IF ORDINARY DATA

            // IF TIME (CONCAT DATE AND TIME)

            if ($question_type == 2){

                $date = $_POST['question-'.$i];
                $time = $_POST['question-'.$i.'-second'];

                $question = $date."|".$time;

            }

            array_push($arr, "('" . $last_id . "', '" . $question_label . "', '" . $question . "')");

        }

    }

    // INSERT INTO DB

    $str = implode(",", $arr);

    $query = "INSERT INTO FORM_SUBMIT_DETAIL (`FORM_SUBMIT_ID`, `KEY`, `VALUE`) VALUES " . $str;

    if (mysqli_query($dbconn, $query)){
        echo("Detail Success");
    }else{
        echo("Detail Failed");
        http_response_code(400);
    }

?>
