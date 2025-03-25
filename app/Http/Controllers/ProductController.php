<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        // $categories = Category::where('status', 'Activo')->get(); 

        return response()->json([
            "success" => true,
            "data" => $products,
        ], 200);
    }
    
    public function show($id){
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "success" => false,
                "error" => "Producto no encontrado"
            ], 422);
        } 

        return response()->json([
            "success" => true,
            "data" => $product,
        ], 200);
    }
    
    public function destroy($id){
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "success" => false,
                "error" => "Producto no encontrado"
            ], 422);
        }
        
        $product->status = "Inactivo";

        $product->save();

        return response()->json([
            "success" => true,
            "message" => "Producto dado de baja exitosamente",
        ], 200);
    }

    public function enableProduct($id){
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "success" => false,
                "error" => "Producto no encontrado"
            ], 422);
        }
        
        $product->status = "Activo";
        $product->save();

        return response()->json([
            "success" => true,
            "message" => "¡Producto dado de alta exitosamente!",
        ], 200);
        
    }

    public function store(Request $request){
        $product = new Product();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit_price' => 'required|numeric|min:0',
            'unit_stock' => 'required|integer|min:0',
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.', 

            'image.required' => 'La imagen es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',

            'unit_price.required' => 'El precio es obligatorio.',
            'unit_price.numeric' => 'El precio debe ser numerico.',
            'unit_price.min' => 'El precio debe ser mayor de cero.',

            'unit_stock.required' => 'El stock es obligatorio.',
            'unit_stock.integer' => 'El stock debe ser un entero.',
            'unit_stock.min' => 'El stock debe ser mayor de cero.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $product->name = $request->name;
        $product->unit_stock = $request->unit_stock;
        $product->unit_price = $request->unit_price;
        $product->category_id = $request->category_id;
        $product->status = "Activo";
        $product->save();
        
        if ($request->hasFile('image')){
            
            $photo = $request->image;
            $new_photo = 'product_1_'.$product->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/productos', $new_photo, 'public');
            $route = 'storage/'.$route;
            $product->product_image=asset($route);
            $route = env('LOCALHOST_URI').'/TiendaHollala/storage/app/public/imagenes/productos/product_1_'.$product->id.'.'.$photo->extension();
            $product->product_image = $route;
            
            $product->save();
        }
        
        session()->flash('alert', '¡Registro exitoso!');

        return redirect("/producto/lista");
        
    }
    
    public function update(Request $request, $id){-+
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "success" => false,
                "error" => "Producto no encontrado"
            ], 422);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit_price' => 'numeric|min:0',
            'unit_stock' => 'integer|min:0',
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.', 
            
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
            
            'unit_price.required' => 'El precio es obligatorio.',
            'unit_price.numeric' => 'El precio debe ser numerico.',
            'unit_price.min' => 'El precio debe ser mayor de cero.',
            
            'unit_stock.required' => 'El stock es obligatorio.',
            'unit_stock.integer' => 'El stock debe ser un entero.',
            'unit_stock.min' => 'El stock debe ser mayor de cero.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "error" => $validator->errors()
            ], 422);           
        }
        
        $product->name = $request->name;
        $product->unit_stock = $request->unit_stock;
        $product->unit_price = $request->unit_price;
        $product->category_id = $request->category_id;
        
        if($product->status = "Inactivo"){
            $product->status = "Activo";
        }

        $product->save();

        if ($request->hasFile('image')){
            
            $photo = $request->image;
            $new_photo = 'product_1_'.$product->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/productos', $new_photo, 'public');
            $route = 'storage/'.$route;
            $product->product_image=asset($route);
            $route = env('LOCALHOST_URI').'/TiendaHollala/storage/app/public/imagenes/productos/product_1_'.$product->id.'.'.$photo->extension();
            $product->product_image = $route;
            
            $product->save();
        }

        session()->flash('alert', '¡Registro exitoso!');

        return redirect("/producto/lista");
    }

    public function indexByCategory($id){
        $products = Product::where('category_id', $id)->get();
        $categories = Category::where('status', 'Activo')->get(); 

        return view('/products/list', compact('products', 'categories'));
    }
    
    public function indexOrder($order){
        $products = Product::orderBy($order, 'asc')->get();
        $categories = Category::where('status', 'Activo')->get(); 
    
        return view('/products/list', compact('products', 'categories'));
    }

    public function search(Request $request){

        $search = $request->get('search');

        $products = Product::where('name', 'like', '%' . $search . '%')
        ->orWhereHas('category', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();
        $categories = Category::where('status', 'Activo')->get(); 

        return view('/products/list', compact('products', 'categories'));

    }
}
