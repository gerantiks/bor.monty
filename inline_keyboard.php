<?php
$keyboard = json_encode([
    "inline_keyboard" => [
        [
            [
                "text" => "Назад",
                "callback_data" => "1"
            ],
            [
                "text" => "Все вірно?",
                "callback_data" => "2"
            ]
        ]
    ]
]);
