<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $usuario = Auth::user();

            if ($usuario->rol === 'paciente') {
                $citas = $usuario->paciente->citas;
            } else {
                $citas = $usuario->doctor->citas;
            }

            return response()->json($citas, 200);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No se implementa en este ejemplo
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'fecha_cita' => 'required|date',
                'descripcion' => 'nullable|string',
            ]);

            $cita = Cita::create([
                'paciente_id' => Auth::user()->paciente->id,
                'doctor_id' => $validated['doctor_id'],
                'fecha_cita' => $validated['fecha_cita'],
                'descripcion' => $validated['descripcion'],
                'estado' => true,
            ]);

            return response()->json($cita, 201);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
    {
        try {
            return response()->json($cita, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'mensaje' => 'Cita no encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cita $cita)
    {
        // No se implementa en este ejemplo
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita)
    {
        // No se implementa en este ejemplo
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cita = Cita::findOrFail($id);

            if (Auth::user()->paciente->id !== $cita->paciente_id) {
                return response()->json(['mensaje' => 'No autorizado'], 403);
            }

            $cita->delete();

            return response()->json(['mensaje' => 'Cita cancelada'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'mensaje' => 'Cita no encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
