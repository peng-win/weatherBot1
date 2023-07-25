<?php

//mail('support@testmoydom.ru', 'Test', 'Проверка CRON');
include("MenuMessages.php");
include ("MessagesWeather.php");
include("UpdatesForDb.php");

header('Content-Type: text/html; charset=utf-8');

$site_dir = dirname('https://junior.testmoydom.ru', 2) .'/'; // корень сайта
$bot_token = '6033179284:AAE351yAizdTZMnEAa21q9aAzc344hDesW4'; // токен бота
$data = file_get_contents('php://input');
$data = json_decode($data, true);

notice_about_changes($bot_token);
function notice_about_changes($bot_token): void
{
    $db = new SQLite3("weather.db");
    $request = new UpdatesForDb($db);
    $message = new MessagesWeather($bot_token);


    $limit = $request->get_count_from_weather_table($db);

    for($i = 0; $i<=$limit; $i++)
    {
        $chat_id = $request->get_all_users_weather_table($db, $i);

        $city = $request->get_city($db, $chat_id);
        $old_temp = $request->get_temperature($db, $chat_id);
        $new_temp = get_new_temp($city);

       // message_to_telegram($bot_token, $chat_id, $chat_id);
        if (abs($old_temp - $new_temp) >= 1) {
            $message->message_about_weather_add_db($bot_token, $chat_id, $city);
        }
    }
}

function get_new_temp($city)
{
    $units = "metric";
    $lang = "ru";
    $countDay = 1;
    $appID = "5fe43806571f7b2cbfb063ad1c66c240";
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&cnt=$countDay&lang=$lang&units=$units&appid=$appID";
    $data = @file_get_contents($url);
    $text_return = '';

    if ($data) {
            $clima = json_decode($data, true);
            foreach($clima['list'] as $p) {
                return $p['main']['temp'];

            }
    }

}
//$message = new MessagesWeather($bot_token);
//$chat_id = $data['message']['from']['id'];
//$chat_id = 769820969;
//$message->message_notice($bot_token, $chat_id);


//$old_temperature = $request->get_temperature($db,$chat_id);
//$new_temperature = get_new_temp();
//$text_return = "";

//if ($data) {

//}
