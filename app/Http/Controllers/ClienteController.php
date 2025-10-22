<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $clientes = Cliente::when($buscar, function ($query, $buscar) {
            return $query->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('telefono', 'like', "%{$buscar}%")
                         ->orWhere('tipo_cliente', 'like', "%{$buscar}%");
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('clientes.index', compact('clientes', 'buscar'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:5|max:20',
            'telefono' => 'required|string|size:8|regex:/^[0-9]+$/|unique:clientes,telefono',
            'tipo_cliente' => 'required|in:ocasional,mensual',
        ], [
            'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 20 caracteres.',
            'telefono.size' => 'El teléfono debe tener exactamente 8 números.',
            'telefono.regex' => 'El teléfono solo debe contener números.',
            'telefono.unique' => 'Ya existe un cliente con este número de teléfono.',
        ]);

        $cliente = Cliente::create($request->all());

        // Redirigir al registro de vehículo para este cliente
        return redirect()->route('vehiculos.create', ['cliente_id' => $cliente->id])
            ->with('success', 'Cliente creado exitosamente. Ahora registra su vehículo.');
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|min:5|max:20',
            // Aquí se excluye el teléfono actual del cliente para permitir su mismo número
            'telefono' => 'required|string|size:8|regex:/^[0-9]+$/|unique:clientes,telefono,' . $cliente->id,
            'tipo_cliente' => 'required|in:ocasional,mensual',
        ], [
            'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 20 caracteres.',
            'telefono.size' => 'El teléfono debe tener exactamente 8 números.',
            'telefono.regex' => 'El teléfono solo debe contener números.',
            'telefono.unique' => 'Ya existe un cliente con este número de teléfono.',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}
