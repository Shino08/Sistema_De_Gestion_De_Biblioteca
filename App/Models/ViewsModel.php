<?php
    namespace App\Models;

    # Modelo de vistas, se usa para manejar las vistas desde la URL #
    class ViewsModel {

        # Metodo para obtener las vistas desde la URL  colocando por defecto el login o el 404 si la ruta no existe en el directorio #
        
        protected function getViewModel($view){
            $routes = ["dashboard","createSystemDb","libraryReports","userManagement", "managementAuthors", "managementBooks", "manageLoans", "logOut", "userUpdate"];

            if (in_array($view, $routes)) {

                if (is_file("./App/Views/Content/".$view."-view.php")) {
                    $content = "./App/Views/Content/".$view."-view.php";
                } else {
                    $content = "404";
                }
                
            } elseif($view == "login" || $view == "index"){
                $content = "login";
            }else{
                $content = "404";
        
            }
            return $content;
        }
    }