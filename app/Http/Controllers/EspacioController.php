<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EspacioController extends Controller
{
   public function index(Request $request)
{
    $buscar = $request->input('buscar');

    $espacios = Espacio::with(['cliente', 'entradas.salida'])
        ->when($buscar, function($query, $buscar) {
            $query->where('numero_espacio', 'like', "%{$buscar}%")
                  ->orWhere('estado', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', function ($q) use ($buscar) {
                      $q->where('nombre', 'like', "%{$buscar}%");
                  });
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10)
        ->withQueryString();

    return view('espacios.index', compact('espacios', 'buscar'));
}

   
 // Formulario de creación
 public function create()
 {
     return view('espacios.create');
 }

 // Guardar nuevo espacio
 public function store(Request $request)
 {
     $request->validate([
         'numero_espacio' => 'required|unique:espacios,numero_espacio|max:10',
         'estado' => 'required|in:libre,ocupado,mantenimiento',

     ], [
         'numero_espacio.unique' => 'Ya existe un espacio con ese número. Por favor, elige otro.',
         'numero_espacio.required' => 'El número de espacio es obligatorio.',
         
     ]
    );

     Espacio::create($request->only('numero_espacio', 'estado'));

     return redirect()->route('espacios.index')->with('success', 'Espacio creado correctamente.');
 }

 // Formulario de edición
 public function edit(Espacio $espacio)
{
    // Evitar edición si está ocupado
    if ($espacio->estado === 'ocupado') {
        return redirect()->route('espacios.index')
            ->with('error', 'No puedes editar un espacio que está ocupado. Libéralo primero.');
    }

    return view('espacios.edit', compact('espacio'));
}

 // Actualizar espacio
 public function update(Request $request, Espacio $espacio)
 {
     $request->validate([
         'numero_espacio' => 'required|max:10|unique:espacios,numero_espacio,' . $espacio->id,
         'estado' => 'required|in:libre,ocupado,mantenimiento',
     ]);

     $espacio->update($request->only('numero_espacio', 'estado'));

     return redirect()->route('espacios.index')->with('success', 'Espacio actualizado correctamente.');
 }

 // Eliminar espacio
 public function destroy(Espacio $espacio)
{
    // Verificar si el espacio está ocupado
    if ($espacio->estado === 'ocupado') {
        return redirect()->route('espacios.index')
            ->with('error', 'No puedes eliminar un espacio que está ocupado. Libéralo antes de eliminarlo.');
    }

    // Verificar si hay entradas activas relacionadas
    $entradaActiva = Entrada::where('espacio_id', $espacio->id)
        ->whereDoesntHave('salida')
        ->first();

    if ($entradaActiva) {
        return redirect()->route('espacios.index')
            ->with('error', 'No puedes eliminar este espacio mientras tenga una entrada activa.');
    }

    $espacio->delete();

    return redirect()->route('espacios.index')
        ->with('success', 'Espacio eliminado correctamente.');
}

 public function asignar($id)
 {
     $espacio = Espacio::findOrFail($id);
 
     //  Solo traer clientes SIN espacio ocupado actualmente
     $clientes = Cliente::whereDoesntHave('vehiculos.entradas', function ($query) {
             $query->whereDoesntHave('salida'); // tiene entrada activa (aún no salió)
         })
         ->orderBy('id', 'desc')
         ->get();
 
     //  Si no hay clientes disponibles
     if ($clientes->isEmpty()) {
         return redirect()->route('espacios.index')
             ->with('error', 'No hay clientes disponibles para asignar (todos tienen espacios ocupados).');
     }
 
     return view('espacios.asignar', compact('espacio', 'clientes'));
 }
 
    public function updateAsignacion(Request $request, Espacio $espacio)
{
    $request->validate([
        'cliente_id' => 'nullable|exists:clientes,id'
    ]);

    DB::beginTransaction();
    try {
        //  DESASIGNAR CLIENTE
        if (empty($request->cliente_id)) {
            $entradaActiva = Entrada::where('espacio_id', $espacio->id)
                ->whereDoesntHave('salida')
                ->first();

            if ($entradaActiva) {
                DB::rollBack();
                return back()->with('error', 'No puedes desasignar mientras exista una entrada activa. Libera el espacio primero.');
            }

            $espacio->update([
                'cliente_id' => null,
                'estado' => 'libre'
            ]);

            DB::commit();
            return redirect()->route('espacios.index')->with('success', 'Espacio desasignado y marcado como libre.');
        }

        //  VALIDACIÓN NUEVA: verificar si el cliente ya tiene un espacio ocupado
        $espacioOcupado = Espacio::where('cliente_id', $request->cliente_id)
            ->where('estado', 'ocupado')
            ->where('id', '!=', $espacio->id)
            ->first();

        if ($espacioOcupado) {
            DB::rollBack();
            return back()->with('error', 'Este cliente ya tiene un espacio asignado. Libera el anterior antes de asignar otro.');
        }

        //  Validar que el cliente tenga vehículo
        $vehiculo = \App\Models\Vehiculo::where('cliente_id', $request->cliente_id)->first();
        if (!$vehiculo) {
            DB::rollBack();
            return back()->with('error', 'El cliente seleccionado no tiene vehículo registrado. Registre un vehículo antes de asignar.');
        }

        //  Actualizar asignación
        $espacio->update([
            'cliente_id' => $request->cliente_id,
            'estado' => 'ocupado'
        ]);

        //  Crear entrada si no existe
        $entradaExistente = Entrada::where('espacio_id', $espacio->id)
            ->whereDoesntHave('salida')
            ->first();

        if (!$entradaExistente) {
            Entrada::create([
                'vehiculo_id' => $vehiculo->id,
                'espacio_id' => $espacio->id,
                'fecha_entrada' => now(),
            ]);
        }

        DB::commit();
        return redirect()->route('espacios.index')->with('success', 'Asignación actualizada correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar asignación de espacio: ' . $e->getMessage());
        return back()->with('error', 'No se pudo actualizar la asignación.');
    }
}
    
    public function liberar(Espacio $espacio)
{
    try {
        DB::beginTransaction();

        // Buscar la entrada activa
            $entrada = Entrada::where('espacio_id', $espacio->id)
                ->whereDoesntHave('salida')
                ->first();

            if (!$entrada) {
                DB::rollBack();
                return back()->with('error', 'No hay entrada activa para este espacio.');
            }

            // Registrar salida (liberación estándar)
            Salida::create([
                'entrada_id' => $entrada->id,
                'fecha_salida' => now(),
                'total_pagado' => 0,
            ]);

        // Liberar el espacio
        $espacio->update([
            'cliente_id' => null,
            'estado' => 'libre'
        ]);

        DB::commit();

            return redirect()->route('salidas.index')
                ->with('success', 'Salida registrada y espacio liberado');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error al liberar espacio: " . $e->getMessage());
        return back()->with('error', 'Error al liberar el espacio');
    }
}

    public function liberarGratis(Espacio $espacio)
    {
        try {
            DB::beginTransaction();

            $entrada = Entrada::where('espacio_id', $espacio->id)
                ->whereDoesntHave('salida')
                ->with('vehiculo.cliente')
                ->first();

            if (!$entrada) {
                DB::rollBack();
                return back()->with('error', 'No hay entrada activa para este espacio.');
            }

            // Solo aplicar para clientes ocasionales
            if (!$entrada->vehiculo || !$entrada->vehiculo->cliente || $entrada->vehiculo->cliente->tipo_cliente !== 'ocasional') {
                DB::rollBack();
                return back()->with('error', 'La opción Gratis solo aplica para clientes ocasionales.');
            }

            Salida::create([
                'entrada_id' => $entrada->id,
                'fecha_salida' => now(),
                'total_pagado' => 0,
                'es_gratis' => true,
            ]);

            $espacio->update([
                'cliente_id' => null,
                'estado' => 'libre'
            ]);

            DB::commit();

            return redirect()->route('salidas.index')
                ->with('success', 'Salida Gratis registrada (ticket ≥100 Bs) y espacio liberado.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al liberar gratis el espacio: ' . $e->getMessage());
            return back()->with('error', 'Error al liberar gratis el espacio');
        }
    }
    
}