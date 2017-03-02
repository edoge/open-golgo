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
        $uri    = env('IRC_SERVER', 'irc://irc.livedoor.ne.jp:6662');
        $client = new Hoa\Irc\Client(new Hoa\Socket\Client($uri));

        $client->on('open', function (Hoa\Event\Bucket $bucket) {
            $bucket->getSource()->join(env('BOT_NAME', 'open_g'), env('BOT_CHANNEL', '#open-golgo'));
            return;
        });
        $client->on('message', function (Hoa\Event\Bucket $bucket) {
            $data    = $bucket->getData();
            $message = $data['message'];
            if (strPos($message, ' ') !== false) {
                $words = explode(' ', $message);
                $key = trim(head($words));
                $class_name = 'App\BotCommands\Message' . ucfirst($key);
                if (class_exists($class_name)) {
                    $executor = new $class_name();
                    $bucket->getSource()->say($executor->handle($words));
                }
            } else {
                $mapping = config('app.mapping');
                $converted = mb_convert_encoding(trim($message), mb_internal_encoding(), config('app.irc_encoding'));
                if (array_key_exists($converted, $mapping)) {
                    if (is_callable($mapping[$converted])) {
                        $bucket->getSource()->say(mb_convert_encoding($mapping[$converted](), config('app.irc_encoding')));
                    } else {
                        $class_name = 'App\BotCommands\Message' . $mapping[$converted];
                        $executor = new $class_name();
                        $bucket->getSource()->say($executor->handle($converted, $data['from']));
                    }
                }
            }
        });
        $client->on('mention', function(Hoa\Event\Bucket $bucket) {
            $data    = $bucket->getData();
            $message = $data['message'];
            $words = explode(' ', $message);
            if (count($words) < 2) return;
            $class_name = 'App\BotCommands\Mention' . ucfirst(camel_case(trim($words[1])));
            if (class_exists($class_name)) {
                $executor = new $class_name();
                $bucket->getSource()->say($executor->handle($bucket));
            }
        });
        $client->run();
    }
}
