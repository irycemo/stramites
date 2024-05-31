<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        /* $schedule->command('expirar:tramites-vencidos')->daily(); */
        $schedule->command('concluir:consultas')->daily();
        $schedule->command('expirar:copias')->daily();
        $schedule->command('caducar:tramites')->daily();
        $schedule->command('cache:recaudacion')->everyMinute();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
