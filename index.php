<?php
require __DIR__ . '/BD_query.php';
require  __DIR__ . '/Secret_info.php';
require __DIR__ . '/Inline_keyboard.php';
require __DIR__ . '/SendEmail.php';
require __DIR__ . '/FilesFunctions.php';

    /**
     * @var array $keyboard;
     * @var $token;
     * @var $addressSmtp;
     * @var $passwordSmtp;
     * @var 
     */

    $data = json_decode(file_get_contents('php://input'), true); // получает информацию в виде json строки и декодирует;
    file_put_contents('file.txt', '$data: '. print_r($data, 1) . "\n", FILE_APPEND); // записивает в файл file.txt, массив $data c флагом FILE_APPENDED до запись (без него перезапись);


    $getUserMessage = mb_strtolower($data['message']['text'], 'UTF-8'); // получаемое сообщение от пользователя.
    $getChatId = (int) $data['message']['chat']['id']; 

    $callbackChatId = $data['callback_query']['message']['chat']['id'];  // chat_id через кнопки
    $callbackData= $data['callback_query']['data']; //data через кнопки;


    if (getStatus() == '1') //Проверка статуса диалога бота и пользователя
    {
        $sendEmailAddress = $getUserMessage;
        $result = validationAddress($sendEmailAddress); // Проверка адреса
        if ($result)
        {
            $sendUserMessage = "Введіть текст повідомлення:";
            $status = '2';
        } else {
            $status = '1';
            $sendUserMessage = "Вы ввели несуществующий адресс, введите повторно";
        }
        
        
    }
    elseif (getStatus() == '2')
    {
        //Проверка на наличии файлов, фото с последующей записью на папку сервера 
        if (isset($data['message']['document']))
        {
            SaveFile($data, $token);
            $getUserMessage = $data['message']['caption'];
        } 
        elseif (isset($data['message']['photo']))
        {
            SavePhoto($data, $token);
            $getUserMessage = $data['message']['caption'];
        } else {
            $status = '3'; 
            $callback = ['callback_query'=> "0" ];  
            $data = array_merge($data, $callback); //добавляем в массив data, callback для вызова кнопок
            $sendUserMessage = 'Пару секунд... Підтвердіть відправку натиснувши кнопку внизу';  
        }
        
    }

    elseif (getStatus() == '3')
    {
        if ($callbackData == '2')  //Подтверждение отправки письма
        {
            $sendEmailAddress = putColumnText('2');  // выбираем адрес;
            $textEmail = putColumnText('3'); // выбираем текст сообщение;
            $files = getArrayNamesFilesInSend($dir); //проверяет на наличие файлов в send

            if (empty($textEmail)){  //Если текст письма будет пустой, добавляется дата
                $textEmail = "Время отправки письма: " . date('H:i:s');
                $result = sendEmail($addressSmtp, $passwordSmtp, $sendEmailAddress, $textEmail, $files, $dir);
            } else {
                $result = sendEmail($addressSmtp, $passwordSmtp, $sendEmailAddress, $textEmail, $files, $dir);
            }
            
            $sendUserMessage = $result;

            delFiles($dir);
            DropTable();
        }
        elseif ($callbackData == '1') //Отмена отправки пользователем введенных ранее данных
        {
            DropTable();
            delFiles($dir);
            $sendUserMessage = 'Введіть будь-ласка команду, мяу';
        }
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
                $sendUserMessage = "Введіть будь-ласка вашу почту, нижче без помилок і повністю";
                $status = '1';
                break;


            case "Отправить":
                $sendUserMessage = "Ваш лист відправлено";
                break;

            default:
                $sendUserMessage = "Не зрозуміло";
        }
    }
   
    //Добавление информации в БД
    if (isset($status)){
        InsertChatTextStatus($getChatId, $getUserMessage, $status);
    } else {
        InsertChatText($getChatId, $getUserMessage);
    }  
    
    //Условия перед отправкой юзеру ответа
    if ($data['callback_query'] == '0') // добавляет кнопки callback
    {
        $sendUserMessage = http_build_query(
            [
                'chat_id' => $getChatId,
                'text' =>$sendUserMessage
            ]
        );
        sendTelegramKeyboard($token, $sendUserMessage, $keyboard);
    }
    elseif(isset($callbackChatId)) //проверка на наличие выбраной кнопки $callbackData
    {
            $sendUserMessage = http_build_query(
                [
                    'chat_id' => $callbackChatId,
                    'text' =>$sendUserMessage
                ]
            );
            sendTelegram($token, $sendUserMessage);

    }
    else
    {
        $sendUserMessage = http_build_query(
            [
                'chat_id' => $getChatId,
                'text' =>$sendUserMessage
            ]
        );
        
        sendTelegram($token, $sendUserMessage);
    }
    
    function sendTelegram($token, $sendUserMessage)
    {
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?".$sendUserMessage);
        // | читает файл в строку, используеться http_build_query для генерации URL запроса что содержит масcив или обьект.
    }

    function sendTelegramKeyboard($token, $sendUserMessage, $keyboard)
    {
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . $sendUserMessage . "&reply_markup=". $keyboard);
    }
    
    