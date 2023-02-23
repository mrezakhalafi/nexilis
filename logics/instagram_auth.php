<?php

// include_once($_SERVER['DOCUMENT_ROOT'] . '/db_conn.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
// https://api.instagram.com/oauth/authorize?client_id=4328628943838644&redirect_uri=https://pbrowser-embed.herokuapp.com/&scope=user_profile,user_media&response_type=code
// https://api.instagram.com/oauth/authorize?client_id=4328628943838644&redirect_uri=https://palio.io/nexilis/logics/instagram_auth.php&scope=user_profile,user_media&response_type=code

$auth_code = $_GET['code'];
$code = str_replace("#_", "", $auth_code);
$client_id = '4328628943838644';
$client_secret = 'a9435aa306d86219feb4092e50bdbcc5';
$redirect = 'https://palio.io/nexilis/logics/instagram_auth.php';

function getUserMedia($uid, $sc, $acc_token)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.instagram.com/'. $uid .'/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,username&access_token=' . $acc_token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $json = json_decode($response, true);

        $code = array();
        // $name = array();
        $created_date = array();
        // $shop_code = array();
        $description = array();
        $thumb_id = array();
        $score = 0;

        $shop_code = $sc;

        foreach($json['data'] as $post) {
            $millis = (int)(microtime(true)*1000)+1;

            $code[] = substr($post['id'], strlen($post['id'])-10);

            $created_date[] = $millis;
            
            $description[] = $post['caption'];

            $thumb_id[] = $post['media_url'];
        }

        $query = 'REPLACE INTO PRODUCT (`CODE`, `NAME`, `CREATED_DATE`, `SHOP_CODE`, `DESCRIPTION`, `THUMB_ID`, `SCORE`) VALUES ';
        $query_parts = array();
        for ($x = 0; $x < count($json['data']); $x++){
            $query_parts[] = "('" . $code[$x] . "', '-', '" . $created_date[$x] . "', '" . $shop_code . "', '" . $description[$x] . "', '" . $thumb_id[$x] . "', '0')";
        }
        $query .= implode(',', $query_parts);

        $dbconn = paliolite();
        // $dbconn = getDBConn();
        $query = $dbconn->prepare($query);
        $query->execute();
        $query->close();

        echo "<script>alert('insert product sukses');</script>";
    }
}

function getUserProfile($uid, $acc_token) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.instagram.com/'. $uid .'?fields=id,username&access_token=' . $acc_token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $json = json_decode($response, true);
        $userid = $json['id'];
        $uname = $json['username'];

        $millis = (int)(microtime(true)*1000);

        $dbconn = paliolite();
        $query = $dbconn->prepare("REPLACE INTO SHOP (`CODE`, `NAME`, `CREATED_DATE`, `DESCRIPTION`, 
            `FILE_TYPE`, `THUMB_ID`, `LINK`, `CATEGORY`, `USE_ADBLOCK`, `SCORE`, `PALIO_ID`)
            VALUES ('" . $userid . "', '". $uname ."', ". $millis .", '-', 1, '', '', '3', 0, 0, NULL)");
        $query->execute();
        $query->close();

        // echo 'im here';
        
        getUserMedia($uid, $json['id'], $acc_token);
    }
}

function getLongLivedToken($uid, $cl_sc, $shortToken)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=' . $cl_sc . '&access_token=' . $shortToken,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $json = json_decode($response, true);
        
        getUserProfile($uid, $json['access_token']);
    }
}

// get short lived token
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.instagram.com/oauth/access_token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
        'code' => $code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect,
        'grant_type' => 'authorization_code'
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);


curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $json = json_decode($response, true);
    getLongLivedToken($json['user_id'], $client_secret, $json['access_token']);
}
