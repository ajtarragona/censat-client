<?php

namespace Ajtarragona\Censat\Controllers;
use Illuminate\Routing\Controller;

use App\Models\Censat\Map;
use Exception;

class MapController extends Controller
{
   
    public function show($name,$location){
        try{
            $map= new Map($name,$location);
            return $map->render();
        }catch(Exception $e){
            abort(404);
        }
        // return $servei->image()->download();
    }

}