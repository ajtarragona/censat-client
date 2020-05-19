<?php

namespace Ajtarragona\Censat\Controllers;
use Illuminate\Routing\Controller;

use App\Models\Censat\Image;
use Exception;

class ImageController extends Controller
{
   
    public function show($id){
        try{
            $img= new Image($id);
            return $img->download();
        }catch(Exception $e){
            abort(404);
        }
        // return $servei->image()->download();
    }

}