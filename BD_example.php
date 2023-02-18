<?php

try {
    function InsertIdText($getChatId, $getUserMessage)
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "root", "");
        $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8mb4; SET CHARACTER SET utf8mb4; SET SESSION collation_connection = utf8mb4_unicode_ci;'); // атрибути подключения к БД
        $sql = "INSERT INTO bot.message(`chat_id`, `text`)VALUES('$getChatId','$getUserMessage');";// виражение для создание таблици
        $conn->exec($sql);
        $conn = null;

    }

    function InsertIdTextStatus($getChatId, $getUserMessage, $statusId)
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "root", "");
        $sql = "INSERT INTO bot.message (`chat_id`, `text`, `status`)VALUES('$getChatId','$getUserMessage', $statusId)";
        $conn->exec($sql);
    }

    function getStatusUserMessage()
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot", 'root', '');
        $sql = "SELECT * FROM bot.message WHERE id = (SELECT MAX(`id`) FROM bot.message WHERE (`status`))";
        $rezult = $conn->query($sql);
        $row = $rezult->fetch();
        return $row['status'];
    }

}

catch (PDOException $e) {
    echo "table error" . $e->getMessage();
}

