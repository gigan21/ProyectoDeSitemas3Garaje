<?php

namespace App\Http\Controllers;

use App\Models\PagoMensual;
use App\Models\Cliente;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = PagoMensual::from('pagos_mensuales')
            ->with('cliente')
            ->orderBy('fecha_pago', 'desc')
            ->paginate(10);
            
        return view('pagos.index', compact('pagos'));
    }

    public function create()
    {
        $clientes = Cliente::where('tipo_cliente', 'mensual')->get();
        return view('pagos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date'
        ]);

        PagoMensual::create($request->all());

        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado exitosamente');
    }

    public function destroy(PagoMensual $pago)
    {
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado exitosamente');
    }
}