<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WindowsAzure\Common\ServicesBuilder;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boot:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup sqlite database file to azure blob.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connection = "DefaultEndpointsProtocol=https;AccountName=".config('app.blob.account').";AccountKey=".config('app.blob.key');
        $blob = ServicesBuilder::getInstance()->createBlobService($connection);

        $database = base_path() . '/database/database.sqlite';
        $file = fopen($database, "r");
        $blob->createBlockBlob(config('app.blob.container'), 'database.sqlite', $file);
        fclose($file);
    }
}
