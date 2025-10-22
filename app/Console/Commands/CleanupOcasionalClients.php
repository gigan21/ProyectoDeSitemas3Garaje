<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Entrada;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupOcasionalClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:ocasional-clients {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up occasional clients who have already left the parking lot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza de clientes ocasionales...');

        // Obtener clientes ocasionales que ya tienen salida registrada
        $clientesOcasionalesConSalida = Cliente::where('tipo_cliente', 'ocasional')
            ->whereHas('vehiculos.entradas.salida')
            ->with(['vehiculos.entradas.salida'])
            ->get();

        if ($clientesOcasionalesConSalida->isEmpty()) {
            $this->info('✅ No hay clientes ocasionales para limpiar.');
            return;
        }

        $this->info("📊 Encontrados {$clientesOcasionalesConSalida->count()} clientes ocasionales con salida registrada.");

        if (!$this->option('force')) {
            if (!$this->confirm('¿Deseas continuar con la limpieza?')) {
                $this->info('❌ Operación cancelada.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $clientesEliminados = 0;
            $vehiculosEliminados = 0;

            foreach ($clientesOcasionalesConSalida as $cliente) {
                // Verificar que todos los vehículos del cliente tengan salida registrada
                $todosConSalida = $cliente->vehiculos->every(function ($vehiculo) {
                    return $vehiculo->entradas->every(function ($entrada) {
                        return $entrada->salida !== null;
                    });
                });

                if ($todosConSalida) {
                    // Eliminar vehículos del cliente (pero mantener las salidas)
                    foreach ($cliente->vehiculos as $vehiculo) {
                        $vehiculosEliminados++;
                        $vehiculo->delete();
                    }

                    // Eliminar el cliente
                    $cliente->delete();
                    $clientesEliminados++;

                    $this->line("🗑️  Cliente '{$cliente->nombre}' eliminado con sus vehículos (salidas preservadas).");
                }
            }

            DB::commit();

            $this->info("✅ Limpieza completada:");
            $this->info("   - Clientes eliminados: {$clientesEliminados}");
            $this->info("   - Vehículos eliminados: {$vehiculosEliminados}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error durante la limpieza: " . $e->getMessage());
        }
    }
}
