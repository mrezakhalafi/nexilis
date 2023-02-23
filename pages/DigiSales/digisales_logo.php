<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    $be = $_GET['be'];

    $queryBE = $dbconn->prepare("SELECT * FROM PREFS WHERE BE = 308 AND `KEY` = 'app_builder_url_first_tab'");
    $queryBE->execute();
    $dataBE = $queryBE->get_result()->fetch_assoc();
    $queryBE->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="assets/vendor/jquery/jquery-3.6.0.min.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Tab 1 Logo</title>
</head>
<style>

    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<body style="background-color: #f7f7f7" class="d-none">

    <div class="row m-3" style="margin-top: 275px !important">
        <div class="col-4">
            <img src="../../assets/img/digisales-logo.jpeg" style="width: 115px; height: auto; margin-top: -5px">
        </div>
        <div class="col-8">
            <p style="color: #cc2632; font-size: 40px; font-weight: bold" class="mt-3">DigiSales</p>
        </div>
    </div>

    
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

    var curr_url = window.location.href;
    var valBE = '<?= $dataBE['VALUE']; ?>';

    console.log("Current URL :" + curr_url);
    console.log("Database URL : " + valBE);

    if (curr_url == valBE) {
        console.log("ke cover");
        $('body').removeClass('d-none');
    }
    else {
        console.log("ke be");
        location.replace('https://'+valBE);   
    }

</script>