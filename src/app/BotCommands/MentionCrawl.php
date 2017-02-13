<?php

namespace App\BotCommands;

class MentionCrawl
{
    public function handle()
    {
        \Illuminate\Support\Facades\Artisan::call('boot:crawl', []);
        return "done."
    }
}
