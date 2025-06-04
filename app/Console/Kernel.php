<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Os comandos Artisan fornecidos pela aplicação.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        // Registre aqui o comando de importação de municípios
        \App\Console\Commands\ImportarMunicipiosIBGE::class,
    ];

    /**
     * Defina o schedule de tarefas agendadas (opcional, não usado aqui).
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Registre os “loaders” de comandos Artisan.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
