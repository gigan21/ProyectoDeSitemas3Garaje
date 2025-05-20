<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Vehiculo;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
        public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $entradasQuery = Entrada::with(['vehiculo', 'espacio', 'salida']);

        if ($buscar) {
            // Ahora busca solo por el número de espacio asociado a la entrada
            $entradasQuery->whereHas('espacio', function ($q) use ($buscar) {
                $q->where('numero_espacio', 'like', "%{$buscar}%");
            });
        }

        $entradas = $entradasQuery->orderBy('fecha_entrada', 'desc')
                               ->paginate(10)
                               ->withQueryString();
            
        return view('entradas.index', compact('entradas', 'buscar'));
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
    public function getFechaEntradaFormateadaAttribute()
{
    return $this->fecha_entrada instanceof \Carbon\Carbon 
        ? $this->fecha_entrada->format('d/m/Y H:i')
        : \Carbon\Carbon::parse($this->fecha_entrada)->format('d/m/Y H:i');
}
}