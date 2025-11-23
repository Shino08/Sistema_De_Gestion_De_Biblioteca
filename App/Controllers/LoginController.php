<?php

namespace App\Controllers;
use App\Models\MainModel;

class LoginController extends MainModel {

    #Controlador iniciar sesion#
    public function StartSessionController() {
     
        #Almacenar datos del formulario#

        $loginInput = $this->CleanData($_POST['email']); // Puede ser email o username
        $password = $this->CleanData($_POST['password']);

        #Verifiacando campos obligatorios#

        if ($loginInput == '' || $password == '') {
            echo "<p style='color:red;'>Error: Faltan datos por ingresar</p>";
            return;
        }

        # Verificando usuario (por email o username) #

        $checkUser = $this->ExecuteQuery("SELECT * FROM users WHERE email='$loginInput' OR username='$loginInput'");

        if ($checkUser->rowCount() == 1) {

            $user = $checkUser->fetch();

            // Verificar contraseña
            if (password_verify($password, $user['password'])) {

                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: ".APP_URL."dashboard/");
                exit();
                
            } else {
                echo "<p style='color:red;'>Error: Usuario o contraseña incorrectos</p>";
            }
            
        } else {
            echo "<p style='color:red;'>Error: Usuario o contraseña incorrectos</p>";
        }

    }

    #Controlador cerrar sesion#
    public function CloseSessionController() {
        session_start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        
        header("Location: ".APP_URL."login/");
        exit();
    }

}