<?php


if (!isset($_GET['name'])){
    http_response_code ( 400 );
    return;
}

$name = $_GET['name'];

if (!isset($_GET['data'])){
    $dataSet = false;
    $data = null;
} else {
    $data = $_GET['data'];
    $dataSet = true;
    if ($data != "location" && $data != "value"){
        http_response_code ( 400 );
        return;
    }
}


$result = array();
if ($dataSet == false) {
    $result['location'] = null;
    $result['value'] = null;
} else {
    $result[$data] = null;
}

return json_encode($result);


