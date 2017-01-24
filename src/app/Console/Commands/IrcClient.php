<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Hoa;

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
        $uri    = 'irc://irc.ircnet.ne.jp:6661';
        $client = new Hoa\Irc\Client(new Hoa\Socket\Client($uri));

        $client->on('open', function (Hoa\Event\Bucket $bucket) {
            $bucket->getSource()->join('edabot', '#brahmer');
            return;
        });
        $client->on('mention', function (Hoa\Event\Bucket $bucket) {
            $data    = $bucket->getData();
            $message = $data['message']; // do something with that.

            $bucket->getSource()->say(
                $data['from']['nick'] . ': What?'
            );
            return;
        });
        $client->run();
    }
}
