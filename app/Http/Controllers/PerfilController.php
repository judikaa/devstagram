<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//abstract class Controller {
//    use AuthorizesRequests, ValidatesRequests;
//}

class PerfilController extends Controller
{
    //

    public function index(){
        return view('perfil.index');       

    }
    public function store(Request $request){
        $request->request->add(['username'=> Str::slug( $request->username)]);
        $request->validate([
            'username' => ['required','unique:users,username,'.auth()->user()->id,'min:3','max:20','not_in:twitter,devstagram,editar-perfil,posts'],
            'email' => ['required','unique:users,email,'.auth()->user()->id,'email','max:60'],
            'password' => ['confirmed']
        ]);   


        if($request->imagen) {
            $manager=new ImageManager(new Driver());            
            $imagen = $request->file('imagen');

            $nombreImagen=Str::uuid(). "." . $imagen->extension();
            
            $imagenServidor = $manager->read($imagen);
            $imagenServidor->cover(1000,1000);
            
            $imagenesPath=public_path('perfiles'). '/' . $nombreImagen;
            $imagenServidor->save($imagenesPath);

        }
        $usuario=User::find(auth()->user()->id);
        $usuario->username=$request->username;
        $usuario->email=$request->email;
        $usuario->imagen=$nombreImagen ?? auth()->user()->imagen ?? '';

        if ($request->password) {
            $request->validate([
                'oldPassword' => ['required','min:6'],
                'password' => ['confirmed','min:6'],
            ]);    

            if(Hash::check($request->oldPassword, auth()->user()->password)) {
                $usuario->password= Hash::make($request->password);
            } else {
                return back()->with('mensaje','Credenciales incorrectas');
            }
        }   

        $usuario->save();
        return redirect()->route('posts.index',$usuario->username);

    }
}
