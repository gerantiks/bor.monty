<?php


    require __DIR__ . '/vendor/autoload.php';  //подключение файла автозагрузки с добавленой в composer PHPMailer библиотекой.
    require __DIR__ . '/inline_keyboard.php';
    require __DIR__ . '/BD_example.php';
    require  __DIR__ . '/Secret_info.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    /**
     * @var array $keyboard;
     * @var $token;
     * @var $linkNgrok;
     * @var $passwordSmtp;
     * @var $addressSmtp;
     */

    $linkWebhook = "https://api.telegram.org/bot$token/setwebhook?url=$linkNgrok";

    $data = json_decode(file_get_contents('php://input'), true); // получает информацию в виде json строки и декодирует;
    file_put_contents('file.txt', '$data: '. print_r($data, 1) . "\n", FILE_APPEND); // записивает в файл file.txt, массив $data c флагом FILE_APPENDED до запись (без него перезапись);


    $getUserMessage = mb_strtolower($data['message']['text'], 'UTF8'); // получаемое сообщение от пользователя.
    $getChatId = $data['message']['chat']['id'];
    $callbackChatId = $data['callback_query']['message']['chat']['id'];  // chat_id через кнопки
    $callbackData= $data['callback_query']['data']; //data через кнопки;


    function sendEmail($addressSmtp, $passwordSmtp)
    {
        $sendEmailAdress = "";

        $topicEmail = "List";

        $mail = new PHPMailer();

        $mail->isSMTP();

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->Host = 'smtp.gmail.com';

        $mail->Port = 587;

        $mail->SMTPAuth = true;

        $mail->Username =  $addressSmtp;

        $mail->Password = $passwordSmtp;

        $mail->setFrom('cajihe4885@brandoza.com', 'Anonim');

        $mail->addAddress($sendEmailAdress, "");

        $mail->Subject = $topicEmail;

        //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);

        $mail->Body = 'Helloy my first list';

        //$mail->addAttachment('images/phpmailer_mini.png');

        if (!$mail->send()) {
            $sendUserMassage = 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $sendUserMassage = 'Message sent!';
        }
        return $sendUserMassage;
    }



    if (getStatusUserMessage() == '1')
    {
        $sendUserMessage = "Введіть текст повідомлення:";
        $statusId = '2';

    }
    elseif (getStatusUserMessage() == '2')
    {
        $sendUserMessage = 'Сообщение отправлено';
        $statusId = '3';

    }
    else
    {
        switch ($getUserMessage)
        {
            case "/start":
                $sendUserMessage = "Доброго дня, мне звати Монті. Я буду вашим помічником, Мяу!
                    Знизу є кнопки з моїми вміннями. Якщо, щось буде потрібно виберіть кнопку і я зроблю за вас.";
                break;

            case "email":
                $sendUserMessage = "Введіть будь-ласка вашу почту, нижче без помилок і повнстю";
                $statusId = '1';
                break;


            case "Отправить":
                $sendUserMessage = "Ваш лист відправлено";
                break;

            case ("мяу" || "кот" || "кіт"):
                $sendUserMessage = "😽";
                break;

            default:
                $sendUserMessage = "Не зрозуміло";
        }
    }


    if (isset($data['callback_query']))
    {
        $sendUserMessage = "Сообщение получено";
        $sendUserMessage = http_build_query(
            [
                'chat_id' => $callbackChatId ,
                'text' =>$sendUserMessage
            ]
        );
    } else
    {
        $sendUserMessage = http_build_query(
            [
                'chat_id' => $getChatId,
                'text' =>$sendUserMessage
            ]
        );
    }



//    if ($data["callback_query"]["data"] == "1")
//        $sendUserMessage = "Результат получил";
    if (isset($statusId)) //проверка на наличие статуса
    {
        InsertIdTextStatus($getChatId, $getUserMessage, $statusId);
    } else
    {
        InsertIdText($getChatId, $getUserMessage); // записивает в базу данних;
    }


    sendTelegram($token, $sendUserMessage);

    function sendTelegram($token, $sendUserMessage)
    {
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?".$sendUserMessage);
        // | читает файл в строку, используеться http_build_query для генерации URL запроса что содержит масив или обьект.
    }



    function sendAnswerBotButton($token, $sendUserMessage, $keyboard)
    {
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . $sendUserMessage . "&reply_markup=". $keyboard);
    }
