<?php

namespace weatherBot;

class MenuMessages
{
    public $bot_token;

    public function __construct($bot_token)
    {
        $this->bot_token = $bot_token;
    }

    function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = ''): void
    {
        $ch = curl_init();
        if ($reply_markup == '') {
            $btn[] = ["text" => "Узнать погоду", "callback_data" => '/weather'];
            $reply_markup = json_encode(["keyboard" => [$btn], "resize_keyboard" => true]);
        }
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text,
                'reply_markup' => $reply_markup,
            ]
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
    }

    function message_to_telegram_inline($bot_token, $chat_id, $text, $reply_markup): void
    {
        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text,
                'reply_markup' => $reply_markup
            ]
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
    }

    function first_message_to_telegram($bot_token, $chat_id, $text): void
    {
        $ch = curl_init();

        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => $chat_id,
                'parse_mode' => 'HTML',
                'text' => $text,
            ]
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
    }
}

