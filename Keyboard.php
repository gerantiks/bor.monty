<?php
// Блок с клавиатурой для тг бота

// Написать анонимную функцию для всех
$buttonsCancelOrSend = [
    'inline_keyboard' => [
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
];

$buttonCancel = [
    'inline_keyboard' => [
        [    
            [   
                "text" => "Отменить",
                "callback_data" => "1"
            ]
        ]
        
    ]
];

$buttonsMessageOrWeather = [
    'keyboard' =>[ 
        [
            ['text' => "Відправити лист"],
            ['text' => "Погода"],
            ['text' => "Інструкція листа"]
        ],
    ],
    'resize_keyboard' => true,
    'one_time_keyboard' => true
    
];


$keyboard = function($buttons)
{
    return json_encode($buttons);
};


var_dump($keyboard($buttonsCancelOrSend));

