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
    
    $title = $_POST['product_name'];
    $description = $_POST['product_description'];

    $sent_time = $_POST['sent_time'];
    $hex = $_POST['hex'];
    // $shop_code = $_POST['shop_code'];
    $palio_id = $_POST['palio_id'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $reward_point = $_POST['reward_point'];

    $length = $_POST['length'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $fragile = $_POST['fragile'];
    
    $merchant_code = "";
    if (isset($_POST['merchant_code'])) {
        $merchant_code = $_POST['merchant_code'];
    }

    try {
        $upload_dir = base_url() . 'palio_browser/assets/uploads/';
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
                        // echo "Failed saving video ";
                        break;
                    }
                }

                $video_name = base_url() . 'palio_browser/images/' . $video_name;

                try {
                    $uploaded_file_thumb = $upload_dir . $_FILES[$thumbvar]["name"];
                    $uploaded_file_thumb = preg_replace('/\s/i', '%20', $uploaded_file_thumb);

                    // fetch file type
                    $fileTypeThumb = strtolower(pathinfo($uploaded_file_thumb, PATHINFO_EXTENSION));

                    $thumb_name = $hex . '-' . $i . '.' . $fileTypeThumb;

                    // move file to cu directory
                    if(!copy('../assets/uploads/' . $_FILES[$thumbvar]['name'], '../images/' . $thumb_name)){
                        if(!copy($uploaded_file_thumb, '../images/' . $thumb_name)){
                            // echo "Failed saving thumb ";
                        }
                    }

                    $thumb_name = base_url() . 'palio_browser/images/' . $thumb_name;

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

        $query = $dbconn->prepare("SELECT s.* from SHOP s left join BUSINESS_ENTITY be on s.PALIO_ID = be.COMPANY_ID WHERE be.ID = ? LIMIT 1");
        $query->bind_param("s", $palio_id);
        $query->execute();
        $shop = $query->get_result()->fetch_object();
        $query->close();

        $shop_code = $shop->CODE;
        $category = $shop->CATEGORY;

        // insert into table
        $query = $dbconn->prepare(
            "INSERT INTO PRODUCT(`CODE`, `NAME`, `CREATED_DATE`, `SHOP_CODE`, `DESCRIPTION`,  `THUMB_ID`, `CATEGORY`, `PRICE`, `QUANTITY`, `REWARD_POINT`,  `MERCHANT_CODE`) VALUES (?, ?, ?, ?, ?,  ?, ?, ?, ?, ?,  ?)");
        $query->bind_param("sssssssssss", $hex, $title, $sent_time, $shop_code, $description, $filenamelist, $category, $price, $quantity, $reward_point, $merchant_code);
        $status = $query->execute();
        $query->close();

        $query = $dbconn->prepare(
            "INSERT INTO PRODUCT_SHIPMENT_DETAIL(`PRODUCT_CODE`, `LENGTH`, `WIDTH`, `HEIGHT`, `WEIGHT`, `IS_FRAGILE`) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssss", $hex, $length, $width, $height, $weight, $fragile);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>