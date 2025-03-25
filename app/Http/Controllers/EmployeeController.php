<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    public function index(){
        $Employees = Employee::all();

        if (!$Employees) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleados no encontrados'
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $Employees,
        ]);
        
    }
    
    public function show($id){
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }        
        
        return response()->json([
            "success" => true,
            "data" => $Employee,
        ]);
        
    }

    public function store(Request $request){
        
        $Employee = new Employee();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:hombre,mujer,otro',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 
            'notes' => 'nullable|string',
            'employee_type' => 'required',],
            [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.string' => 'El nombre debe ser una cadena de texto.',
            'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
        
            'last_name.required' => 'Los apellidos son obligatorios.',
            'last_name.string' => 'Los apellidos deben ser una cadena de texto.',
            'last_name.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
        
            'gender.required' => 'El género es obligatorio.',
            'gender.in' => 'El género debe ser uno de los siguientes: hombre, mujer, otro.',
        
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.date' => 'La fecha de nacimiento no es válida.',
        
            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no puede tener más de 255 caracteres.',
        
            'postal_code.required' => 'El código postal es obligatorio.',
            'postal_code.numeric' => 'El código postal debe ser numérico.',
        
            'photo.required' => 'La imagen es obligatoria.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'photo.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
        
            'notes.string' => 'Las notas deben ser una cadena de texto.',
        
            'employee_type.required' => 'El tipo de empleado es obligatorio.',
            ]
        );
        
        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }            
        }

        $Employee->first_name = $request->first_name;
        $Employee->last_name = $request->last_name;
        $Employee->birth_date = $request->birth_date;
        $Employee->email = $request->email;
        $Employee->password =Hash::make($request->password);
        $Employee->gender = $request->gender;
        $Employee->notes = $request->notes;
        $Employee->address = $request->address;
        $Employee->postal_code = $request->postal_code;
        $Employee->employee_type = $request->employee_type;
        $Employee->status = "Activo";
        
        $Employee->save();
        
        if ($request->hasFile('photo')){
            
            $photo = $request->photo;
            $new_photo = 'employee_1_'.$Employee->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/empleados', $new_photo, 'public');
            $route = 'storage/'.$route;
            $Employee->profile_image=asset($route);
            $route = env('LOCALHOST_URI').'TiendaHollala/storage/app/public/imagenes/empleados/employee_1_'.$Employee->id.'.'.$photo->extension();
            $Employee->profile_image = $route;
            
            $Employee->save();
        }
        

        return response()->json([
            "success" => true,
            "data" => $Employee,
        ], 200);
    }
    
    public function update(Request $request, $id){
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }  

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => 'required|in:hombre,mujer,otro',
                'password' => 'string|min:8|confirmed', 
                'birth_date' => 'required|date',
                'address' => 'required|string|max:255',
                'postal_code' => 'required|numeric',
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
                'notes' => 'nullable|string',
                'employee_type' => 'required',],
                [
                'first_name.required' => 'El nombre es obligatorio.',
                'first_name.string' => 'El nombre debe ser una cadena de texto.',
                'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
            
                'last_name.required' => 'Los apellidos son obligatorios.',
                'last_name.string' => 'Los apellidos deben ser una cadena de texto.',
                'last_name.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
            
                'gender.required' => 'El género es obligatorio.',
                'gender.in' => 'El género debe ser uno de los siguientes: hombre, mujer, otro.',
            
                'password.string' => 'La contraseña debe ser una cadena de texto.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'birth_date.date' => 'La fecha de nacimiento no es válida.',
            
                'address.required' => 'La dirección es obligatoria.',
                'address.string' => 'La dirección debe ser una cadena de texto.',
                'address.max' => 'La dirección no puede tener más de 255 caracteres.',
            
                'postal_code.required' => 'El código postal es obligatorio.',
                'postal_code.numeric' => 'El código postal debe ser numérico.',
            
                'photo.image' => 'El archivo debe ser una imagen.',
                'photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
                'photo.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
            
                'notes.string' => 'Las notas deben ser una cadena de texto.',
            
                'employee_type.required' => 'El tipo de empleado es obligatorio.',
                ]
            );
              
          
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errors" => $validator->error(),
            ], 422);
        }
        
        $Employee->first_name = $request->first_name;
        $Employee->last_name = $request->last_name;
        $Employee->birth_date = $request->birth_date;
        $Employee->email = $request->email;
        $Employee->gender = $request->gender;
        $Employee->notes = $request->notes;
        $Employee->address = $request->address;
        $Employee->postal_code = $request->postal_code;
        $Employee->employee_type = $request->employee_type;        

        if ($request->hasFile('photo')){
            
            $photo = $request->photo;
            $new_photo = 'employee_1_'.$Employee->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/empleados', $new_photo, 'public');
            $route = 'storage/'.$route;
            $Employee->profile_image=asset($route);
            $route = env('LOCALHOST_URI').'TiendaHollala/storage/app/public/imagenes/empleados/employee_1_'.$Employee->id.'.'.$photo->extension();
            $Employee->profile_image = $route;
            
            $Employee->save();
        }
        
        $Employee->save();
        
        return response()->json([
            "success" => true,
            "data" => $Employee,
        ], 200);
    }
    
    public function destroy($id){
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }    

        $Employee->status = "Inactivo";
        
        $Employee->save();
        
        return response()->json([
            'success' => true,
            'message' => "Empleado dado de baja correctamente",
        ], 200);
        
    }

    public function search(Request $request){

        $search = $request->get('search');

        $users = Employee::where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')->get();

        if (!$users) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }        

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);

    }

    public function enableEmployee($id){
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }        

        $Employee->status = "Activo";
        $Employee->save();

        return response()->json([
            'success' => true,
            'message' => "Empleado dado de alta correctamente",
        ], 200);
            
    }

    public function updatePasword($id, Request $request){
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }  

        $validator = Validator::make($request->all(), [
            'password' => 'string|min:8|confirmed',
            ],
            [
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }    

        $Employee->password = Hash::make($request->password);
        $Employee->save();
        
        return response()->json([
            "success" => true,
            "message" => "Contraseña actualizada correctamente"
        ], 200);

    }

    public function updateEmail(Request $request, $id){
        $Employee = Employee::find($id);

        if (!$Employee) {
            return response()->json([
                'success' => false,
                'errors' => 'Empleado no encontrado'
            ], 404);
        }  

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:employees,email|max:255',],
            [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            ]
        );
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }    
        
        $Employee->email = $request->email;
        $Employee->save();

        return response()->json([
            "success" => true,
            "message" => "Email actualizado correctamente"
        ], 200);
    
    }
    
}
