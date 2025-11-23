<?php

namespace App\Controllers;

use App\Models\MainModel;

# Este controlador se encarga de todas las operaciones relacionadas con los usuarios #

class UserController extends MainModel {

    # Controlador para registrar usuario #

    public function RegisterUserController() {
        
        #Almacenar data del formulario #

        $name = $this->CleanData($_POST['user_name']);
        $lastName = $this->CleanData($_POST['user_lastName']);
        $email = $this->CleanData($_POST['user_email']);
        $phone = $this->CleanData($_POST['user_phone']);

        # Verificando data obligatorios #

        if ($name == "" || $lastName == "" || $phone == ""){
            $_SESSION['alert'] = "Error: Faltan datos por ingresar";
            header("Location: ".APP_URL."userCreate/");
            exit();
        }

        # Verificando integridad de data #

        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $_SESSION['alert'] = "Error: El Nombre debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."userCreate/");
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastName)) {
            $_SESSION['alert'] = "Error: El Apellido debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."userCreate/");
            exit();
        }
        if ($this->VerifyData("[0-9]{8,15}", $phone)) {
            $_SESSION['alert'] = "Error: El Teléfono debe tener al menos 8 caracteres";
            header("Location: ".APP_URL."userCreate/");
            exit();
        }

        # Verificando email#

        if ($email != '') {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_email= $this->ExecuteQuery("SELECT email FROM users WHERE email='$email'");

                if ($check_email->rowCount()>0) {
                    $_SESSION['alert'] = "Error: El Correo ya está registrado";
                    header("Location: ".APP_URL."userCreate/");
                    exit();
                }
            }else {
                $_SESSION['alert'] = "Error: El Correo no es válido";
                header("Location: ".APP_URL."userCreate/");
                exit();
            }
        }

        # Verificando usuario  #

        $check_user = $this->ExecuteQuery("SELECT first_name FROM users WHERE first_name='$name'");

        if ($check_user->rowCount()>0) {
            $_SESSION['alert'] = "Error: El usuario ya está registrado";
            header("Location: ".APP_URL."userCreate/");
            exit();
        }

        $data_user_register = [
            [
                "field_name" => "first_name",
                "field_mark" => ":first_name",
                "field_value" => $name
            ],
            [
                "field_name" => "last_name",
                "field_mark" => ":last_name",
                "field_value" => $lastName
            ],
            [
                "field_name" => "email",
                "field_mark" => ":email",
                "field_value" => $email
            ],
            [
                "field_name" => "phone",
                "field_mark" => ":phone",
                "field_value" => $phone
            ]
        ];

        $user_register = $this->SaveData("users", $data_user_register);

        if($user_register->rowCount() == 1){
            $_SESSION['alert'] = "Éxito: El usuario se registró correctamente";
            header("Location: ".APP_URL."userManagement/1/");
            exit();
        }else {
            $_SESSION['alert'] = "Error: El usuario no se registró correctamente";
            header("Location: ".APP_URL."userCreate/");
            exit();
        }
    }

    # Controlador lista de usuarios #

    public function UserListController($page, $rows, $url, $search){
        $page = $this->CleanData($page);
        $rows = $this->CleanData($rows);

        $url = $this->CleanData($url);
        $url = APP_URL.$url.'/';

        $search = $this->CleanData($search);
        $table = "";

        $page = (isset($page) && $page > 0) ? (int) $page : 1; 
        $init = ($page > 1) ? ($page * $rows) - $rows : 0;

        if (isset($search) && $search != '') {
            $query = "SELECT * FROM users WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%') ORDER BY first_name ASC LIMIT $init, $rows";
            $queryTotal = "SELECT COUNT(user_id) FROM users WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%')";
        } else {
            $query = "SELECT * FROM users ORDER BY first_name ASC LIMIT $init, $rows";
            $queryTotal = "SELECT COUNT(user_id) FROM users";
        }

        $dataStmt = $this->ExecuteQuery($query);
        $data = $dataStmt->fetchAll();

        $totalStmt = $this->ExecuteQuery($queryTotal);
        $total = (int)$totalStmt->fetchColumn();

        $numberPages = ceil($total/$rows); 

        $table .= '
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>'
            ;

        if ($total >= 1 && $page <= $numberPages) {
            $count = $init + 1;
            $pagInit = $init + 1;

            foreach($data as $rows){
            $table .= '
                <tr>
                    <td>'.$count.'</td>
                    <td>'.$rows['first_name'].'</td>
                    <td>'.$rows['last_name'].'</td>
                    <td>'.$rows['email'].'</td>
                    <td>'.$rows['phone'].'</td>
                    <td>
                        <a href="'.APP_URL.'userEdit/'.$rows['user_id'].'/">Editar</a>
                        
                        <form action="'.APP_URL.'App/Forms/userForm.php" method="POST" style="display:inline;">
                            <input type="hidden" name="userModule" value="delete">
                            <input type="hidden" name="user_id" value="'.$rows['user_id'].'">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>';

                $count++;
            }

            $pagFinal = $count - 1;

        } else {
            if ($total >= 1) {
                $table .= '
                <tr>
                    <td colspan="6">
                        <a href="'.$url.'1/">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                </tr>';
            } else {
                $table.= '
                <tr>
                    <td colspan="6">
                        No hay registros en el sistema
                    </td>
                </tr>';
            }
        }

        $table .= '</tbody></table>';

        if ($total >= 1 && $page <= $numberPages) {
            $table .= '<p>Mostrando usuarios <strong>'.$pagInit.'</strong> al <strong>'.$pagFinal.'</strong> de un <strong>total de '.$total.'</strong></p>';

            $table .= $this->PaginationTables($page, $numberPages, $url, 5);
        }

        return $table;
    }

    # Controlador eliminar usuario #

    public function DeleteUserController(){
        
        $id = $this->CleanData($_POST['user_id']);
        
        if ($id == 1) {
            $_SESSION['alert'] = "Error: No se puede eliminar el usuario principal";
            header("Location: ".APP_URL."userManagement/1/");
            exit();
        }
        
        $data = $this->ExecuteQuery("SELECT * FROM users WHERE user_id='$id'");
        
        if ($data->rowCount() <= 0) {
            $_SESSION['alert'] = "Error: No hemos encontrado el usuario";
            header("Location: ".APP_URL."userManagement/1/");
            exit();
        } else {
            $data = $data->fetch();
        }
        
        $userDelete = $this->DeleteData("users", "user_id", $id);
        
        if ($userDelete->rowCount() == 1) {
            $_SESSION['alert'] = "Éxito: El usuario " . $data['first_name'] . " " . $data['last_name'] . " se eliminó correctamente";
        } else {
            $_SESSION['alert'] = "Error: El usuario " . $data['first_name'] . " " . $data['last_name'] . " no se eliminó correctamente";
        }
        
        header("Location: ".APP_URL."userManagement/1/");
        exit();
    }

    # Controlador actualizar usuario #

    public function UpdateUserController(){

        $id = $this->CleanData($_POST['user_id']);

        $data = $this->ExecuteQuery("SELECT * FROM users WHERE user_id='$id'");
        if ($data->rowCount()<= 0) {
            $_SESSION['alert'] = "Error: No hemos encontrado el usuario";
            header("Location: ".APP_URL."userManagement/1/");
            exit();
        } else {
            $data = $data->fetch();
        }

        #Almacenar data del formulario #
        $name = $this->CleanData($_POST['user_name']);
        $lastName = $this->CleanData($_POST['user_lastName']);
        $email = $this->CleanData($_POST['user_email']);
        $phone = $this->CleanData($_POST['user_phone']);

        # Verificando data obligatorios #
        if ($name == "" || $lastName == "" || $phone == "") {
            $_SESSION['alert'] = "Error: Faltan datos por ingresar";
            header("Location: ".APP_URL."userEdit/$id/");
            exit();
        }

        #Verificando integridad de data #

        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $_SESSION['alert'] = "Error: El nombre debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."userEdit/$id/");
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastName)) {
            $_SESSION['alert'] = "Error: El apellido debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."userEdit/$id/");
            exit();
        }
        if ($this->VerifyData("[0-9]{8,15}", $phone)) {
            $_SESSION['alert'] = "Error: El teléfono debe tener al menos 8 caracteres";
            header("Location: ".APP_URL."userEdit/$id/");
            exit();
        }

        # Verificando email#

        if ($email != '' && $data['email'] != $email) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_email= $this->ExecuteQuery("SELECT email FROM users WHERE email='$email'");

                if ($check_email->rowCount()>0) {
                    $_SESSION['alert'] = "Error: El email ya está registrado";
                    header("Location: ".APP_URL."userEdit/$id/");
                    exit();
                }
            }else {
                $_SESSION['alert'] = "Error: El email no es válido";
                header("Location: ".APP_URL."userEdit/$id/");
                exit();
            }
        }

        $usuario_data_up = [
            [
                "field_name" => "first_name",
                "field_mark" => ":name",
                "field_value" => $name
            ],
            [
                "field_name" => "last_name",
                "field_mark" => ":lastName",
                "field_value" => $lastName
            ],
            [
                "field_name" => "email",
                "field_mark" => ":email",
                "field_value" => $email
            ],
            [
                "field_name" => "phone",
                "field_mark" => ":phone",
                "field_value" => $phone
            ]
        ];

        $condicion = [
            "condition_field" => "user_id",
            "condition_mark" => ":ID",
            "condition_value" => $id
        ];

        if($this->UpdateData("users", $usuario_data_up, $condicion)){
            $_SESSION['alert'] = "Éxito: El usuario ".$data['first_name']. " ".$data['last_name']. " se actualizó correctamente";
        }else {
            $_SESSION['alert'] = "Error: El usuario no se pudo actualizar correctamente";
        }
        
        header("Location: ".APP_URL."userManagement/1/");
        exit();
    }

}