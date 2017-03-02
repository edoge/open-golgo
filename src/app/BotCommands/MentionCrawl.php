<?php

namespace App\BotCommands;

class MentionCrawl
{
    public function handle($bucket)
    {
        \Illuminate\Support\Facades\Artisan::call('boot:crawl', []);
        return "done.";
    }
}
