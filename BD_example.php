<?php

try {
    function InsertIntoSql($getChatId, $getUserMessage)
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "root", "");
        $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8mb4; SET CHARACTER SET utf8mb4; SET SESSION collation_connection = utf8mb4_unicode_ci;'); // атрибути подключения к БД
        $sql = "INSERT INTO bot.message(`chat_id`, `text`)VALUES('$getChatId','$getUserMessage');";// виражение для создание таблици
        $conn->exec($sql);
        $conn = null;
        echo "Запрос отправлен";

    }

    function getMaxIdSql()
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "root", "");
        $sql = "SELECT * FROM bot.message WHERE id = (SELECT MAX(`id`) FROM bot.message)"; // выбираем строку с последний id;
        $rezult = $conn->query($sql); // отправляем запрос из скриптом в BD
        $row = $rezult->fetch(); // fetch() считивает рядок;
        return $row['text'];
    }

    function writeDataInSql($getKeyboardData)
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot;", "root", "");
        $sql = "INSERT INTO bot.message (`data`)VALUES($getKeyboardData)";
        $conn->exec($sql);
    }

    function getDataInSql()
    {
        $conn = new PDO("mysql:localhost:3306;dbname=bot", 'root', '');
        $sql = "SELECT * FROM bot.message WHERE id = (SELECT MAX(`id`) FROM bot.message WHERE (`data`) != null)";
        $rezult = $conn->query($sql);
        $row = $rezult->fetch();
        return $row['data'];
    }
}

catch (PDOException $e) {
    echo "table error" . $e->getMessage();
}

print_r(getDataInSql());