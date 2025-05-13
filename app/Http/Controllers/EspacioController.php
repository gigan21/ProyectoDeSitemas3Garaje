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
    public function index()
    {
        $espacios = Espacio::with('cliente')->orderBy('numero_espacio')->get();
        return view('espacios.index', compact('espacios'));
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
     ]);

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
        // Buscar la entrada activa para este espacio
        $entrada = Entrada::where('espacio_id', $espacio->id)
            ->whereDoesntHave('salida')
            ->first();
    
        if ($entrada) {
            // Registrar la salida
            Salida::create([
                'entrada_id' => $entrada->id,
                'fecha_salida' => now(),
                'total_pagado' => 0 // O el cálculo que corresponda
            ]);
        }
    
        // Actualizar el espacio
        $espacio->update([
            'cliente_id' => null,
            'estado' => 'libre'
        ]);
    
        return redirect()->route('espacios.index')
            ->with('success', 'Espacio liberado exitosamente');
    }
    
}