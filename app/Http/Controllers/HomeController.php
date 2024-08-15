<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//abstract class Controller {
//    use AuthorizesRequests, ValidatesRequests;
//}
 
class HomeController extends Controller
{
    public function __invoke()
    {
        // obtener a quienes seguimos
        $ids=( auth()->user()->following->pluck('id')->toArray());
        $posts=Post::whereIn('user_id',$ids)->latest()->paginate(20);
        
        return view('home',['posts'=>$posts]);
    }
}
