<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;

class EspacioController extends Controller
{
   public function index(Request $request)
{
    $buscar = $request->input('buscar');

    $espacios = Espacio::with('cliente')
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
     $espacio->delete();
     return redirect()->route('espacios.index')->with('success', 'Espacio eliminado correctamente.');
 }
    public function asignar($id)
    {
        $espacio = Espacio::findOrFail($id);
        $clientes = Cliente::all();
        return view('espacios.asignar', compact('espacio', 'clientes'));
    }

    public function updateAsignacion(Request $request, Espacio $espacio)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id'  
        ]);
    
        $espacio->update([
            'cliente_id' => $request->cliente_id,
            'estado' => $request->cliente_id ? 'ocupado' : 'libre'
        ]);
    
        return redirect()->route('espacios.index')->with('success', 'Asignación actualizada');
    }
    public function liberar(Espacio $espacio)
{
    try {
        DB::beginTransaction();

        // Buscar la entrada activa
        $entrada = Entrada::where('espacio_id', $espacio->id)
            ->whereDoesntHave('salida')
            ->first();

        if ($entrada) {
            // Registrar salida con total_pagado = 0 para mensuales
            Salida::create([
                'entrada_id' => $entrada->id,
                'fecha_salida' => now(),
                'total_pagado' => 0,
                 // Agrega este campo si existe
            ]);
        }

        // Liberar el espacio
        $espacio->update([
            'cliente_id' => null,
            'estado' => 'libre'
        ]);

        DB::commit();

        return redirect()->route('espacios.index')
            ->with('success', 'Espacio liberado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error al liberar espacio: " . $e->getMessage());
        return back()->with('error', 'Error al liberar el espacio');
    }
}
    
}