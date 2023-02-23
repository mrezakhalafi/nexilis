<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    // GET FROM POST 

    $id = $_POST['id'];
    $f_pin = $_POST['f_pin'];
    $app_type = $_POST['app_type'];
    $app_company_name = $_POST['app_company_name'];
    $app_company_address = $_POST['app_company_address'];
    $app_email = $_POST['app_email'];
    $app_phone = $_POST['app_phone'];
    $app_ektp = $_POST['app_ektp'];
    $app_npwp = $_POST['app_npwp'];

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
    $target_files = array();

    $imageFileType = strtolower(pathinfo($_FILES["app_ektp_photo"]["name"],PATHINFO_EXTENSION));
    $fotoEktp = "FL_EKTP-".$f_pin . time() . ".webp";
    $target_files['app_ektp_photo'] = $target_dir . $fotoEktp;

    $imageFileType = strtolower(pathinfo($_FILES["app_self_photo"]["name"],PATHINFO_EXTENSION));
    $pasFoto = "FL_FP-".$f_pin . time() . ".webp";
    $target_files['app_self_photo'] = $target_dir . $pasFoto;

    $start_upload = true;
    $start_upload = move_uploaded_file($_FILES["app_ektp_photo"]["tmp_name"], $target_files["app_ektp_photo"]);
    $start_upload = move_uploaded_file($_FILES["app_self_photo"]["tmp_name"], $target_files["app_self_photo"]);

    if ($start_upload){

        $connection = ssh2_connect('202.158.33.26', 2309);
        ssh2_auth_password($connection, 'easysoft', '*347e^!VU4y+#hAP');

        if (ssh2_scp_send($connection, $target_files['app_self_photo'], '/apps/lcs/paliolite/server/image/' . $pasFoto, 0777) &&
        ssh2_scp_send($connection, $target_files['app_ektp_photo'], '/apps/lcs/paliolite/server/image/' . $fotoEktp, 0777)) {
            // echo ("Success Copy");
        }

        $datetime = time();
        $newDate = date('Y-m-d H:i:s', $datetime);
        $sqlValues = [];
        $verifiedType = "";

        if ($_POST['app_type'] == 1){

            $verifiedType = "OA";

        }else if($_POST['app_type'] == 2){

            $verifiedType = "VA";

        }

        $queryHeader = "INSERT INTO REQUEST_HEADER (ID, CODE, F_PIN, `DATE`, `STATUS`) VALUES ('$id', '$verifiedType','$f_pin','$newDate', 0)";
        // echo ($queryHeader);

        if (mysqli_query($dbconn, $queryHeader)){

            // echo("Berhasil");
            // $last_id = $dbconn->insert_id;

            foreach (array_keys($_POST) as $data){

                $value = $_POST[$data];

                if ($data != 'f_pin' && $data != 'profile_radio' && $data != 'ektp_radio' && $data != 'app_type' && $data != 'id' && $value != ''){

                    array_push($sqlValues, "('$id', '$data', '$value')");

                }
        
            }

            if ($pasFoto){

                array_push($sqlValues, "('$id', 'app_self_photo', '$pasFoto')");

            }

            if ($fotoEktp){

                array_push($sqlValues, "('$id', 'app_ektp_photo', '$fotoEktp')");

            }

            $sqlValues = join(",", $sqlValues);

            $queryDetail = "INSERT INTO REQUEST_DETAIL (REQUEST_HEADER, `KEY`, `VALUES`) VALUES $sqlValues";
            // echo ($queryDetail);

            if (mysqli_query($dbconn, $queryDetail)){

                // echo("Insert request form Success!");
                echo($pasFoto."|".$fotoEktp);

            }else{

                echo("Data query 2 gagal di input.");
                http_response_code(400);

            }

        }else{

            echo("Data query 1 gagal di input.");
            http_response_code(400);

        }

    }else{

        echo("The file is suitable but not uploaded successfully.");
        http_response_code(500);

    }

?>