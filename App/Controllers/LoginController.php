<?php

namespace App\Controllers;
use App\Models\MainModel;

class LoginController extends MainModel {

    #Controlador iniciar sesion#
    public function StartSessionController() {
     
        #Almacenar datos del formulario#

        $email = $this->CleanData($_POST['email']);
        $password = $this->CleanData($_POST['password']);

        #Verifiacando campos obligatorios#

        if ($email == '' || $password == '') {
            echo "<p style='color:red;'>Error: Faltan datos por ingresar</p>";
            return;
        }

        # Verificando integridad de los datos #

        if ($this->VerifyData("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$", $email)) {
            echo "<p style='color:red;'>Error: El email no es válido</p>";
            return;
        }

        if ($this->VerifyData("[a-zA-Z0-9$@.-]{7,100}", $password)) {
            echo "<p style='color:red;'>Error: La contraseña debe tener al menos 7 caracteres</p>";
            return;
        }
        
        # Verificando email #

        $check_email = $this->ExecuteQuery("SELECT * FROM administrators WHERE email='$email'");

        if ($check_email->rowCount() == 1) {

            $check_email = $check_email->fetch();

            if ($check_email['email'] == $email) {

                $_SESSION['id'] = $check_email['admin_id'];
                $_SESSION['email'] = $check_email['email'];                            
                
                header("Location: ".APP_URL."dashboard/");
                exit();
                
            } else {
                echo "<p style='color:red;'>Error: Email o contraseña incorrectos</p>";
            }
            
        } else {
            echo "<p style='color:red;'>Error: Email o contraseña incorrectos</p>";
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