<?php
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('test.txt', '$data' . print_r($data, 1) . "\n", FILE_APPEND);

//Проверка на наличие
if (isset($data['message']['document']))
{
    SaveFile($data);

} elseif (isset($data['message']['photo']))
{
    SavePhoto($data);
} 

function SaveFile($data)
{
    //Информация про одержанный файл
    $fileId = $data['message']['document']['file_id'];
    $fileName = $data['message']['document']['file_name'];

    $url = 'https://api.telegram.org/bot5648217050:AAE6z4NHLIJRbQv6hFVH5xIernfN6Hdn-iQ/getFile?file_id='.$fileId;
    $fileInfo = json_decode(file_get_contents($url), true);

    //ccылка на файл
    $filePath = $fileInfo['result']['file_path'];

    //Сохраняем на сервер
    $file = file_get_contents("https://api.telegram.org/file/bot5648217050:AAE6z4NHLIJRbQv6hFVH5xIernfN6Hdn-iQ/".$filePath);
    file_put_contents("/var/www/bot.monty/send/$fileName", $file);
}

function SavePhoto($data)
{
    $photoId = $data['message']['photo']['3']['file_id'];
    $url = 'https://api.telegram.org/bot5648217050:AAE6z4NHLIJRbQv6hFVH5xIernfN6Hdn-iQ/getFile?file_id=' . $photoId;

    $photoId =json_decode(file_get_contents($url), true);

    $photoPath = $photoId['result']['file_path'];


    $url = 'https://api.telegram.org/file/bot5648217050:AAE6z4NHLIJRbQv6hFVH5xIernfN6Hdn-iQ/' . $photoPath;
    //Название сжатих фото, это микросекунди
    file_put_contents("/var/www/bot.monty/send/" . microtime(true) .'.jpg', file_get_contents($url));
}


