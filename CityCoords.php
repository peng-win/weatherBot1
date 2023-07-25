<?php

class CityCoords
{
    public $bot_token;
    public function __construct($bot_token)
    {
        $this->bot_token =$bot_token;
    }

    public function find_city($bot_token, $chat_id, $city)
    {
        $message_menu = new MenuMessages($bot_token);
        $key = "YfNxSmoYSDqi";
        $url = "https://api.geotree.ru/search.php?key=$key&term=$city&level=4";
        $data=@file_get_contents($url);


        $array[] = "";
        $text_return = "";
        if($data)
        {
            $findcity = json_decode($data, true);

            $k=0;
            $text_return = "Выберите населенный пункт: "."\n";
            foreach ($findcity as $r)
            {
                    $address = $city." ".$r['parents']['level_1']['name_source']."\n";
                    //$text_return.= $address;

                    $lat = $r['geo_center']['lat'];
                    $lon = $r['geo_center']['lon'];
                    $coords = "lat=".$lat."&lon=".$lon;
                    $array[$k] = [['text' => $address, 'callback_data'=>$coords]];

                    $k++;
            }

            $reply_markup = json_encode([
                "inline_keyboard" =>
                    $array,
            ]);

        }
        else
        {
            $text_return = 'Некорректный ввод названия населенного пункта. Повторите попытку';
        }

        $message_menu->message_to_telegram_inline($bot_token, $chat_id, $text_return, $reply_markup);
    }

    public function find_coord($bot_token, $chat_id, $address)
    {
        $message_menu = new MenuMessages($bot_token);
        $key = "YfNxSmoYSDqi";
        $url = "https://api.geotree.ru/search.php?key=$key&$address";
        $text_return = "";
        $data = @file_get_contents($url);

        if($data)
        {
            $coords = json_decode($data, true);

//                $text_return .= $coords['geo_center']['lon']."\n";
//                $text_return .= $coords['geo_center']['lat']."\n";
            $text_return = "ddd";
        }
        else
        {
            $text_return = 'Некорректный ввод названия населенного пункта. Повторите попытку';
        }

        $message_menu->message_to_telegram($bot_token, $chat_id, $text_return);
    }

}