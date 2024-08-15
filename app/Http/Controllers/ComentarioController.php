<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller {
    use AuthorizesRequests, ValidatesRequests;
}

class ComentarioController extends Controller
{
    public function store(Request $request,User $user, Post $post){
        //validar
        $this->validate($request, [
            'comentario' => ['required','max:255'],
        ]);
        //guardar en la base
        Comentario::create([
            'user_id'=> auth()->user()->id,
            'post_id'=>$post->id,
            'comentario'=> $request->comentario
         ]);
         
         return back()->with('mensaje','Comentario ok');

    }
}
