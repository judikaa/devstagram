<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//abstract class Controller {
//    use AuthorizesRequests, ValidatesRequests;
//}

class LoginController extends Controller
{
    //
    public function index() 
    {
        return view('auth.login');
    }
    public function store(Request $request) 
    {
        $request->validate([
            'email' =>'required|email',
            'password' => 'required'
        ]);
    
        if(!auth()->attempt($request->only('email','password'), $request->remember)) {
            return back()->with('mensaje','Credenciales incorrectas');
        }
        return redirect()->route('posts.index',auth()->user()->username);
    }
}
