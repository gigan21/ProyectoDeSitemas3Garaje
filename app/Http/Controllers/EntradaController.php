<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Vehiculo;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    public function index()
    {
        $entradas = Entrada::with(['vehiculo', 'espacio', 'salida'])
            ->orderBy('fecha_entrada', 'desc')
            ->paginate(10);
            
        return view('entradas.index', compact('entradas'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::all();
        $espacios = Espacio::where('estado', 'libre')->get();
        
        return view('entradas.create', compact('vehiculos', 'espacios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'espacio_id' => 'required|exists:espacios,id'
        ]);
    
        try {
            DB::transaction(function () use ($request) {
                $espacio = Espacio::findOrFail($request->espacio_id);
                
                // Validar que el espacio esté libre
                if ($espacio->estado !== 'libre') {
                    throw new \Exception('El espacio seleccionado no está disponible');
                }
            
                $espacio->update(['estado' => 'ocupado']);
            
                Entrada::create([
                    'vehiculo_id' => $request->vehiculo_id,
                    'espacio_id' => $request->espacio_id,
                    'fecha_entrada' => now()
                ]);
            });
    
            return redirect()->route('entradas.index')
                ->with('success', 'Entrada registrada exitosamente');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al registrar entrada: ' . $e->getMessage());
        }
    }
}