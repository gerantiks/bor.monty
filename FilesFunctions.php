<?php
//Блок для работы с файлами или фото в папке сервера

require __DIR__ . '/Secret_info.php';
 
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

//Функция создает массив с именами файлов, что находяться в папке send
function getArrayNamesFilesInSend ($dir)
{
    if (glob($dir . '*')){   //проверка на наличе файлов в директории
        $files = scandir($dir);
        unset ($files['0'], $files['1']);
        $files=array_values($files); //переиндексация массива
    } else {
        $files = null;
    }  
    return $files;
}

//Функция удаляет файли что лежат в директории send
function delFiles($dir) 
{
    $delFiles = glob($dir . "*");
    foreach ($delFiles as $file){
        if (file_exists($file)){
            unlink($file);  // удалить файл в директории send
        }  
    }
}

