<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Entrada;
use App\Models\Cliente;
use App\Models\PagoMensual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalidaController extends Controller
{
     public function index()
    {
        $salidas = Salida::with('entrada.vehiculo')
            ->orderBy('fecha_salida', 'desc')
            ->get(); // Cambia paginate(10) por get()
            
        return view('salidas.index', compact('salidas'));
    }

  public function create()
{
    $entradas = Entrada::whereDoesntHave('salida')
        ->whereHas('vehiculo.cliente', function($query) {
            $query->where('tipo_cliente', 'ocasional'); // Filtra solo clientes ocasionales
        })
        ->with('vehiculo')
        ->get();
        
    return view('salidas.create', compact('entradas'));
}

    public function store(Request $request)
    {
        $request->validate([
            'entrada_id' => 'required|exists:entradas,id',
            'total_pagado' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Obtener la entrada relacionada con todas las relaciones necesarias
            $entrada = Entrada::with(['vehiculo.cliente', 'espacio'])->find($request->entrada_id);
            
            // Liberar el espacio
            $entrada->espacio->update(['estado' => 'libre']);

            // Registrar la salida
            $salida = Salida::create([
                'entrada_id' => $request->entrada_id,
                'fecha_salida' => now(),
                'total_pagado' => $request->total_pagado
            ]);

            // NO eliminar automáticamente los registros de salidas
            // Los registros de salidas permanecerán hasta limpieza manual

            DB::commit();

            return redirect()->route('salidas.index')
                ->with('success', 'Salida registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar salida: ' . $e->getMessage());
            return back()->with('error', 'No se pudo registrar la salida: ' . $e->getMessage());
        }
    }
    public function getFechaSalidaFormateadaAttribute()
{
    return $this->fecha_salida instanceof \Carbon\Carbon 
        ? $this->fecha_salida->format('d/m/Y H:i')
        : \Carbon\Carbon::parse($this->fecha_salida)->format('d/m/Y H:i');
}
public function destroy($id)
{
    DB::beginTransaction();
    try {
        $salida = Salida::with('entrada.espacio')->findOrFail($id);
        
        // Volver a marcar el espacio como ocupado
        if ($salida->entrada && $salida->entrada->espacio) {
            $salida->entrada->espacio->update(['estado' => 'ocupado']);
        }
        
        $salida->delete();
        
        DB::commit();
        
        return redirect()->route('salidas.index')
            ->with('success', 'Salida eliminada correctamente');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al eliminar salida: ' . $e->getMessage());
        return back()->with('error', 'No se pudo eliminar la salida: ' . $e->getMessage());
    }
}
public function pagar(Salida $salida)
{
    // Carga la relación de entrada y vehículo para mostrar la información
    $salida->load('entrada.vehiculo');
    
    return view('salidas.pagar', compact('salida'));
}

public function procesarPago(Request $request, Salida $salida)
{
    $request->validate([
        'monto' => 'required|numeric|min:0',
        'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
    ]);

    // Actualizar el pago en la salida
    $salida->update([
        'total_pagado' => $request->monto,
        'metodo_pago' => $request->metodo_pago
    ]);

    return redirect()->route('salidas.index')
        ->with('success', 'Pago registrado exitosamente');
}
public function createMensual()
{
    $entradas = Entrada::whereDoesntHave('salida')
        ->whereHas('vehiculo.cliente', function($query) {
            $query->where('tipo_cliente', 'mensual');
        })
        ->with('vehiculo')
        ->get();
        
    return view('salidas.create-mensual', compact('entradas'));
}

/**
 * Limpiar registros de cliente ocasional después de salida
 */
private function limpiarClienteOcasional($cliente)
{
    try {
        // Verificar que todos los vehículos del cliente tengan salida registrada
        $todosConSalida = $cliente->vehiculos->every(function ($vehiculo) {
            return $vehiculo->entradas->every(function ($entrada) {
                return $entrada->salida !== null;
            });
        });

        if ($todosConSalida) {
            // Eliminar vehículos del cliente
            foreach ($cliente->vehiculos as $vehiculo) {
                $vehiculo->delete();
            }

            // Eliminar el cliente
            $cliente->delete();
            
            Log::info("Cliente ocasional '{$cliente->nombre}' eliminado automáticamente después de salida.");
        }
    } catch (\Exception $e) {
        Log::error('Error al limpiar cliente ocasional: ' . $e->getMessage());
    }
}
}
