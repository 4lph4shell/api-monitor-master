<?php


namespace App\Channels;


use App\Interfaces\ChannelsInterface;
use TelegramBot\Api;

class TelegramChannel implements ChannelsInterface
{
    /**
     * Sends a message to telegram.
     *
     * @param string $message
     *
     * @throws \TelegramBot\Api\Exception
     */
    public static function send(string $message): void
    {
        $chat_id = env('TELEGRAM_CHAT');
        $token = env('TELEGRAM_TOKEN');
        $bot = new \TelegramBot\Api\BotApi($token);

        // Send the message to the telegram chat
        $bot->sendMessage($chat_id, $message, null, false, null, null, true);
    }
}
