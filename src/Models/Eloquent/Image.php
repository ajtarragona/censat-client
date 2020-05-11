<?php

namespace Ajtarragona\Censat\Models\Eloquent;

use Alfresco;
use Ajtarragona\Censat\Traits\Castable;
use Ajtarragona\Censat\Traits\HasAttributes;
use Ajtarragona\Censat\Traits\HasDates;

class Image {

    use Castable;
    use HasAttributes;
    use HasDates;

    public $document_id;
    public $name;
    public $extension;
    public $mimetype;
    public $size;
    public $humansize;
    public $created;
    public $updated;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->dates=['created','updated'];
        $this->attributes=["document_id","name",'extension','mimetype','size','humansize','created','updated'];
    }
    
    public function url(){
        return route('censat.image.show',['id'=>$this->document_id]);
    }

    public function download(){
        Alfresco::downloadObject($this->document_id,true);
        
    }

    public function render($args=[]){
        $class="img-fluid";

        if($args && isset($args["class"])) $class.=" ".$args["class"];

        return "<img src='".$this->url()."' class='".$class."'/>";
    }

}