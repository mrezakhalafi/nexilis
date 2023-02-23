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
    move_uploaded_file($_FILES['file']['tmp_name'], '../images/' . $_FILES['file']['name']);
    
    $title = $_POST['shop_name'];
    $link = $_POST['shop_link'];
    $description = $_POST['shop_description'];

    $sent_time = $_POST['sent_time'];
    $hex = $_POST['hex'];
    $adblock = $_POST['adblock'];
    $created_by = $_POST['created_by'];
    $category = $_POST['category_id'];

    try {
        $upload_dir = base_url() . 'nexilis/images/';
        $uploaded_file = $upload_dir . $_FILES["file"]["name"];
        $uploaded_file = preg_replace('/\s/i', '%20', $uploaded_file);

        // fetch file type
        $fileType = strtolower(pathinfo($uploaded_file, PATHINFO_EXTENSION));

        $image_name = $hex . '.' . $fileType;

        // move file to cu directory
        if(!copy('../images/' . $_FILES['file']['name'], '../images/' . $image_name)){
            if(!copy($uploaded_file, '../images/' . $image_name)){
                echo "Failed saving image";
                return;
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
            "INSERT INTO SHOP(`CODE`, `NAME`, `CREATED_DATE`, `DESCRIPTION`, `FILE_TYPE`, `THUMB_ID`, `LINK`, `CATEGORY`, `USE_ADBLOCK`, `CAN_DELETE`, `CREATED_BY`, `SHOW_FOLLOWS`, `IS_QIOSK`) VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?, 1, ?, 0, 1)");
        $query->bind_param("sssssssss", $hex, $title, $sent_time, $description, $image_name, $link, $category, $adblock, $created_by);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>