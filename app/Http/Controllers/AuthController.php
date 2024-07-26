<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'rol' => 'required|string|in:paciente,doctor',
                'especialidad_id' => 'required_if:rol,doctor|exists:especialidads,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errores' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $usuario = User::create([
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'rol' => $validated['rol'],
            ]);

            if ($validated['rol'] === 'paciente') {
                Paciente::create(['user_id' => $usuario->id]);
            } else {
                Doctor::create(['user_id' => $usuario->id, 'especialidad_id' => $request->especialidad_id]);
            }

            Auth::login($usuario);

            return response()->json(['mensaje' => 'Usuario registrado y ha iniciado sesión con éxito'], 200);

        } catch (ValidationException $e) {
            return response()->json(['mensaje' => 'Error de validación', 'errores' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['mensaje' => 'Las credenciales proporcionadas no coinciden con nuestros registros'], 401);
            }

            $request->session()->regenerate();

            return response()->json(['mensaje' => 'Usuario ha iniciado sesión con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['mensaje' => 'Usuario ha cerrado sesión con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => 'Error en el servidor', 'error' => $e->getMessage()], 500);
        }
    }
}
