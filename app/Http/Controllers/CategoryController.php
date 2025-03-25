<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();

        if (!$categories) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $categories,
        ], 200);
    }

    public function store(Request $request){

        $category = new Category();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',  

            'image.required' => 'La imagen es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $category->name = $request->name;
        
        $category->save();
        
        if ($request->hasFile('image')){

            $photo = $request->image;
            $new_photo = 'category_1_'.$category->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/categorias', $new_photo, 'public');
            $route = env('LOCALHOST_URI').'storage/'.$route;
            $category->category_image=asset($route);

            $category->save();
        }

        return response()->json([
            'success' => true,
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id){

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',  

            'image.required' => 'La imagen es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        
        $category->name = $request->name;
        
        $category->save();
        if ($request->hasFile('image')){
            
            $photo = $request->image;
            $new_photo = 'category_1_'.$category->id.'.'.$photo->extension();
            $route=$photo->storeAs('imagenes/categorias', $new_photo, 'public');
            $route = 'storage/'.$route;
            $category->profile_image=asset($route);
            $route = 'http://192.168.99.82/TiendaHollala/storage/app/public/imagenes/categorias/category_1_'.$category->id.'.'.$photo->extension();
            $category->profile_image = $route;

            $category->save();
        }
        
        return response()->json([
            'success' => true,
            'data' => $category,
        ], 200);
    }

    public function show($id){
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category,
        ], 200);
    }
    
    public function destroy($id){
        
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }

        if($category->status == "Activo"){
            $category->status = "Inactivo";
        }else{
            $category->status = "Activo";
        }

        
        return response()->json([
            'success' => true,
            'message' => "Registro eliminado correctamente",
        ], 200);
    
    }

    public function search(Request $request){

        $search = $request->get('search');

        $categories = Category::where('name', 'like', '%' . $search . '%')->get(); 

        if (!$categories) {
            return response()->json([
                'success' => false,
                'message' => 'Categorias no encontradas'
            ], 404);
        }

        return view('/categories/list')->with('categories', $categories);

    }

    
}
