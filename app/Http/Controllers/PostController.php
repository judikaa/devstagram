<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;



class PostController extends Controller
{
    public function index(User $user) 
    {
        // consulta la base de datos
        $posts=Post::where('user_id',$user->id)->latest()->paginate(20);
        return view('dashboard', [
            'user'=> $user, 
            'posts'=>$posts]);
    }

    public function create(){
        return view('posts.create');
    }

    public function store(Request $request)
    {

        // validacion
          $request->validate([
              'titulo' => ['required','max:50'],
              'descripcion' => ['required','max:300'],
              'imagen' => ['required'],
          ]);

        //  Post::create([
        //      'titulo'=> $request->titulo,
        //      'descripcion'=>$request->descripcion,
        //      'imagen'=> $request->imagen,
        //      'user_id'=> auth()->user()->id
        //  ]);
          
          // otra manera de crear una línea en la bd
          // $post = new Post;
          // $post->titulo-> $request->titulo;
          // $post->descripcion->$request->descripcion;
          // $post->imagen-> $request->imagen;
          // $post->user_id-> auth()->user()->id;

        // tercera forma de crear una línea en la bd a partir del usuario
          $request->user()->posts()->create([
            'titulo'=> $request->titulo,
            'descripcion'=>$request->descripcion,
            'imagen'=> $request->imagen,
            'user_id'=> auth()->user()->id
        ]);

          // redireccionar
          return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post)
    {
      return view('posts.show',[
            'post'=>$post,
            'user'=>$user
        ]);
      }
     
    public function destroy(Post $post)
    {
   //   $this->authorize('delete',$post);
      $imagen_path=public_path('uploads/' . $post->imagen);
      if (File::exists($imagen_path)) {
        unlink($imagen_path);
        
      }
      $post->delete();
      return redirect()->route('posts.index', auth()->user()->username);
    }
    
}
