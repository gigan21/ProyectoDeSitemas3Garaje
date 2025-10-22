<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Salida;
use App\Models\Entrada;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupOcasionalSalidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:ocasional-salidas {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up salidas records of occasional clients (only when manually executed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza de salidas de clientes ocasionales...');

        // Obtener salidas de clientes ocasionales
        $salidasOcasionales = Salida::whereHas('entrada.vehiculo.cliente', function($query) {
                $query->where('tipo_cliente', 'ocasional');
            })
            ->with(['entrada.vehiculo.cliente'])
            ->get();

        if ($salidasOcasionales->isEmpty()) {
            $this->info('✅ No hay salidas de clientes ocasionales para limpiar.');
            return;
        }

        $this->info("📊 Encontradas {$salidasOcasionales->count()} salidas de clientes ocasionales.");

        if (!$this->option('force')) {
            if (!$this->confirm('¿Deseas continuar con la limpieza de salidas de clientes ocasionales?')) {
                $this->info('❌ Operación cancelada.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $salidasEliminadas = 0;
            $entradasEliminadas = 0;

            foreach ($salidasOcasionales as $salida) {
                // Eliminar la entrada relacionada
                if ($salida->entrada) {
                    $salida->entrada->delete();
                    $entradasEliminadas++;
                }

                // Eliminar la salida
                $salida->delete();
                $salidasEliminadas++;

                $this->line("🗑️  Salida eliminada (ID: {$salida->id})");
            }

            DB::commit();

            $this->info("✅ Limpieza de salidas completada:");
            $this->info("   - Salidas eliminadas: {$salidasEliminadas}");
            $this->info("   - Entradas eliminadas: {$entradasEliminadas}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error durante la limpieza de salidas: " . $e->getMessage());
        }
    }
}
