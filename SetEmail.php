<?php

$data = json_decode(file_get_contents('php://input'), true);


$file_id = $data['message']['photo'][0]['file_id'];
print_r($file_id);
$url = 'https://api.telegram.org/bot5648217050:AAE6z4NHLIJRbQv6hFVH5xIernfN6Hdn-iQ/'.$file_id;
$local_file_path = '\send';

file_put_contents($local_file_path, file_get_contents($url), FILE_APPEND);
//if (isset($data[]))
//file_put_contents('test.txt', '$data: ' . print_r($data, 1) . "\n", FILE_APPEND);

//if (isset($data['message']['document']) || isset($data['message']['photo']))
//{
//    //$file = file_put_contents('send.txt', '$file: ' . print_r($data['message']['document'], 1) . "\n", FILE_APPEND );
//
//}



//

//print_r($file);

