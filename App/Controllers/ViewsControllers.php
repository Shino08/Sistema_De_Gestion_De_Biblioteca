<?php

namespace App\Controllers;
use App\Models\ViewsModel;

class ViewsControllers extends ViewsModel{
   
    public function GetViewController($view){

        if ($view != '') {
            $content = $this->getViewModel($view);
        } else {
            $content = "login";
        }

        return $content;
        
    }
}