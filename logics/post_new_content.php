<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();

    // save file in db
    // var_dump ($_FILES['file']['tmp_name']);
    // var_dump ($_FILES['file']['name']);
    // if(move_uploaded_file($_FILES['file']['tmp_name'], '../assets/uploads/' . $_FILES['file']['name'])) {
    //     echo 'works';
    // } else {
    //     echo $_FILES['file']['error'];
    //     echo 'not work';
    // }
    // return;
    try{
        for($i = 0; $i < 5; $i++) {
            $filevar = 'file' . $i;
            $thumbvar = 'thumb' . $i;
            move_uploaded_file($_FILES[$filevar]['tmp_name'], '../assets/uploads/' . $_FILES[$filevar]['name']);
            try{
                move_uploaded_file($_FILES[$thumbvar]['tmp_name'], '../assets/uploads/' . $_FILES[$thumbvar]['name']);
            } catch (\Throwable $th) {
                echo $th->getMessage();
            }
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
    // move_uploaded_file($_FILES['file']['tmp_name'], '../assets/uploads/' . $_FILES['file']['name']);
    // try{
    //     // if(move_uploaded_file($_FILES['thumb']['tmp_name'], '../assets/uploads/' . $_FILES['thumb']['name'])) {
    //     //     echo 'works';
    //     // } else {
    //     //     var_dump ($_FILES['thumb']['tmp_name']);
    //     //     echo 'not work';
    //     // }
    //     move_uploaded_file($_FILES['thumb']['tmp_name'], '../assets/uploads/' . $_FILES['thumb']['name']);
    // } catch (\Throwable $th) {
    //     //throw $th;
    //     echo $th->getMessage();
    // }
    
    $title = $_POST['title'];
    $description = $_POST['description'];

    $sent_time = $_POST['sent_time'];
    $hex = $_POST['hex'];
    $post_id = $_POST['post_id'];
    // $shop_code = $_POST['shop_code'];
    $f_pin = $_POST['created_by'];
    $privacy = $_POST['privacy'];
    $file_type = $_POST['file_type'];
    
    $merchant_code = "";
    if (isset($_POST['merchant_code'])) {
        $merchant_code = $_POST['merchant_code'];
    }

    try {
        $upload_dir = base_url() . 'persib_web/assets/uploads/';
        $filenamelist = '';

        try {
            for($i = 0; $i < 5; $i++) {
                $filevar = 'file' . $i;
                $thumbvar = 'thumb' . $i;
                $uploaded_file = $upload_dir . $_FILES[$filevar]["name"];
                $uploaded_file = preg_replace('/\s/i', '%20', $uploaded_file);
                if(empty($_FILES[$filevar]['name'])) {
                    break;
                }

                // fetch file type
                $fileType = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));

                $video_name = $hex . '-' . $i . '.' . $fileType;

                // move file to cu directory
                if(!copy('../assets/uploads/' . $_FILES[$filevar]['name'], '../images/' . $video_name)){
                    if(!copy($uploaded_file, '../images/' . $video_name)){
                        echo "Failed saving video ";
                        break;
                    }
                }

                $video_name = base_url() . 'persib_web/images/' . $video_name;

                try {
                    $uploaded_file_thumb = $upload_dir . $_FILES[$thumbvar]["name"];
                    $uploaded_file_thumb = preg_replace('/\s/i', '%20', $uploaded_file_thumb);

                    // fetch file type
                    $fileTypeThumb = strtolower(pathinfo($uploaded_file_thumb, PATHINFO_EXTENSION));

                    $thumb_name = $hex . '-' . $i . '.' . $fileTypeThumb;

                    // move file to cu directory
                    if(!copy('../assets/uploads/' . $_FILES[$thumbvar]['name'], '../images/' . $thumb_name)){
                        if(!copy($uploaded_file_thumb, '../images/' . $thumb_name)){
                            echo "Failed saving thumb ";
                        }
                    }

                    $thumb_name = base_url() . 'persib_web/images/' . $thumb_name;

                } catch (\Throwable $th) {
                    //throw $th;
                    echo $th->getMessage();
                }

                if($filenamelist !== ''){
                    $filenamelist = $filenamelist . "|";
                }
                $filenamelist = $filenamelist . $video_name;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        echo $filenamelist . " ";

        if ($filenamelist === '') {
            echo 'Failed saving images/videos';
            return;
        }

        // insert into table
        $query = $dbconn->prepare(
            "INSERT INTO POST(`POST_ID`, `F_PIN`, `TITLE`,`DESCRIPTION`, `TYPE`, `CREATED_DATE`, `PRIVACY`, `FILE_TYPE`, `THUMB_ID`,`FILE_ID`, `LAST_UPDATE`) VALUES (?, ?, ?, ?, '2', ?, ?, ?, '-', ?, ?)");
        $query->bind_param("sssssssss", $post_id, $f_pin, $title, $description, $sent_time, $privacy, $file_type, $filenamelist, $sent_time);
        $status = $query->execute();
        $query->close();

        $query = $dbconn->prepare(
            "INSERT INTO CONTENT_CATEGORY(`POST_ID`, `CATEGORY`) VALUES (?, '0')");
        $query->bind_param("s", $post_id);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>