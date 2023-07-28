<?php

namespace weatherBot;

require_once "vendor/autoload.php";
//ссылка на бота https://t.me/irych_test_weather_bot
//токен бота 6033179284:AAE351yAizdTZMnEAa21q9aAzc344hDesW4;
//$chat_id = 769820969;
//$urlQuery = "https://api.telegram.org/bot". $token ."/sendMessage?chat_id=". $chat_id ."&text=" . $textMessage;
use SQLite3;

header('Content-Type: text/html; charset=utf-8');

$site_dir = dirname('https://junior.testmoydom.ru', 2) . 'index.php/'; // корень сайта
$bot_token = '6033179284:AAE351yAizdTZMnEAa21q9aAzc344hDesW4'; // токен бота
$data = file_get_contents('php://input');
$data = json_decode($data, true);
$db = new SQLite3("weather.db");

$request = new UpdatesForDb($db);
$message_weather = new MessagesWeather($bot_token);
$message_menu = new MenuMessages($bot_token);

//$find_city_coord = new CityCoords($bot_token);
//if(!empty($data['callback_query']['data']))
//{
//   // $find_city_coord->find_coord($bot_token, $data['callback_query']['from']['id'], trim($data['callback_query']['data']));
//    $message_weather->message_about_weather_add_db_1($bot_token, $data['callback_query']['from']['id'], $data['callback_query']['message'], trim($data['callback_query']['data']));
//}

if (!empty($data['message']['text'])) {
    $chat_id = $data['message']['from']['id'];
    $user_name = $data['message']['from']['username'];
    $first_name = $data['message']['from']['first_name'];
    $last_name = $data['message']['from']['last_name'];
    $text = trim($data['message']['text']);
    $callback_query = $data['callback_query']['data'];
    $callback_data = $callback_query['data'];


    $get_user = $request->get_user_dialog_table($db, $chat_id);

    if (empty($get_user)) {

        $bot_state = "awaitCity";
        $request->add_new_user($db, $chat_id, $bot_state);

        $text_return = "Добро пожаловать, введите название населенного пункта";
        $message_menu->first_message_to_telegram($bot_token, $chat_id, $text_return);

    } else {

        $sql_bot_state = $request->get_bot_state($db, $chat_id);

        if ($text == "/start") {

            $bot_state = "awaitCity";
            $request->update_bot_state($db, $chat_id, $bot_state);

            $text_return = "Введите название населённого пункта";
            $message_menu->first_message_to_telegram($bot_token, $chat_id, $text_return);

        } elseif ($text == '/help') {

            $text_return = "Привет, вот команды, которые я понимаю:
                                                 /help - список команд
                                                 /weather - узнать погоду";
            $message_menu->message_to_telegram($bot_token, $chat_id, $text_return);

        } elseif ((str_starts_with($text, '/weather') || mb_strstr($text, "Узнать погоду")) && $sql_bot_state=="sendWeather") {

            $message_weather->message_about_weather($bot_token, $chat_id, $text);
        } else {

            $city = $text;
            $message_weather->message_about_weather_add_db($bot_token, $chat_id, $city);
            //$find_city_coord->find_city($bot_token,$chat_id,$city);
        }


    }
}
