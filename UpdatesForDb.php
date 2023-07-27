<?php

//запросы для добавления данных в базу

namespace weatherBot;


use Exception;

class UpdatesForDb
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add_new_user($db, $chat_id, $bot_state): void
    {
        //добавляем нового пользователя
        try {
            $db->exec("insert into userDialog (userId, botState) values ('$chat_id', '$bot_state')");
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        }
    }

    public function add_user_city($db, $chat_id, $city, $temperature): void
    {
        //добавляем название населенного пункта
        try {
            $db->exec("insert into weatherForUser (userId, userCity, userWeatherTemp) values ('$chat_id', '$city', '$temperature')");
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        }
    }

//запросы для получения данных из базы
    public function get_user_weather_table($db, $chat_id)
    {
        //проверяем наличие данных о пользователе в таблице weatherForUser
        return $db->querySingle("select userId from weatherForUser where userId='$chat_id'");
    }

    public function get_all_users_weather_table($db, $limit)
    {
        //достаем список всех пользователей в таблице weatherForUser
        return $db->querySingle("select userId from weatherForUser limit '$limit',1");

    }

    public function get_count_from_weather_table($db)
    {
        return $db->querySingle("select count(*) from weatherForUser");

    }

    public function get_user_dialog_table($db, $chat_id)
    {
        //проверяем наличие данных о пользователе в таблице userDialog
        return $db->querySingle("select userId from userDialog where userId='$chat_id'");
    }

    public function get_city($db, $chat_id)
    {
        //получаем данные о населенном пункте
        return $db->querySingle("select userCity from weatherForUser where userId='$chat_id'");
    }

    public function get_bot_state($db, $chat_id)
    {
        return $db->querySingle("select botState from userDialog where userId='$chat_id'");
    }

    public function get_temperature($db, $chat_id)
    {
        return $db->querySingle("select userWeatherTemp from weatherForUser where userId='$chat_id'");
    }

//запросы для обновления базы данных

    public function update_user_city($db, $chat_id, $city, $temperature): void
    {
        //обновляем данные о населенном пункте
        try {
            $db->exec("update weatherForUser set userCity='$city', userWeatherTemp='$temperature' where userId='$chat_id'");
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        }
    }

    public function update_bot_state($db, $chat_id, $bot_state): void
    {
        //обновляем данные о состоянии бота
        try {
            $db->exec("update userDialog set botState = '$bot_state' where userId='$chat_id'");
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        }
    }
}
