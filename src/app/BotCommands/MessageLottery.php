<?php

namespace App\BotCommands;

class MessageLottery
{
    public function handle($message, $from)
    {
        $entries = [
            "大吉" => 1,
            "吉" => 20,
            "凶" => 40,
            "大凶" => 39
        ];
        return mb_convert_encoding(\App\Helpers::array_rand_weighted($entries), config('app.irc_encoding'));
    }
}

