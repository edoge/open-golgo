<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Players;

class RankCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boot:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl current rank';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $uri    = 'http://rororrank.ddo.jp/roror/ranking';
        $client = new Client();
        $target = $client->request('get', $uri);

        $target->filter('#mBottomp table tr')->each(function($element){
            if(count($element->filter('td'))){
                $rank = $element->filter('td')->eq(1)->text();
                $name = $element->filter('td')->eq(3)->text();
                $player = Players::where('name', $name)->first();
                if (is_null($player)) {
                    $player = new Players();
                    $player->name = $name;
                }
                $player->rank = $rank;
                $player->save();
            }
        });
    }
}
