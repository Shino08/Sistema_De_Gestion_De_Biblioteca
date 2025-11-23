<?php

namespace App\Controllers;

use App\Models\MainModel;

class AuthorController extends MainModel {

    # Controlador para registrar autor #

    public function RegisterAuthorController() {
        
        $name = $this->CleanData($_POST['author_name']);
        $lastName = $this->CleanData($_POST['author_lastName']);
        $nationality = $this->CleanData($_POST['author_nationality']);
        $birthdate = $this->CleanData($_POST['author_birthdate']);

        # Verificando datos obligatorios #

        if ($name == "" || $lastName == ""){
            $_SESSION['alert'] = "Error: Faltan datos por ingresar";
            header("Location: ".APP_URL."authorCreate/");
            exit();
        }

        # Verificando integridad de datos #

        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $_SESSION['alert'] = "Error: El Nombre debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."authorCreate/");
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastName)) {
            $_SESSION['alert'] = "Error: El Apellido debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."authorCreate/");
            exit();
        }

        $data_author_register = [
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
                "field_name" => "nationality",
                "field_mark" => ":nationality",
                "field_value" => $nationality
            ],
            [
                "field_name" => "birth_date",
                "field_mark" => ":birth_date",
                "field_value" => $birthdate
            ]
        ];

        $author_register = $this->SaveData("authors", $data_author_register);

        if($author_register->rowCount() == 1){
            $_SESSION['alert'] = "Éxito: El autor se registró correctamente";
            header("Location: ".APP_URL."managementAuthors/1/");
            exit();
        }else {
            $_SESSION['alert'] = "Error: El autor no se registró correctamente";
            header("Location: ".APP_URL."authorCreate/");
            exit();
        }
    }

    # Controlador lista de autores #

    public function AuthorListController($page, $rows, $url, $search){
        $page = $this->CleanData($page);
        $rows = $this->CleanData($rows);
        $url = $this->CleanData($url);
        $url = APP_URL.$url.'/';
        $search = $this->CleanData($search);
        $table = "";

        $page = (isset($page) && $page > 0) ? (int) $page : 1; 
        $init = ($page > 1) ? ($page * $rows) - $rows : 0;

        if (isset($search) && $search != '') {
            $query = "SELECT * FROM authors WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR nationality LIKE '%$search%') ORDER BY first_name ASC LIMIT $init, $rows";
            $queryTotal = "SELECT COUNT(author_id) FROM authors WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR nationality LIKE '%$search%')";
        } else {
            $query = "SELECT * FROM authors ORDER BY first_name ASC LIMIT $init, $rows";
            $queryTotal = "SELECT COUNT(author_id) FROM authors";
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
                        <th>Nacionalidad</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';

        if ($total >= 1 && $page <= $numberPages) {
            $count = $init + 1;
            $pagInit = $init + 1;

            foreach($data as $rows){
            $table .= '
                <tr>
                    <td>'.$count.'</td>
                    <td>'.$rows['first_name'].'</td>
                    <td>'.$rows['last_name'].'</td>
                    <td>'.$rows['nationality'].'</td>
                    <td>'.$rows['birth_date'].'</td>
                    <td>
                        <a href="'.APP_URL.'authorEdit/'.$rows['author_id'].'/">Editar</a>
                        
                        <form action="'.APP_URL.'App/Forms/authorForm.php" method="POST" style="display:inline;">
                            <input type="hidden" name="authorModule" value="delete">
                            <input type="hidden" name="author_id" value="'.$rows['author_id'].'">
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
                        <a href="'.$url.'1/">Haga clic acá para recargar el listado</a>
                    </td>
                </tr>';
            } else {
                $table.= '
                <tr>
                    <td colspan="6">No hay registros en el sistema</td>
                </tr>';
            }
        }

        $table .= '</tbody></table>';

        if ($total >= 1 && $page <= $numberPages) {
            $table .= '<p>Mostrando autores <strong>'.$pagInit.'</strong> al <strong>'.$pagFinal.'</strong> de un <strong>total de '.$total.'</strong></p>';
            $table .= $this->PaginationTables($page, $numberPages, $url, 5);
        }

        return $table;
    }

    # Controlador eliminar autor #

    public function DeleteAuthorController(){
        
        $id = $this->CleanData($_POST['author_id']);
        
        $data = $this->ExecuteQuery("SELECT * FROM authors WHERE author_id='$id'");
        
        if ($data->rowCount() <= 0) {
            $_SESSION['alert'] = "Error: No hemos encontrado el autor";
            header("Location: ".APP_URL."managementAuthors/1/");
            exit();
        } else {
            $data = $data->fetch();
        }
        
        $authorDelete = $this->DeleteData("authors", "author_id", $id);
        
        if ($authorDelete->rowCount() == 1) {
            $_SESSION['alert'] = "Éxito: El autor " . $data['first_name'] . " " . $data['last_name'] . " se eliminó correctamente";
        } else {
            $_SESSION['alert'] = "Error: El autor no se eliminó correctamente";
        }
        
        header("Location: ".APP_URL."managementAuthors/1/");
        exit();
    }

    # Controlador actualizar autor #

    public function UpdateAuthorController(){

        $id = $this->CleanData($_POST['author_id']);

        $data = $this->ExecuteQuery("SELECT * FROM authors WHERE author_id='$id'");
        if ($data->rowCount()<= 0) {
            $_SESSION['alert'] = "Error: No hemos encontrado el autor";
            header("Location: ".APP_URL."managementAuthors/1/");
            exit();
        } else {
            $data = $data->fetch();
        }

        $name = $this->CleanData($_POST['author_name']);
        $lastName = $this->CleanData($_POST['author_lastName']);
        $nationality = $this->CleanData($_POST['author_nationality']);
        $birthdate = $this->CleanData($_POST['author_birthdate']);

        if ($name == "" || $lastName == "") {
            $_SESSION['alert'] = "Error: Faltan datos por ingresar";
            header("Location: ".APP_URL."authorEdit/$id/");
            exit();
        }

        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $name)) {
            $_SESSION['alert'] = "Error: El nombre debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."authorEdit/$id/");
            exit();
        }
        if ($this->VerifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastName)) {
            $_SESSION['alert'] = "Error: El apellido debe tener al menos 3 caracteres";
            header("Location: ".APP_URL."authorEdit/$id/");
            exit();
        }

        $author_data_up = [
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
                "field_name" => "nationality",
                "field_mark" => ":nationality",
                "field_value" => $nationality
            ],
            [
                "field_name" => "birth_date",
                "field_mark" => ":birthdate",
                "field_value" => $birthdate
            ]
        ];

        $condicion = [
            "condition_field" => "author_id",
            "condition_mark" => ":ID",
            "condition_value" => $id
        ];

        if($this->UpdateData("authors", $author_data_up, $condicion)){
            $_SESSION['alert'] = "Éxito: El autor se actualizó correctamente";
        }else {
            $_SESSION['alert'] = "Error: El autor no se pudo actualizar correctamente";
        }
        
        header("Location: ".APP_URL."managementAuthors/1/");
        exit();
    }

}
