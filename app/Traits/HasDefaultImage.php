<?php

namespace App\Traits;

trait hasDefaultImage {

    public function getImage($altText){
        
        if(!$this->logo){

            return "https://ui-avatars.com/api/?name=$altText size=255";

        }
            return "/personal/public/uploads/$this->logo";
        
        //var_dump($this->logo);
    }
}