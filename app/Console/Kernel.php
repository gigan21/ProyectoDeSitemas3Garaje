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
        // Limpiar historial de entradas y salidas diariamente a las 2:00 AM
        $schedule->command('cleanup:history --force')
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Limpiar clientes ocasionales que puedan haber quedado pendientes
        $schedule->command('cleanup:ocasional-clients --force')
            ->dailyAt('02:30')
            ->withoutOverlapping()
            ->runInBackground();
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
