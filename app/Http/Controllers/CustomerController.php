<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as Validator;
// use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(){
        $customers = Customer::all();

        if (!$customers) {
            return response()->json([
                'success' => false,
                'message' => 'Clientes no encontrados'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $customers,
        ], 200);


    }
    
    public function show($id){
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer,
        ], 200);
    }

    public function ordersIndex($id){
        $orders = Order::where('customer_id', $id)->get();

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }
        if (!$orders) {
            return response()->json([
                'success' => false,
                'message' => 'Ordenes de venta no encontradas'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [$customer, $orders],
        ], 200);
    }
    /**
    public function orderShow($order_id, $customer_id){
        $order = Order::with('customer')->with('orderDetails')->find($order_id);
        
        // if($order->customer_id != $customer_id){
            //     return redirect('/');
            // }
        
        return response()->json([
            'success' => true,
            'data' => $order,
        ], 200);
    }
    
    public function search(Request $request){
        
        $search = $request->get('search');
        
        $customers = Customer::where('first_name', 'like', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')->get();
        
        return response()->json([
            'success' => true,
            'data' => $customers,
        ], 200);

    }
    
    public function store(Request $request){
        
        $Customer = new Customer();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:Customers,email|max:255',
            'password' => 'required|string|min:8|confirmed', 
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'],
            [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.string' => 'El nombre debe ser una cadena de texto.',
            'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
        
            'last_name.required' => 'Los apellidos son obligatorios.',
            'last_name.string' => 'Los apellidos deben ser una cadena de texto.',
            'last_name.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
    
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        
            'photo.required' => 'La imagen es obligatoria.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'photo.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
            ]
        );
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $Customer->first_name = $request->first_name;
        $Customer->last_name = $request->last_name;
        $Customer->email = $request->email;
        $Customer->password =Hash::make($request->password);
        $Customer->status = "Activo";
        
        $Customer->save();
        

        if ($request->hasFile('photo')){
            
            $photo = $request->photo;
            $new_photo = 'Customer_'.$Customer->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/clientes', $new_photo, 'public');
            $route = 'storage/'.$route;
            $Customer->profile_image=asset($route);
            $route = 'http://192.168.99.82/TiendaHollala/storage/app/public/imagenes/clientes/employee_1_'.$Employee->id.'.'.$photo->extension();
            $Customer->profile_image = $route;
            
            $Customer->save();
        }

        
    
        Auth::guard('customer')->login($Customer);
        
        $request->session()->regenerate();
    
        return redirect()->intended('/');
    }
    
    public function update(Request $request){
        $Customer = Customer::find(Auth('customer')->user()->id);

        if($request->email == $Customer->email){
            $validator = FacadesValidator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',],
                [
                'first_name.required' => 'El nombre es obligatorio.',
                'first_name.string' => 'El nombre debe ser una cadena de texto.',
                'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
            
                'last_name.string' => 'Los apellidos deben ser una cadena de texto.',
                'last_name.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
            
                'photo.image' => 'El archivo debe ser una imagen.',
                'photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
                'photo.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
                ]
            );
        }else{
            
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:Customers,email|max:255',
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',],
                [
                'first_name.required' => 'El nombre es obligatorio.',
                'first_name.string' => 'El nombre debe ser una cadena de texto.',
                'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
            
                'last_name.required' => 'Los apellidos son obligatorios.',
                'last_name.string' => 'Los apellidos deben ser una cadena de texto.',
                'last_name.max' => 'Los apellidos no pueden tener más de 255 caracteres.',
            
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El formato del correo electrónico es inválido.',
                'email.unique' => 'El correo electrónico ya está en uso.',
                'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            
                'photo.image' => 'El archivo debe ser una imagen.',
                'photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
                'photo.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
                ]
            );
        }
        
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $Customer->first_name = $request->first_name;
        $Customer->last_name = $request->last_name;
        $Customer->email = $request->email;
        
        if ($request->hasFile('photo')){
            
            $photo = $request->photo;
            $new_photo = 'Customer_'.$Customer->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/clientes', $new_photo, 'public');
            $route = 'storage/'.$route;
            $Customer->profile_image=asset($route);
            
            $Customer->save();
        }
        
        $Customer->save();
        
        return redirect("/cliente/perfil");
    }
     */
}
