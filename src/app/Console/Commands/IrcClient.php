<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Hoa;
use App\Models\Players;

class IrcClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boot:irc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'boot irc client';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $uri    = 'irc://irc.livedoor.ne.jp:6662';
        $client = new Hoa\Irc\Client(new Hoa\Socket\Client($uri));

        $client->on('open', function (Hoa\Event\Bucket $bucket) {
            $bucket->getSource()->join('open_g', '#open-golgo');
            return;
        });
        $client->on('message', function (Hoa\Event\Bucket $bucket) {
            $data    = $bucket->getData();
            $message = $data['message'];
            $words = explode(' ', $message);
            switch(trim(head($words))) {
                case "tg":
                    $bucket->getSource()->say("not implemented.");
                    break;
                case "rank":
                    if (count($words) !== 2){
                        $bucket->getSource()->say("usage: rank <target>.");
                        return;
                    }
                    $player = Players::where('name', trim($words[1]))->first();
                    if (is_null($player)) {
                        $bucket->getSource()->say(trim($words[1]) . " is not found.");
                    } else {
                        $bucket->getSource()->say($player->name . "'s rank is ". $player->rank);
                    }
                    break;
                default:
                    return;
            }
            return;
        });
        $client->on('mention', function(Hoa\Event\Bucket $bucket) {
            $data    = $bucket->getData();
            $message = $data['message'];
            if (strpos($message, 'crawl') > 0) {
                \Illuminate\Support\Facades\Artisan::call('boot:crawl', []);
                $bucket->getSource()->say("done.");
                return;
            } else if (strpos($message, 'join') > 0) {
                $bucket->getSource()->join('open_g', '#roror');
                return;
            }
            $bucket->getSource()->say(
                $data['from']['nick'] . ': What?'
            );
        });
        $client->run();
    }
}
