<?php

namespace App\Controllers;

use App\Models\MainModel;

class BookController extends MainModel {

    # Controlador para registrar libro #

    public function RegisterBookController() {
        
        $title = $this->CleanData($_POST['book_title']);
        $author_id = $this->CleanData($_POST['author_id']);
        $isbn = $this->CleanData($_POST['book_isbn']);
        $publication_year = $this->CleanData($_POST['book_year']);
        $genre = $this->CleanData($_POST['book_genre']);
        $stock = $this->CleanData($_POST['book_stock']);

        # Verificando datos obligatorios #

        if ($title == "" || $author_id == "" || $stock == ""){
            $_SESSION['alert'] = "Error: Faltan datos por ingresar";
            header("Location: ".APP_URL."bookCreate/");
            exit();
        }

        # Verificando integridad de datos #

        if ($this->VerifyData("[0-9]{1,}", $stock)) {
            $_SESSION['alert'] = "Error: El stock debe ser un número válido";
            header("Location: ".APP_URL."bookCreate/");
            exit();
        }

        $data_book_register = [
            [
                "field_name" => "title",
                "field_mark" => ":title",
                "field_value" => $title
            ],
            [
                "field_name" => "author_id",
                "field_mark" => ":author_id",
                "field_value" => $author_id
            ],
            [
                "field_name" => "isbn",
                "field_mark" => ":isbn",
                "field_value" => $isbn
            ],
            [
                "field_name" => "publication_year",
                "field_mark" => ":publication_year",
                "field_value" => $publication_year
            ],
            [
                "field_name" => "genre",
                "field_mark" => ":genre",
                "field_value" => $genre
            ],
            [
                "field_name" => "stock",
                "field_mark" => ":stock",
                "field_value" => $stock
            ]
        ];

        $book_register = $this->SaveData("books", $data_book_register);

        if($book_register->rowCount() == 1){
            $_SESSION['alert'] = "Éxito: El libro se registró correctamente";
            header("Location: ".APP_URL."managementBooks/1/");
            exit();
        }else {
            $_SESSION['alert'] = "Error: El libro no se registró correctamente";
            header("Location: ".APP_URL."bookCreate/");
            exit();
        }
    }

    # Controlador lista de libros #

    public function BookListController($page, $rows, $url, $search){
        $page = $this->CleanData($page);
        $rows = $this->CleanData($rows);
        $url = $this->CleanData($url);
        $url = APP_URL.$url.'/';
        $search = $this->CleanData($search);
        $table = "";

        $page = (isset($page) && $page > 0) ? (int) $page : 1; 
        $init = ($page > 1) ? ($page * $rows) - $rows : 0;

        if (isset($search) && $search != '') {
            $query = "SELECT b.*, CONCAT(a.first_name, ' ', a.last_name) as author_name 
                      FROM books b 
                      LEFT JOIN authors a ON b.author_id = a.author_id 
                      WHERE (b.title LIKE '%$search%' OR b.isbn LIKE '%$search%' OR a.first_name LIKE '%$search%' OR a.last_name LIKE '%$search%') 
                      ORDER BY b.title ASC LIMIT $init, $rows";
            $queryTotal = "SELECT COUNT(b.book_id) FROM books b LEFT JOIN authors a ON b.author_id = a.author_id WHERE (b.title LIKE '%$search%' OR b.isbn LIKE '%$search%' OR a.first_name LIKE '%$search%' OR a.last_name LIKE '%$search%')";
        } else {
            $query = "SELECT b.*, CONCAT(a.first_name, ' ', a.last_name) as author_name 
                      FROM books b 
                      LEFT JOIN authors a ON b.author_id = a.author_id 
                      ORDER BY b.title ASC LIMIT $init, $rows";
            $queryTotal = "SELECT COUNT(book_id) FROM books";
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
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Año</th>
                        <th>Género</th>
                        <th>Stock</th>
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
                    <td><strong>'.$rows['title'].'</strong></td>
                    <td>'.$rows['author_name'].'</td>
                    <td>'.$rows['isbn'].'</td>
                    <td>'.$rows['publication_year'].'</td>
                    <td>'.$rows['genre'].'</td>
                    <td>'.$rows['stock'].'</td>
                    <td>
                        <a href="'.APP_URL.'bookEdit/'.$rows['book_id'].'/">Editar</a>
                        
                        <form action="'.APP_URL.'App/Forms/bookForm.php" method="POST" style="display:inline;">
                            <input type="hidden" name="bookModule" value="delete">
                            <input type="hidden" name="book_id" value="'.$rows['book_id'].'">
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
                    <td colspan="8">
                        <a href="'.$url.'1/">Haga clic acá para recargar el listado</a>
                    </td>
                </tr>';
            } else {
                $table.= '
                <tr>
                    <td colspan="8">No hay registros en el sistema</td>
                </tr>';
            }
        }

