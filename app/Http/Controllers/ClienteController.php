<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
   public function index(Request $request)
    {
        $buscar = $request->input('buscar'); // Cambiado de 'search' a 'buscar'

        $clientes = Cliente::when($buscar, function($query, $buscar) {
            return $query->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('telefono', 'like', "%{$buscar}%")
                         ->orWhere('tipo_cliente', 'like', "%{$buscar}%");
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('clientes.index', compact('clientes', 'buscar'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'tipo_cliente' => 'required|in:ocasional,mensual'
        ]);
          // Verificar si ya existe un cliente con el mismo teléfono
$existe = \App\Models\Cliente::where('telefono', $request->telefono)->exists();

if ($existe) {
    return back()->withErrors(['telefono' => 'Ya existe un cliente con este número de teléfono.'])->withInput();
}

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
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
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'tipo_cliente' => 'required|in:ocasional,mensual'
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