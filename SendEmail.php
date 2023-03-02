<?php
/*
Блок для получения имен файлов, с последующей отравкой с помощью PHPMailer.
По окончанию выполнения отправки или неудачной попытки, директория send будет очищаться.
*/
$dir = __DIR__ . "/send/"; //Путь к папке, куда временно будут сохраняться файли от пользователя

require __DIR__ . '/Secret_info.php';

    /** 
         * @var $passwordSmtp;
         * @var $addressSmtp;
         * @var $sendEmailAddress;
         * @var $textEmail;
     */
    
require __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
function sendEmail($addressSmtp, $passwordSmtp, $sendEmailAddress, $textEmail, $files, $dir)
{

    $topicEmail = "List";

    $mail = new PHPMailer(); //создаем обьект

    $mail->CharSet = "utf-8";  //ставим кодировку

    $mail->isSMTP(); // способ отправки протокол SMTP

    $mail->SMTPDebug = SMTP::DEBUG_SERVER; 

    $mail->Host = 'smtp.gmail.com'; //SMTP сервер для отпраки

    $mail->Port = 587; //порт 
        
    $mail->SMTPAuth = true; //авторизация на SMTP сервере

    $mail->Username =  $addressSmtp; //Логин почтового ящика отправителя

    $mail->Password = $passwordSmtp; //Пароль от ящика

    $mail->setFrom('kisicaf593@wiroute.com', 'Anonim'); //Адресс отправителя и имя

    $mail->addAddress($sendEmailAddress, ""); //Получатель

    $mail->Subject = $topicEmail; //Тема письма

    $mail->Body = $textEmail; //Текст письма

    //Перебор имен файлов из массива $files;
    foreach ($files as $file){
        $mail->addAttachment( "{$dir}{$file}",  $file); //Прикрепить файли
    }

    if (!$mail->send()) {
        $sendUserMassage = 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        $sendUserMassage = 'Email sent without error';
    }

    return $sendUserMassage;
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
function delFilesInSend($dir) 
{
    $delFiles = glob($dir . "*");
    foreach ($delFiles as $file){
        if (file_exists($file)){
            unlink($file);  // удалить файл
        }  
    }
}

 $files = getArrayNamesFilesInSend($dir);
 sendEmail($addressSmtp, $passwordSmtp, $sendEmailAddress, $textEmail, $files, $dir);
 delFilesInSend($dir);