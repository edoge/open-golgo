<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WindowsAzure\Common\ServicesBuilder;

class RepairDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boot:repair';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'repair sqlite database file from azure blob.';

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
        $file = $blob->getBlob(config('app.blob.container'), 'database.sqlite');
        file_put_contents($database, $file->getContentStream());
    }
}
