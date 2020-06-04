<?php

namespace Ajtarragona\Censat;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\Blade;
//use Illuminate\Support\Facades\Schema;

class CensatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        

        //cargo rutas
        $this->loadRoutesFrom(__DIR__.'/routes.php');


        //publico configuracion de la api
        // $config = __DIR__.'/Config/censat-api.php';
        
        $this->publishes([
            __DIR__.'/Config/censat-api.php' => config_path('censat-api.php'),
            __DIR__.'/Config/censat-database.php' => config_path('censat-database.php'),
        ], 'ajtarragona-censat-config');

        // $this->mergeConfigFrom($config, 'censat-api');
        

        
        


       
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       	
        //defino facades
        $this->app->bind('censat', function(){
            return new \Ajtarragona\Censat\Models\CensatClient;
        });
        

        //helpers
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }


        
        if (file_exists(config_path('censat-api.php'))) {
            $this->mergeConfigFrom(config_path('censat-api.php'), 'censatclient');
        } else {
            $this->mergeConfigFrom(__DIR__.'/Config/censat-api.php', 'censatclient');
        }


        if (file_exists(config_path('censat-database.php'))) {
            $this->mergeConfigFrom(config_path('censat-database.php'), 'database.connections');
        } else {
            $this->mergeConfigFrom(__DIR__.'/Config/censat-database.php', 'database.connections');
        }
        
    }
}
