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
    $image_name = null;
    if (isset($_POST['shop_image'])) {
        $image_name = $_POST['shop_image'];
    } else {
        move_uploaded_file($_FILES['file']['tmp_name'], '../assets/uploads/' . $_FILES['file']['name']);
    }
    
    $title = $_POST['shop_name'];
    $link = $_POST['shop_link'];
    $description = $_POST['shop_description'];
    $code = $_POST['shop_code'];
    $adblock = $_POST['adblock'];
    $hex = $_POST['hex'];

    try {
        if($image_name === null){
            $upload_dir = base_url() . 'palio_browser/assets/uploads/';
            $uploaded_file = $upload_dir . $_FILES["file"]["name"];
            $uploaded_file = preg_replace('/\s/i', '%20', $uploaded_file);

            // fetch file type
            $fileType = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));

            $image_name = $hex . '.' . $fileType;

            // move file to cu directory
            if(!copy('../assets/uploads/' . $_FILES['file']['name'], '../images/' . $image_name)){
                if(!copy($uploaded_file, '../images/' . $image_name)){
                    echo "Failed saving image";
                    return;
                }
            }
                
        }
        // echo $hex;
        // echo '\n';
        // echo $title;
        // echo '\n';
        // echo $sent_time;
        // echo '\n';
        // echo $description;
        // echo '\n';
        // echo $image_name;
        // echo '\n';
        // echo $link;
        // echo '\n';
        
        // insert into table
        $query = $dbconn->prepare(
            "UPDATE SHOP SET `NAME` = ? , `LINK` = ? , `DESCRIPTION` = ? , `THUMB_ID` = ?, `USE_ADBLOCK` = ? WHERE `CODE` = ? "
            );
        $query->bind_param("ssssss", $title, $link, $description, $image_name, $adblock, $code);
        $status = $query->execute();
        $query->close();

        
        if(isset($_POST['address']) || isset($_POST['village']) || isset($_POST['district']) || isset($_POST['city']) || isset($_POST['province']) || isset($_POST['zipcode']) || isset($_POST['phonenumber']) || isset($_POST['couriernote'])){
            $address = $_POST['address'];
            $village = $_POST['village'];
            $district = $_POST['district'];
            $city = $_POST['city'];
            $province = $_POST['province'];
            $zipcode = $_POST['zipcode'];
            $phonenumber = $_POST['phonenumber'];
            $couriernote = $_POST['couriernote'];

            $query = $dbconn->prepare("INSERT INTO SHOP_SHIPPING_ADDRESS (`STORE_CODE`, `ADDRESS`, `VILLAGE`, `DISTRICT`, `CITY`, `PROVINCE`, `ZIP_CODE`, `PHONE_NUMBER`, `COURIER_NOTE`) VALUES (?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `ADDRESS` = ?, `VILLAGE` = ?, `DISTRICT` = ? , `CITY` = ? , `PROVINCE` = ? , `ZIP_CODE` = ? , `PHONE_NUMBER` = ? , `COURIER_NOTE` = ?");
            $query->bind_param("sssssssssssssssss", $code, $address, $village, $district, $city, $province, $zipcode, $phonenumber, $couriernote, $address, $village, $district, $city, $province, $zipcode, $phonenumber, $couriernote);
            $status = $query->execute();
            $query->close();
        }

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>