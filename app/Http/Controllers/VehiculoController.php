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
        ->paginate(10)
        ->withQueryString();

        return view('vehiculos.index', compact('vehiculos', 'buscar'));
    }


    public function create()
    {
        $clientes = Cliente::all();
        return view('vehiculos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:10|unique:vehiculos',
            'modelo' => 'required|string|max:50',
            'color' => 'required|string|max:30',
            'cliente_id' => 'required|exists:clientes,id'
        ]);

        Vehiculo::create($request->all());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo registrado exitosamente');
    }

    public function edit(Vehiculo $vehiculo)
    {
        $clientes = Cliente::all();
        return view('vehiculos.edit', compact('vehiculo', 'clientes'));
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $request->validate([
            'placa' => 'required|string|max:10|unique:vehiculos,placa,'.$vehiculo->id,
            'modelo' => 'required|string|max:50',
            'color' => 'required|string|max:30',
            'cliente_id' => 'required|exists:clientes,id'
            
        ]);

        $vehiculo->update($request->all());

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente');
    }
}
