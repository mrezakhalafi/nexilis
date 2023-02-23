<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    // GET FROM POST 

    $f_pin = $_POST['f_pin'];
    $ads_title = $_POST['ads_title'];
    $ads_description = $_POST['ads_desc'];
    $ads_url = $_POST['ads_url'];
    $media_type = $_POST['media_type'];
    $end_date = $_POST['end_date_milis'];
    $content_type = '';
    $is_gif = $_POST['is_gif'];
    $animation = 0;
    $is_dissapear = $_POST['is_dissapear'];
    $start_position = $_POST['start_position'];
    $server = $_POST['server'];

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/nexilis/assets/img/gif/';
    $target_files = array();

    if ($media_type == 1){

        if ($is_gif == 1){

            $animation = 1;

            $fotoAds = "ADS-".$f_pin . time() . ".gif";
            // $thumbFotoAds = "T_ADS-".$f_pin . time() . ".webp";
            $thumbFotoAds = "ADS-".$f_pin . time() . ".gif";

        }else if($is_gif == 0){

            $fotoAds = "ADS-".$f_pin . time() . ".webp";
            $thumbFotoAds = "T_ADS-".$f_pin . time() . ".webp";

        }

        $content_type = 0;

    }else if ($media_type == 2){

        $fotoAds = "ADS-".$f_pin . time() . ".mp4";
        $thumbFotoAds = "T_ADS-".$f_pin . time() . ".webp";
        $content_type = 1;

    }else if($media_type == 3){

        $content_type = 2;
        $thumbFotoAds = "";
        $fotoAds = "";

    }

    $start_upload = true;

    if ($fotoAds != ""){
        $target_files['ads_photo'] = $target_dir . $fotoAds;
        $start_upload = move_uploaded_file($_FILES["ads_photo"]["tmp_name"], $target_files["ads_photo"]);
    }

    if ($thumbFotoAds != "" && $is_gif != 1){
        $target_files['thumbnail'] = $target_dir . $thumbFotoAds;
        $start_upload = move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_files["thumbnail"]);
    }

    if ($start_upload){

        if ($server == 1){

            // FOR CBN

            $connection = ssh2_connect('192.168.1.100', 2309);
            ssh2_auth_password($connection, 'easysoft', '*347e^!VU4y+#hAP'); 

            if (ssh2_scp_send($connection, $target_files['ads_photo'], '/apps/lcs/paliolite/server/image/' . $fotoAds, 0777) && 
            ssh2_scp_send($connection, $target_files['thumbnail'], '/apps/lcs/paliolite/server/image/' . $thumbFotoAds, 0777)) {
                // echo ("Success Send");
            }

        }else{

            // FOR AWS & LOCALHOST

            if (copy($target_files['ads_photo'], '/apps/lcs/cxbutton/server/image/' . $fotoAds) && 
            copy($target_files['thumbnail'], '/apps/lcs/cxbutton/server/image/' . $thumbFotoAds)){
                // echo ("Success Send");
            }

        }

        try {

            $api_url = "";

            if ($server == 1){
                    
                // CBN
                $api_url = "http://192.168.1.100:8004/webrest/";

            }else{

                // AWS & LOCALHOST
                $api_url = "http://127.0.0.1:8004/webrest/";

            }

            $api_data = array(
                'code' => 'INTADS',
                'data' => array(
                    'f_pin' => $f_pin,
                    'title' => $ads_title,
                    'description' => $ads_description,
                    'content_type' => $content_type,
                    'ads_type' => 1,
                    'thumb' => $thumbFotoAds,
                    'file_id' => $fotoAds,
                    'end_date' => $end_date,
                    'animation' => 1,
                    'link' => 'https://'.$ads_url,
                    'loop_animation' => intval($is_dissapear),
                    'start_position' => intval($start_position)
                ),
            );
    
            $api_options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => strval(json_encode($api_data))
                )
            );
    
            $api_stream = stream_context_create($api_options);
            $api_result = file_get_contents($api_url, false, $api_stream);
            $api_json_result = json_decode($api_result);

            echo json_encode($api_data);
        
            if (http_response_code() != 200) {
                throw new Exception('Send message failed!');
            }
    
        } catch (Exception $e) {
    
            echo $e->getMessage();
    
        }

    }else{

        echo("The file is suitable but not uploaded successfully.");
        http_response_code(500);

    }

?>