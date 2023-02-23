<?php 

$json_received =   '{
                    "nama" : "reza",
                    "jurusan" : "informatika",
                    "tahun" : "2016"
                    }';

$data = json_decode($json_received, true); // [ Array [0] => {"nama" => "reza"} [1] => {"jurusan" => "informatika"} ]
$keys = array_keys($data); // [ Array [0] => "nama" [1] => "jurusan" ]

// echo($data['nama']);

$total_where = "";
$single_where = array();

// echo $keys[0];

foreach($keys as $key){

    $keyname = $key;
    $value = $data[$key];

    // echo($keyname);
    // echo($value);

    $where_temp = "(`KEY` = '$keyname' AND `VALUE` = '$value')";

    array_push($single_where, $where_temp);
}

// echo($single_where[0]);

$total_where = implode(" OR ", $single_where);

// echo($total_where);

$total_query = "SELECT SUM(TOTAL) AS TOTAL_PRICE FROM MONEY_HISTORY WHERE " . $total_where;

echo ($total_query);

?>

