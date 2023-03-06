<?php
/*
    Блок для работы с базой данных MySQL  
*/
//Записивает в таблицу, chat_id, text, status.
    function InsertChatTextStatus($getChatId, $getUserMessage, $status)
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "user1", "");
        $sql = "INSERT INTO bot.message (`chat_id`, `text`, `status`)VALUES($getChatId, '$getUserMessage', $status)";
        $conn->query($sql);
    }
    function InsertChatText($getChatId, $getUserMessage) //Записивает в таблицу, chat_id, text.
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "user1", "");
        $sql = "INSERT INTO bot.message (`chat_id`, `text`)VALUES($getChatId, '$getUserMessage')";
        $conn->query($sql);
    }

//Вытягиваем с таблицы последний id в колонке status.
    function getStatus()
    {
        $conn = new PDO("mysql:localhost:3306;dbname = bot", 'user1', '');
        $sql = "SELECT * FROM bot.message WHERE id = (SELECT MAX(`id`) FROM bot.message WHERE (`status`))";
        $rezult = $conn->query($sql);
        $row = $rezult->fetch();
        return $row['status'];
    }
//Вытягиваем с таблицы текст, с определенным статусом. 
    function putColumnText($statusId)
    {
        $conn = new PDO("mysql:localhost:3306;dbname = bot", "user1", "");
        $sql = "SELECT * FROM bot.message WHERE id = (SELECT MAX(`id`) FROM bot.message WHERE `status` = $statusId)";
        $result = $conn->query($sql);
        $row = $result->fetch();
        return $row['text'];
    }

    //Сброс таблицы
    function DropTable()
    {
        $conn = new PDO("mysql:localhost:3306;dbname = bot;", "user1", "");
        $sql = "TRUNCATE TABLE bot.message;";
        $conn->query($sql);
    }


