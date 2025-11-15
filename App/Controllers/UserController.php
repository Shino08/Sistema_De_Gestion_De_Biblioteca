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

            $alert = [
                "type" => "simple",
                "icon" => "error",
                "title" => "Error",
                "text" => "Faltan data por ingresar"
            ];
            return json_encode($alert);
            exit();

        }

        # Verificando integridad de data #

        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $alert = [
                "type" => "simple",
                "icon" => "error",
                "title" => "Error",
                "text" => "El Nombre debe tener al menos 3 caracteres"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastName)) {
            $alert = [
                "type" => "simple",
                "icon" => "error",
                "title" => "Error",
                "text" => "El Apellido debe tener al menos 3 caracteres"
            ];
            return json_encode($alert); 
            exit();
        }
        if ($this->VerifyData("[0-9]{8,15}", $phone)) {
            $alert = [
                "type" => "simple",
                "icon" => "error",
                "title" => "Error",
                "text" => "El Telefono debe tener al menos 8 caracteres"
            ];
            return json_encode($alert); 
            exit();
        }

        # Verificando email#

        if ($email != '') {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_email= $this->ExecuteQuery("SELECT email FROM users WHERE email='$email'");

                if ($check_email->rowCount()>0) {
                    $alert = [
                        "type" => "simple",
                        "icon" => "error",
                        "title" => "Error",
                        "text" => "El Correo ya está registrado"
                    ];
                    return json_encode($alert);
                    exit();
                }
            }else {
                $alert = [
                    "type" => "simple",
                    "icon" => "error",
                    "title" => "Error",
                    "text" => "El Correo no es válido"
                ];
                return json_encode($alert);
                exit();
            }
        }

        # Verificando usuario  #

        $check_user = $this->ExecuteQuery("SELECT first_name FROM users WHERE first_name='$name'");

        if ($check_user->rowCount()>0) {
            $alert = [
                "type" => "simple",
                "icon" => "error",
                "title" => "Error",
                "text" => "El usuario ya está registrado"
            ];
            return json_encode($alert);
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
            $alert = [
                "type" => "simple",
                "icon" => "success",
                "title" => "Exito",
                "text" => "El usuario se registro correctamente"
            ];
            return json_encode($alert);
            exit();

        }else {

            $alert = [
                "type" => "simple",
                "icon" => "error",
                "title" => "Error",
                "text" => "El usuario no se registro correctamente"
            ];
            return json_encode($alert);
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

    // Corrección: recuperar datos y total desde consultas separadas
    $dataStmt = $this->ExecuteQuery($query);
    $data = $dataStmt->fetchAll();

    $totalStmt = $this->ExecuteQuery($queryTotal);
    $total = (int)$totalStmt->fetchColumn();

    $numberPages = ceil($total/$rows); 

    $table .= '
        <div class="table-container">
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr class="has-background-success-light">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th class="has-text-centered">Acciones</th>
                </tr>
            </thead>
            <tbody>'
        ;

    if ($total >= 1 && $page <= $numberPages) {
        $count = $init + 1;
        $pagInit = $init + 1;

        foreach($data as $rows){
        $table .= '
            <tr class="has-text-centered">
                <td>'.$count.'</td>
                <td>'.$rows['first_name'].' '.$rows['last_name'].'</td>
                <td>'.$rows['email'].'</td>
                <td>'.$rows['phone'].'</td>
                <td>
                    <button 
                        type="button" 
                        class="button is-primary is-medium" 
                        onclick="openEditModal('.$rows['user_id'].', '.json_encode($rows['first_name']).', '.json_encode($rows['last_name']).', '.json_encode($rows['email']).', '.json_encode($rows['phone']).')"
                    >
                        <span class="icon"><i class="fas fa-edit"></i></span>
                        <span>Editar</span>
                    </button>


                    <form class="Form" action="'.APP_URL.'App/Forms/userForm.php" method="POST" autocomplete="off" style="display:inline;">
                        <input type="hidden" name="userModule" value="delete">
                        <input type="hidden" name="user_id" value="'.$rows['user_id'].'">
                        <button type="submit" class="button is-danger is-rounded is-small">
                            <span class="icon">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span>Eliminar</span>
                        </button>
                    </form>
                </td>
            </tr>';

            $count++;
        }

        $pagFinal = $count - 1;

    } else {
        if ($total >= 1) {
            $table .= '
            <tr class="has-text-centered" >
                <td colspan="7">
                    <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>';
        } else {
            $table.= '
            <tr class="has-text-centered" >
                <td colspan="7">
                    No hay registros en el sistema
                </td>
            </tr>';
        }
    }

    $table .= '</tbody></table></div>';

    if ($total >= 1 && $page <= $numberPages) {
        $table .= '<p class="has-text-right">Mostrando usuarios <strong>'.$pagInit.'</strong> al <strong>'.$pagFinal.'</strong> de un <strong>total de '.$total.'</strong></p>';

        $table .= $this->PaginationTables($page, $numberPages, $url, 5);
    }

    return $table;
}

    # Controlador eliminar usuario #

