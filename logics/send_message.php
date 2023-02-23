<?php 

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$api_url = "http://192.168.1.100:8004/webrest/";

$message_id = $_POST['message_id'];
$originator = $_POST['originator'];
$destination = $_POST['destination'];
$content = base64_decode($_POST['content']);
$scope = $_POST['scope'];
$reply_to = $_POST['reply_to'];
$file_id = $_POST['file_id'];

try {

    $api_data = array(
        'code' => 'SNDMSG',
        'data' => array(
            'message_id' => $message_id,
            'from' => $originator,
            'to' => $destination,
            'message_text' => $content, // dari JS, JSON stringify->base64; di sini decode base64->json_decode`
            'scope' => $scope, // 
            'chat_id' => "", 
            'is_complaint' => 0,
            'call_center_id' => "", 
            'reply_to' => $reply_to,
            'file_id' => $file_id
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

    if (http_response_code() != 200) {
        throw new Exception('Send message failed!');
    }

} catch (Exception $e) {

    echo("<script>console.log(" . $e . ");</script>");

}

echo json_encode($api_data);
// echo $content;
