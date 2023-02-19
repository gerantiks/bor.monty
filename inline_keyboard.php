<?php
$keyboard = json_encode([
    "inline_keyboard" => [
        [
            [
                "text" => "Повернутись назад",
                "callback_data" => "1"
            ],
            [
                "text" => "Відправити ?",
                "callback_data" => "2"
            ]
        ]
    ]
]);

