<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller {
    use AuthorizesRequests, ValidatesRequests;
}
class LikeController extends Controller
{
     public function store(Request $request, User $user, Post $post)
    {
        //guardar en la base
        $post->likes()->create([
            'user_id' => $request->user()->id
        ]);
 
        return back();
    }

    public function destroy(Request $request, Post $post)
    {
        $request->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}
