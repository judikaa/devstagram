<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $manager=new ImageManager(new Driver());
        $imagen = $request->file('file');
        // Generar id unico para las imagenes
        $nombreImagen=Str::uuid(). "." . $imagen->extension();

        // Guardar la imagen en el servidor
        $imagenServidor = $manager->read($imagen);

        // Agregar efecto a la imagen con intervetion (cuadrado)
        $imagenServidor->cover(1000,1000);
        // la unidad mide en PX, 1=1 pixel

        // Agregar la imagen a la carpeta public donde se guardan las imagenes
        $imagenesPath=public_path('uploads'). '/' . $nombreImagen;
        // una vez procesada la imagen entonces guardamos la imagen en la carpeta que creamos

        $imagenServidor->save($imagenesPath);

        // retornamos el id unico de la imagen

        return response()->json(['imagen'=>$nombreImagen]);

    }

}
