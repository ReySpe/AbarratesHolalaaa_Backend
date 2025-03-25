<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class EmployeeAuthController extends Controller
{
    // Login API for employees
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if employee exists
        $employee = Employee::where('email', $credentials['email'])->first();

        if (!$employee) {
            return response()->json(['error' => '¡El correo o contraseña son incorrectos!'], 401);
        }

        // Check if the employee is active
        if ($employee->status == "Inactivo") {
            return response()->json(['error' => '¡Tu cuenta ha sido desactivada!'], 403);
        }

        // Attempt to generate JWT token
        try {
            if (!$token = JWTAuth::claims(['role' => 'employee'])->fromUser($employee)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'employee' => $employee
        ]);
    }

    // Logout API
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }

    // Get Authenticated Employee Data
    public function profile()
    {
        try {
            $employee = JWTAuth::parseToken()->authenticate();
            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }
    }
}
