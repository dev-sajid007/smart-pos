<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;

class backupController extends Controller
{
    public function db_backup()
    {
        $host       = env('DB_HOST');
        $username   = env('DB_USERNAME');
        $password   = env('DB_PASSWORD');
        $database   = env('DB_DATABASE');
        $ds         = DIRECTORY_SEPARATOR;


        $path       = public_path() . $ds . 'app' . $ds . 'backups' . $ds;
        $file       = 'dump.sql';
        $directory  = $path . $file;
        $filename   = 'backup_' . Carbon::now()->format('Y-m-d') . '.sql';
        $command    = sprintf('mysqldump -h %s -u %s -p\'%s\' %s > %s', $host, $username, $password, $database, $path . $file);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $response = MySql::create()
            ->setDbName(env('DB_DATABASE'))
            ->setUserName(env('DB_USERNAME'))
            ->setPassword(env('DB_PASSWORD'))
            ->setHost(env('DB_HOST'))
            ->setPort(env('DB_PORT'))
            ->doNotCreateTables()
            ->dumpToFile($directory);

        // download to local
        return response()->download($directory, $filename);
    }
    // public function dbx_backup()
    // {
    //     $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";
    //     $databaseName = env('DB_DATABASE');
    //     $userName     = env('DB_USERNAME');
    //     $password     = env('DB_PASSWORD');
    //     $host         = env('DB_HOST');
    //     $port         = env('DB_PORT');
    //     $path         = env('DUMP_PATH');
    //     $storage_path = storage_path() . "/app/backup/";
    //     $command = " " . $path . " --user=" . $userName .
    //         " --password=" . $password . " --host=" . $host .
    //         " " . $databaseName . "  > " . $storage_path . $filename;
    //     $returnVar = NULL;
    //     $output = NULL;
    //     exec($command, $output, $returnVar);
    //     $full_path = $storage_path . $filename;
    //     return response()->download($full_path);
    // }



    // public function db_backup_x()
    // {
    //     $host = env('DB_HOST');
    //     $username = env('DB_USERNAME');
    //     $password = env('DB_PASSWORD');
    //     $database = env('DB_DATABASE');
    //     $ds = '/'; // DIRECTORY_SEPARATOR


    //     $path = public_path() . $ds . 'app' . $ds . 'backups' . $ds;
    //     $file = 'dump.sql';
    //     $directory = $path . $file;
    //     $filename = 'backup_' . fdate(now(), 'Y_m_d_h_i_s') . '.sql';
    //     $command = sprintf('mysqldump -h %s -u %s -p\'%s\' %s > %s', $host, $username, $password, $database, $path . $file);


    //     $date = date('Y-m-d');
    //     $databaseName = env('DB_DATABASE');
    //     $userName     = env('DB_USERNAME');
    //     $password     = env('DB_PASSWORD');
    //     $host         = env('DB_HOST');
    //     $port         = env('DB_PORT');

    //     MySql::create()
    //     ->setDumpBinaryPath('')
    //     ->setDbName($databaseName)
    //     ->setUserName($userName)
    //     ->setPassword($password)
    //     ->setHost($host)
    //     ->setPort($port)
    //     ->doNotCreateTables()
    //     ->dumpToFile($directory);


    //     // download to local
    //     return response()->download($directory, $filename);
    // }
}
