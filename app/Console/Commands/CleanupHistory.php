<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Entrada;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:history {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old entries and exits history (older than 1 day)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza del historial...');

        $fechaLimite = Carbon::now()->subDay();

        // Obtener entradas y salidas de clientes ocasionales más antiguas que 1 día
        $entradasAntiguas = Entrada::whereHas('vehiculo.cliente', function($query) {
                $query->where('tipo_cliente', 'ocasional');
            })
            ->whereHas('salida')
            ->where('created_at', '<', $fechaLimite)
            ->with(['salida', 'vehiculo.cliente'])
            ->get();

        if ($entradasAntiguas->isEmpty()) {
            $this->info('✅ No hay registros antiguos para limpiar.');
            return;
        }

        $this->info("📊 Encontrados {$entradasAntiguas->count()} entradas antiguas de clientes ocasionales.");

        if (!$this->option('force')) {
            if (!$this->confirm('¿Deseas continuar con la limpieza del historial?')) {
                $this->info('❌ Operación cancelada.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $entradasEliminadas = 0;
            $salidasEliminadas = 0;

            foreach ($entradasAntiguas as $entrada) {
                // Eliminar la salida primero
                if ($entrada->salida) {
                    $entrada->salida->delete();
                    $salidasEliminadas++;
                }

                // Eliminar la entrada
                $entrada->delete();
                $entradasEliminadas++;

                $this->line("🗑️  Registro de entrada/salida eliminado (ID: {$entrada->id})");
            }

            DB::commit();

            $this->info("✅ Limpieza del historial completada:");
            $this->info("   - Entradas eliminadas: {$entradasEliminadas}");
            $this->info("   - Salidas eliminadas: {$salidasEliminadas}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error durante la limpieza del historial: " . $e->getMessage());
        }
    }
}
