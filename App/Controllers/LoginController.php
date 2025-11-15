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
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Faltan datos por ingresar',
                    text: 'No se han proporcionado todos los datos',
                    confirmButtonText: 'Aceptar'
                });
            </script>
            ";
        }else{
            # Verificando integridad de los datos #

            if ($this->VerifyData("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$", $email)) {
                echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'email no válido',
                        text: 'El email debe tener al menos 4 caracteres',
                        confirmButtonText: 'Aceptar'
                    });
                </script>
                ";
            } else {
                if ($this->VerifyData("[a-zA-Z0-9$@.-]{7,100}", $password)) {
                    echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'contraseña no válida',
                            text: 'La contraseña debe tener al menos 7 caracteres',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                    ";
                } else {
                    
                    # Verificando email #

                    $check_email = $this->ExecuteQuery("SELECT * FROM administrators WHERE email='$email'");

                    if ($check_email->rowCount() == 1) {

                        $check_email = $check_email->fetch();

                        if ($check_email['email'] == $email) {

                            $_SESSION['id'] = $check_email['admin_id'];
                            $_SESSION['email'] = $check_email['email'];                            
                            
                            if (headers_sent()) {
                                echo "
                                <script>
                                    window.location.href = '".APP_URL."dashboard/';
                                </script>
                                ";  
                            } else {
                                header("Location: ".APP_URL."dashboard/");
                            }
                            
                        } else {
                            echo "
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'email no válido',
                                    text: 'email o contraseña incorrectos',
                                    confirmButtonText: 'Aceptar'
                                });
                            </script>
                        ";
                        }
                        
                        
                    } else {
                        echo "
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'email no válido',
                                    text: 'email o contraseña incorrectos',
                                    confirmButtonText: 'Aceptar'
                                });
                            </script>
                        ";
                    }
                    
                }
            }
            
        }

    }

    #Controlador cerrar sesion#
public function CloseSessionController() {
    session_start(); // Asegura que la sesión está activa
    $_SESSION = [];  // Limpia todas las variables de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    
    if (headers_sent()) {
        echo "
        <script>
            window.location.href = '".APP_URL."login/';
        </script>
        ";
    } else {
        header("Location: ".APP_URL."login/");
        exit();
    }
}

}