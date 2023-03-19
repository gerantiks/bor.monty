<?php
/*
Блок для получения имен файлов, с последующей отравкой с помощью PHPMailer.
По окончанию выполнения отправки или неудачной попытки, директория send будет очищаться.
*/
$dir = __DIR__ . "/send/"; //Путь к папке, куда временно будут сохраняться файли от пользователя
require_once __DIR__ . '/Secret_info.php';
require_once __DIR__ . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
function sendEmail($addressSmtp, $passwordSmtp, $sendEmailAddress, $textEmail, $files, $dir)
{

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

    $mail->Subject = 'Тест'; //Тема письма

    $mail->Body = $textEmail; //Текст письма

    //Перебор имен файлов из массива $files;
    foreach ($files as $file){
        $mail->addAttachment( "{$dir}{$file}",  $file); //Прикрепить файли
    }
    if (!$mail->send()){
        $sendUserMessage = "Ошибка при отправке письма ";
        file_put_contents('log.txt', "MailError: " . $mail->ErrorInfo . "\n", FILE_APPEND); //запись логов ошибок в файл
    } else {
        $sendUserMessage = "Письмо отправлено";
    }
   return $sendUserMessage;
}

//Проверка правильности формата адреса електронной почты
function validationAddress($sendEmailAddress)
{
    $mail = new PHPMailer();
    $mail->validateAddress($sendEmailAddress);
    $result = $mail->validateAddress($sendEmailAddress);
    return $result;
}


