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
            ->get(); // Cambia paginate(10) por get()
            
        return view('entradas.index', compact('entradas', 'buscar'));
    }


    public function create()
    {
        $vehiculos = Vehiculo::all();
        $espacios = Espacio::where('estado', 'libre')->get();
        
        return view('entradas.create', compact('vehiculos', 'espacios'));
    }
public function destroy(Entrada $entrada)
{
    // 1. Verificar si la entrada NO tiene una salida registrada (está "En parqueo")
    if (!$entrada->salida) {
        // Si NO hay salida registrada, el vehículo está actualmente en el estacionamiento.
        // Se impide la eliminación para mantener la integridad de los datos.
        return redirect()->route('entradas.index')->with('error', 'No se puede eliminar la entrada porque el vehículo aún se encuentra **En parqueo** (no tiene salida registrada).');
    }

    // 2. Si tiene una salida registrada ($entrada->salida es true), significa que la visita ha terminado.
    // En este caso, la eliminación de la entrada es potencialmente segura (aunque a menudo no es una buena práctica).

    // **IMPORTANTE**: Antes de eliminar, debes liberar el espacio de parqueo
    // que fue ocupado por esta entrada.
    if ($entrada->espacio) {
        $entrada->espacio->update(['estado' => 'libre']);
    }

    // 3. Eliminar la entrada (y la salida asociada si es una relación 'hasOne' con 'onDelete' cascade)
    $entrada->delete();

    // 4. Redireccionar con mensaje de éxito
    return redirect()->route('entradas.index')->with('success', 'Entrada y sus registros relacionados eliminados correctamente.');
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