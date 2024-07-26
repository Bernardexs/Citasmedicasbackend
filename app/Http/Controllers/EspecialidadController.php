<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $especialidades = Especialidad::all();
            return response()->json($especialidades, 200);
        } catch (Exception $e) {
            // Registrar el error y devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($especialidadId)
    {
        try {
            $especialidad = Especialidad::with('doctores')->findOrFail($especialidadId);
            return response()->json($especialidad, 200);
        } catch (ModelNotFoundException $e) {
            // Registrar el error y devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'mensaje' => 'Especialidad no encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            // Registrar el error y devolver una respuesta JSON con el mensaje de error
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
