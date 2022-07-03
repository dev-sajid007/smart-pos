<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Support\Facades\File;

class BackupDatabaseCommand extends Command
{

    protected $signature = 'database:backup';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";
        $databaseName = env('DB_DATABASE');
        $userName     = env('DB_USERNAME');
        $password     = env('DB_PASSWORD');
        $host         = env('DB_HOST');
        $port         = env('DB_PORT');
        $path         = env('DUMP_PATH');
        $storage_path = storage_path() . "/app/backup/";

        $command = " " . $path . " --user=" . $userName .
                   " --password=" . $password . " --host=" . $host .
                   " " . $databaseName . "  > " . $storage_path . $filename;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);



        // File::put('sump.sql','');
        // MySql::create()
        // ->setDumpBinaryPath('')
        // ->setDbName($databaseName)
        // ->setUserName($userName)
        // ->setPassword($password)
        // ->setHost($host)
        // ->setPort($port)
        // ->doNotCreateTables()
        // ->dumpToFile('sump.sql');
    }
}
