<?php
//Блок для получения и сохранения, файлов или фото в папке сервера

require __DIR__ . '/Secret_info.php';
    /**
     * @var $token;
     */
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('test.txt', '$data' . print_r($data, 1) . "\n", FILE_APPEND);

//Проверка на наличие значение в массиве
if (isset($data['message']['document']))
{
    SaveFile($data, $token);

} elseif (isset($data['message']['photo']))
{
    SavePhoto($data, $token);
} 

function SaveFile($data, $token)
{
    //Информация про одержанный файл
    $fileId = $data['message']['document']['file_id'];
    $fileName = $data['message']['document']['file_name'];

    $url = "https://api.telegram.org/bot{$token}/getFile?file_id=".$fileId;
    $fileInfo = json_decode(file_get_contents($url), true);

    //ccылка на файл
    $filePath = $fileInfo['result']['file_path'];

    //Сохраняем на сервер
    $file = file_get_contents("https://api.telegram.org/file/bot{$token}/".$filePath);
    file_put_contents("/var/www/bot.monty/send/$fileName", $file);
}

function SavePhoto($data, $token)
{
    $photoId = $data['message']['photo']['3']['file_id'];
    $url = "https://api.telegram.org/bot{$token}/getFile?file_id=" . $photoId;

    $photoId =json_decode(file_get_contents($url), true);

    $photoPath = $photoId['result']['file_path'];

    $url = "https://api.telegram.org/file/bot{$token}/" . $photoPath;
    //Название сжатых фото, это микросекунди, которые сохраняем на сервер
    file_put_contents("/var/www/bot.monty/send/" . microtime(true) .'.jpg', file_get_contents($url));
}


