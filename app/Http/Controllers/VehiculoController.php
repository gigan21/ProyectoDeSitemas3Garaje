<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $vehiculos = Vehiculo::when($buscar, function($query, $buscar) {
            return $query->where('placa', 'like', "%{$buscar}%")
                         ->orWhere('modelo', 'like', "%{$buscar}%");
            // Eliminamos la línea que buscaba por 'marca'
        })
        ->orderBy('id', 'desc')
        ->get(); // Cambia paginate(10) por get()

        return view('vehiculos.index', compact('vehiculos', 'buscar'));
    }


 public function create()
{
    // Obtener clientes que NO tengan vehículo registrado
    $clientes = Cliente::whereDoesntHave('vehiculos')
        ->orderBy('id', 'desc')
        ->get();

    // Obtener el último cliente registrado
    $ultimoCliente = Cliente::orderBy('id', 'desc')->first();

    return view('vehiculos.create', compact('clientes', 'ultimoCliente'));
}


public function store(Request $request)
{
    $request->validate([
        'tipo_placa' => 'required|in:NUEVA,ANTIGUA', // 🔥 AGREGADO
        'placa' => [
            'required',
            'string',
            //  AGREGADO: Validación según tipo de placa
            function ($attribute, $value, $fail) use ($request) {
                $placa = strtoupper($value);
                
                if ($request->tipo_placa === 'NUEVA') {
                    if (!preg_match('/^[0-9]{4}[A-Z]{3}$/', $placa)) {
                        $fail('Para placa NUEVA use formato: 4 números + 3 letras (ej: 1234ABC)');
                    }
                } elseif ($request->tipo_placa === 'ANTIGUA') {
                    if (!preg_match('/^[0-9]{3}[A-Z]{3}$/', $placa)) {
                        $fail('Para placa ANTIGUA use formato: 3 números + 3 letras (ej: 123ABC)');
                    }
                }
            },
            'unique:vehiculos'
        ],
        'modelo_select' => 'required|string',
        'color_select' => 'required|string',
        'modelo' => 'nullable|string|regex:/^[A-ZÑÁÉÍÓÚ ]{3,10}$/',
        'color' => 'nullable|string|regex:/^[A-ZÑÁÉÍÓÚ ]{3,10}$/',
        'cliente_id' => 'required|exists:clientes,id'
    ]);

    // Resto de tu código sin cambios...
    if ($request->modelo_select === 'OTRO' && empty(trim($request->modelo))) {
        return back()->withErrors(['modelo' => 'Debe ingresar un modelo si selecciona OTRO.'])->withInput();
    }

    if ($request->color_select === 'OTRO' && empty(trim($request->color))) {
        return back()->withErrors(['color' => 'Debe ingresar un color si selecciona OTRO.'])->withInput();
    }

    $placa = strtoupper($request->placa);
    $modelo = $request->modelo_select === 'OTRO'
        ? strtoupper($request->modelo)
        : strtoupper($request->modelo_select);
    $color = $request->color_select === 'OTRO'
        ? strtoupper($request->color)
        : strtoupper($request->color_select);

    Vehiculo::create([
        'placa' => $placa,
        'modelo' => $modelo,
        'color' => $color,
        'cliente_id' => $request->cliente_id,
    ]);

    return redirect()->route('vehiculos.index')
        ->with('success', 'Vehículo registrado exitosamente');
}


public function edit(Vehiculo $vehiculo)
{
    // Bloquear edición si el vehículo tiene entrada activa
    if ($vehiculo->tieneEntradaActiva()) {
        return redirect()->route('vehiculos.index')
            ->with('error', 'No puedes editar este vehículo porque está actualmente asignado a un espacio (entrada activa).');
    }

    $clientes = Cliente::all();
    return view('vehiculos.edit', compact('vehiculo', 'clientes'));
}

public function update(Request $request, Vehiculo $vehiculo)
{
    if ($vehiculo->tieneEntradaActiva()) {
        return redirect()->route('vehiculos.index')
            ->with('error', 'No puedes actualizar este vehículo porque está actualmente asignado a un espacio (entrada activa).');
    }

    $request->validate([
        'tipo_placa' => 'required|in:NUEVA,ANTIGUA', // 🔥 AGREGADO: Campo tipo_placa
        'placa' => [
            'required',
            'string',
            // 🔥 ACTUALIZADO: Validación según tipo de placa
            function ($attribute, $value, $fail) use ($request) {
                $placa = strtoupper($value);
                
                if ($request->tipo_placa === 'NUEVA') {
                    if (!preg_match('/^[0-9]{4}[A-Z]{3}$/', $placa)) {
                        $fail('Para placa NUEVA use formato: 4 números + 3 letras (ej: 1234ABC)');
                    }
                } elseif ($request->tipo_placa === 'ANTIGUA') {
                    if (!preg_match('/^[0-9]{3}[A-Z]{3}$/', $placa)) {
                        $fail('Para placa ANTIGUA use formato: 3 números + 3 letras (ej: 123ABC)');
                    }
                }
            },
            'unique:vehiculos,placa,' . $vehiculo->id,
        ],
        'modelo_select' => 'required|string',
        'color_select' => 'required|string',
        'modelo' => 'nullable|string|regex:/^[A-ZÑÁÉÍÓÚ ]{3,10}$/',
        'color' => 'nullable|string|regex:/^[A-ZÑÁÉÍÓÚ ]{3,10}$/',
        'cliente_id' => 'required|exists:clientes,id'
    ]);

    if ($request->modelo_select === 'OTRO' && empty(trim($request->modelo))) {
        return back()->withErrors(['modelo' => 'Debe ingresar un modelo si selecciona OTRO.'])->withInput();
    }

    if ($request->color_select === 'OTRO' && empty(trim($request->color))) {
        return back()->withErrors(['color' => 'Debe ingresar un color si selecciona OTRO.'])->withInput();
    }

    $placa = strtoupper($request->placa);
    $modelo = $request->modelo_select === 'OTRO'
        ? strtoupper($request->modelo)
        : strtoupper($request->modelo_select);
    $color = $request->color_select === 'OTRO'
        ? strtoupper($request->color)
        : strtoupper($request->color_select);

    $vehiculo->update([
        'placa' => $placa,
        'modelo' => $modelo,
        'color' => $color,
        'cliente_id' => $request->cliente_id,
    ]);

    return redirect()->route('vehiculos.index')
        ->with('success', 'Vehículo actualizado exitosamente');
}

    public function destroy(Vehiculo $vehiculo)
    {
        // Bloquear eliminación si el vehículo tiene entrada activa
        if ($vehiculo->tieneEntradaActiva()) {
            return redirect()->route('vehiculos.index')
                ->with('error', 'No puedes eliminar este vehículo porque está actualmente asignado a un espacio (entrada activa).');
        }

        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente');
    }
}
