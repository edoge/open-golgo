<?php

namespace App\BotCommands;

class MessageNewhost
{
    public function handle($message, $from)
    {
        $ret = $from['nick'] . ' ■新規干す ' . $from['host'] . ' @' . mb_substr($message, -1);
        return mb_convert_encoding($ret, config('app.irc_encoding'));
    }
}

