<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Entrada;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:system {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete system cleanup: occasional clients and old history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza completa del sistema...');

        // Paso 1: Limpiar clientes ocasionales
        $this->info('📋 Paso 1: Limpiando clientes ocasionales...');
        $this->call('cleanup:ocasional-clients', ['--force' => true]);

        // Paso 2: Limpiar historial antiguo
        $this->info('📋 Paso 2: Limpiando historial antiguo...');
        $this->call('cleanup:history', ['--force' => true]);

        $this->info('✅ Limpieza completa del sistema finalizada.');
    }
}