        $table .= '</tbody></table>';

        if ($total >= 1 && $page <= $numberPages) {
            $table .= '<p>Mostrando libros <strong>'.$pagInit.'</strong> al <strong>'.$pagFinal.'</strong> de un <strong>total de '.$total.'</strong></p>';
            $table .= $this->PaginationTables($page, $numberPages, $url, 5);
        }

        return $table;
    }

    # Controlador eliminar libro #

    public function DeleteBookController(){
        
        $id = $this->CleanData($_POST['book_id']);
        
        $data = $this->ExecuteQuery("SELECT * FROM books WHERE book_id='$id'");
        
        if ($data->rowCount() <= 0) {
            $_SESSION['alert'] = "Error: No hemos encontrado el libro";
            header("Location: ".APP_URL."managementBooks/1/");
            exit();
        } else {
            $data = $data->fetch();
        }
        
        $bookDelete = $this->DeleteData("books", "book_id", $id);
        
        if ($bookDelete->rowCount() == 1) {
            $_SESSION['alert'] = "Éxito: El libro " . $data['title'] . " se eliminó correctamente";
        } else {
            $_SESSION['alert'] = "Error: El libro no se eliminó correctamente";
        }
        
        header("Location: ".APP_URL."managementBooks/1/");
        exit();
    }

    # Controlador actualizar libro #

    public function UpdateBookController(){

        $id = $this->CleanData($_POST['book_id']);

        $data = $this->ExecuteQuery("SELECT * FROM books WHERE book_id='$id'");
        if ($data->rowCount()<= 0) {
            $_SESSION['alert'] = "Error: No hemos encontrado el libro";
            header("Location: ".APP_URL."managementBooks/1/");
            exit();
        } else {
            $data = $data->fetch();
        }

        $title = $this->CleanData($_POST['book_title']);
        $author_id = $this->CleanData($_POST['author_id']);
        $isbn = $this->CleanData($_POST['book_isbn']);
        $publication_year = $this->CleanData($_POST['book_year']);
        $genre = $this->CleanData($_POST['book_genre']);
        $stock = $this->CleanData($_POST['book_stock']);

        if ($title == "" || $author_id == "" || $stock == "") {
            $_SESSION['alert'] = "Error: Faltan datos por ingresar";
            header("Location: ".APP_URL."bookEdit/$id/");
            exit();
        }

        $book_data_up = [
            [
                "field_name" => "title",
                "field_mark" => ":title",
                "field_value" => $title
            ],
            [
                "field_name" => "author_id",
                "field_mark" => ":author_id",
                "field_value" => $author_id
            ],
            [
                "field_name" => "isbn",
                "field_mark" => ":isbn",
                "field_value" => $isbn
            ],
            [
                "field_name" => "publication_year",
                "field_mark" => ":publication_year",
                "field_value" => $publication_year
            ],
            [
                "field_name" => "genre",
                "field_mark" => ":genre",
                "field_value" => $genre
            ],
            [
                "field_name" => "stock",
                "field_mark" => ":stock",
                "field_value" => $stock
            ]
        ];

        $condicion = [
            "condition_field" => "book_id",
            "condition_mark" => ":ID",
            "condition_value" => $id
        ];

        if($this->UpdateData("books", $book_data_up, $condicion)){
            $_SESSION['alert'] = "Éxito: El libro se actualizó correctamente";
        }else {
            $_SESSION['alert'] = "Error: El libro no se pudo actualizar correctamente";
        }
        
        header("Location: ".APP_URL."managementBooks/1/");
        exit();
    }

}
