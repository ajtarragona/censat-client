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


        //publico configuracion
        $config = __DIR__.'/Config/censatclient.php';
        
        $this->publishes([
            $config => config_path('censatclient.php'),
        ], 'ajtarragona-censat-config');


        $this->mergeConfigFrom($config, 'censatclient');


        

       
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
    }
}
