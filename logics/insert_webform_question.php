<?php 

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbNewNus = newnus();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    $f_pin = $_POST['f_pin'];

    // GET FROM DF QUESTION 

    $form_code = rand(100000000,999999999);
    $title = $_POST['title'];
    $description = $_POST['description'];

    $total_question = $_POST['total-question'];

    $regis_fee = $_POST['regis_fee'];
    $admin_fee = $_POST['admin_fee'];

    // INSERT INTO DF QUESTION

    for ($i=1; $i<($total_question+1); $i++){

        $question = $_POST['question-'.$i];
        $question_type = $_POST['question-type-'.$i];

        $form_sub = $i;

        $value = null;

        if ($question_type == 2){

            $total_radio = $_POST['total-radio-'.$i];

            for($j=1; $j<($total_radio+1); $j++){

                if ($value != null){
                    $value .= "|".$_POST['radio-'.$i.'-number-'.$j];
                }else{
                    $value = $_POST['radio-'.$i.'-number-'.$j];
                }
            }

        }else if($question_type == 3){
            
            $total_checkbox = $_POST['total-checkbox-'.$i];

            for($j=1; $j<($total_checkbox+1); $j++){

                if ($value != null){
                    $value .= "|".$_POST['checkbox-'.$i.'-number-'.$j];
                }else{
                    $value = $_POST['checkbox-'.$i.'-number-'.$j];
                }
            }

        }else if($question_type == 6){
            
            $total_select = $_POST['total-select-'.$i];

            for($j=1; $j<($total_select+1); $j++){

                if ($value != null){
                    $value .= "|".$_POST['select-'.$i.'-number-'.$j];
                }else{
                    $value = $_POST['select-'.$i.'-number-'.$j];
                }
            }

        }

        $query = "INSERT INTO WEBFORM_QUESTION (FORM_CODE, F_PIN, TITLE, DESCRIPTION, FORM_SUB, TYPE, QUESTION, VALUE, REGIS_FEE, ADMIN_FEE) VALUES 
                ('".$form_code."','".$f_pin."','".$title."','".$description."','".$form_sub."','".$question_type."','".$question."','".$value."','".$regis_fee."','".$admin_fee."')";

        if (mysqli_query($dbNewNus, $query)){
            echo("Success");
        }else{
            echo("Failed");
            http_response_code(400);
        }
    
    }
?>
