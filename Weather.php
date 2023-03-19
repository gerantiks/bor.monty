<?php
    require_once __DIR__ . "/Secret_info.php";
    $weatherUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city[1]}&cnt=4&appid={$weatherToken}&units=metric&lang=ua";
    @$result = json_decode(file_get_contents($weatherUrl), true);
    $iconArray =[
        '01d' => '☀️', '01n' => '🌝',
        '02d' => '🌤', '02n' => '🌤',
        '03d' => '☁️', '03n' => '☁️',
        '04d' => '☁️', '04n' => '☁️',
        '09d' => '🌧', '09n' => '🌧',
        '10d' => '🌦', '10n' => '🌦',
        '11d' => '🌩', '11n' => '🌩',
        '13d' => '❄️', '13n' => '❄️',
        '50d' => '🌫', '50n' => '🌫'
    ];

    if (empty($result)) {
        $answer = "Таке місто не існує, введіть коректну назву !";
        $sendUserMessage = $answer;
    } else {
        $weather = "";
        for ($i = 0; $i < 4; $i++) {
            $temperature = round($result['list'][$i]['main']['temp']);
            $description = ucfirst($result['list'][$i]['weather'][0]['description']); // Описание
            var_dump($description);
            $icon = $result['list'][$i]['weather'][0]['icon'];
            $date = $result['list'][$i]['dt_txt'];
            $humidity = $result['list'][$i]['main']['humidity']; // тиск
            $windyDeg = (int) $result['list'][$i]['wind']['deg'];
            $windySpeed = $result['list'][$i]['wind']['speed'];

            $windyDirection = match (true) {
                ($windyDeg <= 360 && $windyDeg >= 350 || $windyDeg <= 10) => "Північний",
                ($windyDeg < 80 && $windyDeg > 10) => "Північно-східний",
                ($windyDeg >= 80 && $windyDeg <= 110) => "Східний",
                ($windyDeg > 110 && $windyDeg < 170 ) => "Південно-східний",
                ($windyDeg >= 170 && $windyDeg <= 190) => "Південний",
                ($windyDeg > 190 && $windyDeg < 260) => "Південно-західний",
                ($windyDeg >= 260 && $windyDeg <= 280) => "Західний",
                ($windyDeg > 280 && $windyDeg < 350) => "Північно-західний",
            };

            $textWeather = <<<END
        ● Прогноз погоди в  <b>$city[1]</b>, 
        на дату $date,
        Температура: {$temperature} °C,
        Хмарність: <b>{$description}</b> $iconArray[$icon],
        Швидкість вітру: $windySpeed м/с, 
        Напрямок вітру: $windyDirection, 
        Вологість: $humidity. 
        -------------------------------------------------------------------

        END;

            $weather .= $textWeather;
            $sendUserMessage = $weather;
        }
    }



    //print_r($humidity);
    //print_r($result);