public function DeleteUserController(){
    
    $id = $this->CleanData($_POST['user_id']);
    
    if ($id == 1) {
        $alert = [
            "type" => "simple",
            "icon" => "error",
            "title" => "Ocurrió un error",
            "text" => "No se puede eliminar el usuario"
        ];
        return json_encode($alert);
        exit();
    }
    
    $data = $this->ExecuteQuery("SELECT * FROM users WHERE user_id='$id'");
    
    if ($data->rowCount() <= 0) {
        $alert = [
            "type" => "simple",
            "icon" => "error",
            "title" => "Ocurrió un error",
            "text" => "No hemos encontrado el usuario"
        ];
        return json_encode($alert);
        exit();
    } else {
        $data = $data->fetch();
    }
    
    $userDelete = $this->DeleteData("users", "user_id", $id);
    
    if ($userDelete->rowCount() == 1) {
        $alert = [
            "type" => "simple",
            "icon" => "success",
            "title" => "Éxito",
            "text" => "El usuario " . $data['first_name'] . " " . $data['last_name'] . " se eliminó correctamente"
        ];
        return json_encode($alert);
        exit();
    } else {
        $alert = [
            "type" => "simple",
            "icon" => "error",
            "title" => "Ocurrió un error",
            "text" => "El usuario " . $data['first_name'] . " " . $data['last_name'] . " no se eliminó correctamente"
        ];
        return json_encode($alert);
        exit();
    }
}

    # Controlador actualizar usuario #

    public function UpdateUserController(){

        $id = $this->CleanData($_POST['user_id']);

        $data = $this->ExecuteQuery("SELECT * FROM users WHERE user_id='$id'");
        if ($data->rowCount()<= 0) {
            $alert=[
                "type" => "simple",
                "title" => "Ocurrió un error",
                "text" => "No hemos encontrado el usuario",
                "icon" => "error"
            ];
            return json_encode($alert);
            exit();
        } else {
            $data = $data->fetch();
        }

        $admin_usuario = $this->CleanData($_POST['admin_usuario']);

        $admin_clave = $this->CleanData($_POST['admin_clave']);

        # Verificar usuario y clave #

        if ($admin_usuario != '' || $admin_clave != '') {
            $alert=[
                "type" => "simple",
                "title" => "Ocurrió un error",
                "text" => "El usuario o clave no coinciden",
                "icon" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        # Verificando integridad de data #

        if ($this->VerifyData("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "El usuario no coinciden",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->VerifyData("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "La clave no coinciden",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        
        # Verificar data del usuario #

        $check_admin = $this->ExecuteQuery("SELECT * FROM usuario WHERE usuario_id='".$_SESSION['id']."'");

        if ($check_admin->rowCount() == 1) {
            $check_admin = $check_admin->fetch();

            if ($check_admin['usario_usuario'] != $admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])) {
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "El usuario o clave no coinciden",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }else{
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "El usuario o clave no coinciden",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        #Almacenar data del formulario #
        $name = $this->CleanData($_POST['first_name']);
        $lastName = $this->CleanData($_POST['last_name']);
        $usuario = $this->CleanData($_POST['usuario_usuario']);
        $email = $this->CleanData($_POST['usuario_email']);
        $clave1 = $this->CleanData($_POST['usuario_clave_1']);
        $clave2 = $this->CleanData($_POST['usuario_clave_2']);

        # Verificando data obligatorios #
        if ($name == "" || $lastName == "" || $usuario == "") {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "Faltan data por ingresar",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        #Verificando integridad de data #

        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "El name debe tener al menos 3 caracteres",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastName)) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "El lastName debe tener al menos 3 caracteres",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $usuario)) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "El usuario debe tener al menos 3 caracteres",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        # Verificando email#

        if ($email != '' && $data['usuario_email'] != $email) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_email= $this->ExecuteQuery("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");

                if ($check_email->rowCount()>0) {
                    $alert=[
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error",
                        "texto" => "El email ya está registrado",
                        "icono" => "error"
                    ];
                    return json_encode($alert);
                    exit();
                }
            }else {
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "El email no es válido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }

        #Verificando claves#

        if ($clave1 != '' || $clave2 != '') {

            if ($this->VerifyData("[a-zA-Z0-9$@.-]{7,100}", $clave1) || $this->VerifyData("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {

            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "Las claves deben tener al menos 7 caracteres",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }else {
            if ($clave1 != $clave2) {
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "Las claves no coinciden",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }else {
                $clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
            }
        }

        }else {
            $clave = $data['usuario_clave'];
        }

        # Verificando usuario#
        if ($data['usuario_usuario'] != $usuario) {
            $check_user = $this->ExecuteQuery("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
    
            if ($check_user->rowCount()>0) {
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "El usuario ya está registrado",
                    "icono" => "error"
                ];
                return json_encode($alert);
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
                "field_name" => "usuario_usuario",
                "field_mark" => ":Usuario",
                "field_value" => $usuario
            ],
            [
                "field_name" => "usuario_clave",
                "field_mark" => ":Clave",
                "field_value" => $clave
            ],
            [
                "field_name" => "usuario_actualizado",
                "field_mark" => ":Actualizado",
                "field_value" => date('Y-m-d H:i:s')
            ],
        ];

        $condicion = [
            "condicion_campo" => "usuario_id",
            "condicion_marcador" => ":ID",
            "condicion_valor" => $id
        ];

                if($this->Actualizardata("usuario", $usuario_data_up, $condicion)){

                    if ($id == $_SESSION['id']) {
                        $_SESSION['name'] = $name;
                        $_SESSION['lastName'] = $lastName;
                        $_SESSION['usuario'] = $usuario;

                    }
            $alert=[
                "tipo" => "recargar",
                "titulo" => "Usuario actualizado",
                "texto" => "El usuario ".$data['first_name']. " ".$data['last_name']. " se actualizó correctamente",
                "icono" => "success"
            ];

        }else {

            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "El usuario no se pudo actualizar correctamente",
                "icono" => "error"
            ];
        }
        return json_encode($alert);
        
    }

    # Controlador actualizar foto usuario #

    public function ActualizarFotoUsuarioController(){

        $id = $this->CleanData($_POST['usuario_id']);

        $data = $this->ExecuteQuery("SELECT * FROM usuario WHERE usuario_id='$id'");
        if ($data->rowCount()<= 0) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "No hemos encontrado el usuario",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        } else {
            $data = $data->fetch();
        }

                # Directorio de imagenes #

        $img_dir = "../Views/Photos/";

        # Comprobar si se selecciono una imagen #

        if ($_FILES['usuario_foto']['name'] == '' && $_FILES['usuario_foto']['size'] <= 0) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "No hemos encontrado la foto",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

         # Creando directorio #

            if (!file_exists($img_dir)) {
                if (!mkdir($img_dir, 0777)) {
                    $alert=[
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error",
                        "texto" => "Error al crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alert);
                    exit();
                }
            }

            # Verificando tipo de archivo #

            if (mime_content_type($_FILES['usuario_foto']['tmp_name']) != 'image/jpeg' && mime_content_type($_FILES['usuario_foto']['tmp_name']) != 'image/png') {
                
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "El archivo no es una imagen",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Verificando peso de imagen #

            if (($_FILES['usuario_foto']['size']/1024) > 5120) {
                
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "El archivo es demasiado grande",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # name de la imagen #

            if ($data['usuario_foto'] != '') {
                $foto = explode(".", $data['usuario_foto']);
                $foto = $foto[0];
            } else {
                $foto = str_ireplace('', '_', $data['first_name']);
                $foto = $foto.'_'.rand(0, 100);
            }
            
            # Extension de la imagen #
            
            switch (mime_content_type($_FILES['usuario_foto']['tmp_name'])) {
                case 'image/jpeg':
                    $foto = $foto.'.jpg';
                    break;

                case 'image/png':
                    $foto = $foto.'.png';
                    break;
            }

            chmod($img_dir, 0777);

            # Moviendo imagen al directorio #

            if(!move_uploaded_file($_FILES['usuario_foto']['tmp_name'], $img_dir.$foto)) {
                $alert=[
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error",
                    "texto" => "Error al subir la imagen",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Eliminando la imagen #

            if (is_file($img_dir.$data['usuario_foto']) && $data['usuario_foto'] != $foto) {
                chmod($img_dir.$data['usuario_foto'], 0777);
                unlink($img_dir.$data['usuario_foto']);
            }

            $usuario_data_up = [
            [
                "field_name" => "usuario_foto",
                "field_mark" => ":Foto",
                "field_value" => $foto
            ],
            [
                "field_name" => "usuario_actualizado",
                "field_mark" => ":Actualizado",
                "field_value" => date('Y-m-d H:i:s')
            ]
        ];

        $condicion = [
            "condicion_campo" => "usuario_id",
            "condicion_marcador" => ":ID",
            "condicion_valor" => $id
        ];

        if ($this->Actualizardata("usuario", $usuario_data_up, $condicion)) {

            if($id == $_SESSION['id']) {
                $_SESSION['foto'] = $foto;
            }

            $alert=[
                "tipo" => "recargar",
                "titulo" => "Foto actualizada",
                "texto" => "La foto del usuario ". $data['first_name']. " ". $data['last_name']. " se actualizó correctamente",
                "icono" => "success"
            ];
        } else {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "La foto del usuario no se pudo actualizar correctamente",
                "icono" => "warning"
            ];
        }
        return json_encode($alert);
    }

    # Controlador eliminar foto usuario #

    public function EliminarFotoUsuarioController(){

        $id = $this->CleanData($_POST['usuario_id']);

        $data = $this->ExecuteQuery("SELECT * FROM usuario WHERE usuario_id='$id'");
        if ($data->rowCount()<= 0) {
            $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "No hemos encontrado el usuario",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        } else {
            $data = $data->fetch();
        }

        $img_dir = "../Views/Photos/";

        chmod($img_dir, 0777);

        if (is_file($img_dir.$data['usuario_foto'])) {
            chmod($img_dir.$data['usuario_foto'], 0777);
            if (unlink($img_dir.$data['usuario_foto'])) {
                $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "No hemos encontrado la foto del usuario",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
            }
        } else {
           $alert=[
                "tipo" => "simple",
                "titulo" => "Ocurrió un error",
                "texto" => "No hemos encontrado la foto del usuario en el sistema",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        
        $usuario_data_up = [
            [
                "field_name" => "usuario_foto",
                "field_mark" => ":Foto",
                "field_value" => ""
            ],
            [
                "field_name" => "usuario_actualizado",
                "field_mark" => ":Actualizado",
                "field_value" => date('Y-m-d H:i:s')
            ]
        ];

        $condicion = [
            "condicion_campo" => "usuario_id",
            "condicion_marcador" => ":ID",
            "condicion_valor" => $id
        ];

        if ($this->Actualizardata("usuario", $usuario_data_up, $condicion)) {

            if($id == $_SESSION['id']) {
                $_SESSION['foto'] = "";
            }

            $alert=[
                "tipo" => "recargar",
                "titulo" => "Foto eliminada",
                "texto" => "La foto del usuario ". $data['first_name']. " ". $data['last_name']. " se eliminó correctamente",
                "icono" => "success"
            ];
        } else {
            $alert=[
                "tipo" => "recargar",
                "titulo" => "Ocurrió un error",
                "texto" => "No hemos podido actualizar la foto del usuario, sin embargo la foto se eliminó correctamente",
                "icono" => "warning"
            ];
        }
        return json_encode($alert);

    }

}