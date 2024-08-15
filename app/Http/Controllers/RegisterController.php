<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//abstract class Controller {
//    use AuthorizesRequests, ValidatesRequests;
//}

use Illuminate\Http\Request;
class RegisterController extends Controller
{
    //
    public function index() 
    {
        return view('auth.register');
    }
    public function store(Request $request) 
    {
      //  dd($request->get('name'));
      // modificar request
      $request->request->add(['username'=> Str::slug( $request->username)]);

      // validacion
        $request->validate([
            'name' => ['required','min:3','max:30'],
            'username' => ['required','unique:users','min:3','max:20','not_in:twitter,devstagram,editar-perfil,posts'],
            'email' => ['required','unique:users','email','max:60'],
            'password' => ['required','confirmed','min:6'],
        ]);
        User::create([
            'name'=> $request->name,
            'username'=>$request->username,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        // autenticar usuario
        // auth()->attempt([
        //     'email'=> $request->email,
        //    'password'=> $request->password
        // ]);

        //otra forma de autenticar
        auth()->attempt($request->only('email','password'));
        
        // redireccionar
        return redirect()->route('posts.index',auth()->user()->username);
 
    }
}
