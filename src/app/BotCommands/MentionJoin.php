<?php

namespace App\BotCommands;

class MentionJoin
{
    public function handle($bucket)
    {
        $bucket->getSource()->join(env('BOT_NAME', 'open_g'), env('TARGET_CHANNEL', '#open-golgo'));
    }
}
