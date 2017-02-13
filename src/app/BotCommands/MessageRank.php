<?php

namespace App\BotCommands;

use App\Models\Players;

class MessageRank
{
    public function handle($message)
    {
        if (count($message) !== 2){
            $bucket->getSource()->say("usage: rank <target>.");
            return;
        }
        $player = Players::where('name', trim($message[1]))->first();
        if (is_null($player)) {
            return trim($message[1]) . ' is not found.';
        } else {
            return $player->name . '(' . $player->rank . ')' . $player->win . '/' . $player->lose;
        }
    }
}

