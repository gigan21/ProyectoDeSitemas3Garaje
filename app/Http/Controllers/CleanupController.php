<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CleanupController extends Controller
{
    /**
     * Mostrar el panel de limpieza
     */
    public function index()
    {
        return view('cleanup.index');
    }

    /**
     * Ejecutar limpieza de clientes ocasionales
     */
    public function cleanupOcasionalClients(Request $request)
    {
        try {
            $exitCode = Artisan::call('cleanup:ocasional-clients', [
                '--force' => true
            ]);

            if ($exitCode === 0) {
                return redirect()->back()->with('success', 'Limpieza de clientes ocasionales completada exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al ejecutar la limpieza de clientes ocasionales.');
            }
        } catch (\Exception $e) {
            Log::error('Error en limpieza de clientes ocasionales: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al ejecutar la limpieza: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar limpieza del historial
     */
    public function cleanupHistory(Request $request)
    {
        try {
            $exitCode = Artisan::call('cleanup:history', [
                '--force' => true
            ]);

            if ($exitCode === 0) {
                return redirect()->back()->with('success', 'Limpieza del historial completada exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al ejecutar la limpieza del historial.');
            }
        } catch (\Exception $e) {
            Log::error('Error en limpieza del historial: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al ejecutar la limpieza: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar limpieza de salidas de clientes ocasionales
     */
    public function cleanupOcasionalSalidas(Request $request)
    {
        try {
            $exitCode = Artisan::call('cleanup:ocasional-salidas', [
                '--force' => true
            ]);

            if ($exitCode === 0) {
                return redirect()->back()->with('success', 'Limpieza de salidas de clientes ocasionales completada exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al ejecutar la limpieza de salidas de clientes ocasionales.');
            }
        } catch (\Exception $e) {
            Log::error('Error en limpieza de salidas de clientes ocasionales: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al ejecutar la limpieza: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar limpieza completa del sistema
     */
    public function cleanupSystem(Request $request)
    {
        try {
            $exitCode = Artisan::call('cleanup:system', [
                '--force' => true
            ]);

            if ($exitCode === 0) {
                return redirect()->back()->with('success', 'Limpieza completa del sistema ejecutada exitosamente.');
            } else {
                return redirect()->back()->with('error', 'Error al ejecutar la limpieza completa del sistema.');
            }
        } catch (\Exception $e) {
            Log::error('Error en limpieza completa del sistema: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al ejecutar la limpieza: ' . $e->getMessage());
        }
    }
}
