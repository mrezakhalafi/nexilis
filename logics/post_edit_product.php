<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();

    // save file in db
    // if(move_uploaded_file($_FILES['file']['tmp_name'], '../assets/uploads/' . $_FILES['file']['name'])) {
    //     echo 'works';
    // } else {
    //     var_dump ($_FILES['file']['tmp_name']);
    //     echo 'not work';
    // }
    // return;
    $image_name_list = '';
    $image_name_size = 0;
    if (isset($_POST['product_image'])) {
        $image_name_list = $_POST['product_image'];
        if(!empty($image_name_list)){
            $image_name_size = sizeof(explode("|",$image_name_list));
        }
    }
    
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
    
    $product_code = $_POST['product_code'];

    $title = $_POST['product_name'];
    $description = $_POST['product_description'];

    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $reward_point = $_POST['reward_point'];
    $file_type = $_POST['file_type'];
    $hex = $_POST['hex'];

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
        // if($image_name === null){
        $upload_dir = base_url() . 'palio_browser/assets/uploads/';
        try {
            for($i = $image_name_size; $i < 5; $i++) {
                $filevar = 'file' . $i;
                $thumbvar = 'thumb' . $i;
                $uploaded_file = $upload_dir . $_FILES[$filevar]["name"];
                $uploaded_file = preg_replace('/\s/i', '%20', $uploaded_file);
                if(empty($_FILES[$filevar]['name'])) {
                    continue;
                }

                // fetch file type
                $fileType = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));

                $image_name = $hex . '-' . $i . '.' . $fileType;

                // move file to cu directory
                if(!copy('../assets/uploads/' . $_FILES[$filevar]['name'], '../images/' . $image_name)){
                    if(!copy($uploaded_file, '../images/' . $image_name)){
                        // echo "Failed saving image";
                        continue;
                    }
                }
                $image_name = base_url() . 'palio_browser/images/' . $image_name;

                try {
                    $uploaded_file_thumb = $upload_dir . $_FILES[$thumbvar]["name"];
                    $uploaded_file_thumb = preg_replace('/\s/i', '%20', $uploaded_file_thumb);
        
                    // fetch file type
                    $fileTypeThumb = strtolower(pathinfo($uploaded_file_thumb, PATHINFO_EXTENSION));
        
                    $thumb_name = $hex . '-' . $i . '.' . $fileTypeThumb;
        
                    // move file to cu directory
                    if(!copy('../assets/uploads/' . $_FILES[$thumbvar]['name'], '../images/' . $thumb_name)){
                        if(!copy($uploaded_file_thumb, '../images/' . $thumb_name)){
                            // echo "Failed saving thumb";
                        }
                    }
        
                    $thumb_name = base_url() . 'palio_browser/images/' . $thumb_name;
        
                } catch (\Throwable $th) {
                    //throw $th;
                    echo $th->getMessage();
                }

                if($image_name_list !== ''){
                    $image_name_list = $image_name_list . "|";
                }
                $image_name_list = $image_name_list . $image_name;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        // }

        // insert into table
        $query = $dbconn->prepare(
            "UPDATE PRODUCT SET `NAME` = ? , `DESCRIPTION` = ? , `THUMB_ID` = ? , `PRICE` = ? , `QUANTITY` = ? , `REWARD_POINT` = ? , `MERCHANT_CODE` = ? WHERE `CODE` = ? ");
        $query->bind_param("ssssssss", $title, $description, $image_name_list, $price, $quantity, $reward_point, $merchant_code, $product_code);
        $status = $query->execute();
        $query->close();

        $query = $dbconn->prepare("INSERT INTO PRODUCT_SHIPMENT_DETAIL (`PRODUCT_CODE`, `LENGTH`, `WIDTH`, `HEIGHT`,  `WEIGHT`, `IS_FRAGILE`) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `LENGTH` = ?, `WIDTH` = ?, `HEIGHT` = ? , `WEIGHT` = ? , `IS_FRAGILE` = ?");
        $query->bind_param("sssssssssss", $product_code, $length, $width, $height, $weight, $fragile, $length, $width, $height, $weight, $fragile);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>