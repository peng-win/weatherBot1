<?php

namespace weatherBot;


use SQLite3;

class MessagesWeather
{
    public $bot_token;

    public function __construct($bot_token)
    {
        $this->bot_token = $bot_token;
    }

    public function message_about_weather_add_db($bot_token, $chat_id, $city): void
    {
        $message_menu = new MenuMessages($bot_token);
        $db = new SQLite3("weather.db");
        $request = new UpdatesForDb($db);

        $units = "metric";
        $lang = "ru";
        $countDay = 1;
        $appID = "5fe43806571f7b2cbfb063ad1c66c240";
        $url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&cnt=$countDay&lang=$lang&units=$units&appid=$appID";
        $data = @file_get_contents($url);
        $text_return = '';

        if ($data) {
            $clima = json_decode($data, true);
            foreach ($clima['list'] as $p) {
                $text_return .= $city . "\n";
                $text_return .= date("d.m.Y", $p['dt']) . ": " . $p['weather'][0]['description'] . "\n";
                $text_return .= "Температура: " . round($p['main']['temp']) . "°C\n";
                $text_return .= "Влажность: " . $p['main']['humidity'] . "%\n";
                $text_return .= "Ветер: " . $p['wind']['speed'] . "км/ч\n\n";

                $temperature = round($p['main']['temp']);

                $get_user = $request->get_user_weather_table($db, $chat_id);

                if (!empty($get_user)) {
                    $bot_state = "sendWeather";
                    $request->update_bot_state($db, $chat_id, $bot_state);
                    $request->update_user_city($db, $chat_id, $city, $temperature);
                } else {
                    $bot_state = "sendWeather";
                    $request->update_bot_state($db, $chat_id, $bot_state);
                    $request->add_user_city($db, $chat_id, $city, $temperature);
                }
            }
        } else {
            $text_return = 'Некорректный ввод названия населенного пункта. Повторите попытку';
        }
        $message_menu->message_to_telegram($bot_token, $chat_id, $text_return);
    }

    public function message_about_weather($bot_token, $chat_id, $text): void
    {
        $db = new SQLite3("weather.db");
        $request = new UpdatesForDb($db);
        $message_menu = new MenuMessages($bot_token);
        $text_array = explode(" ", $text);
        $num = (int)$text_array[array_key_last($text_array)];

        $city = $request->get_city($db, $chat_id);
        $units = "metric";
        $lang = "ru";
        $countDay = ($num > 0) ? $num : 1;
        $appID = "5fe43806571f7b2cbfb063ad1c66c240";
        $url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&cnt=$countDay&lang=$lang&units=$units&appid=$appID";
        $data = @file_get_contents($url);
        $text_return = "";
        if ($data) {
            $clima = json_decode($data, true);
            foreach ($clima['list'] as $p) {
                $text_return .= $city . "\n";
                $text_return .= date("d.m.Y", $p['dt']) . ": " . $p['weather'][0]['description'] . "\n";
                $text_return .= "Температура: " . round($p['main']['temp']) . "°C\n";
                $text_return .= "Влажность: " . $p['main']['humidity'] . "%\n";
                $text_return .= "Ветер: " . $p['wind']['speed'] . "км/ч\n\n";
            }
            $temperature = round($clima['list']['main']['temp']);
            $request->update_user_city($db, $chat_id, $city, $temperature);
        } else {
            $text_return = 'Некорректный ввод названия населенного пункта. Повторите попытку';
        }
        $message_menu->message_to_telegram($bot_token, $chat_id, $text_return);
    }
}
/*    public function message_about_weather_add_db_1($bot_token, $chat_id, $city, $coords): void
    {
        $db = new SQLite3("weather.db");
        $request = new UpdatesForDb($db);

        $units = "metric";
        $lang = "ru";

        $appID = "5fe43806571f7b2cbfb063ad1c66c240";
        $url = "https://api.openweathermap.org/data/2.5/weather?&lat=57.73&lon=41.06&lang=$lang&units=$units&appid=$appID";

        $data = @file_get_contents($url);
        $text_return = '';

        if ($data)
        {
            $clima = json_decode($data);
            foreach ($clima as $p)
            {
                $text_return .= date("d.m.Y", $p['dt']).": ".$p['weather'][0]['description']."\n";
            }
        }
        else
        {
            $text_return = 'Некорректный ввод названия населенного пункта. Повторите попытку';
        }
        $message_menu->message_to_telegram($bot_token, $chat_id, $text_return);
    }*/
