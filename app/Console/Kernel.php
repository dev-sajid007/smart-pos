<?php

namespace App\Console;

use App\Console\Commands\BackupDatabaseCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        BackupDatabaseCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('database:backup')->daily();
        // Backups
        // $schedule->command('backup:clean')->dailyAt('01:30');
        // $schedule->command('backup:run --only-db')->dailyAt('01:35');
        $schedule->command('backup:run --only-db');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
