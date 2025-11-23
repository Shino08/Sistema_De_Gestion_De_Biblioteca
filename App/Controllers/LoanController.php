<?php

namespace App\Controllers;

use App\Models\MainModel;

// Controlador de Préstamos
// Maneja todas las operaciones relacionadas con préstamos, incluyendo creación, listado,
// devolución y renovación de libros.
class LoanController extends MainModel {

    // Registra un nuevo préstamo
    public function RegisterLoanController() {
        // Obtener y limpiar datos del formulario
        $bookId = $this->CleanData($_POST['book_id']);
        $userId = $this->CleanData($_POST['user_id']);
        $loanDate = $this->CleanData($_POST['loan_date']);
        $expectedReturnDate = $this->CleanData($_POST['expected_return_date']);

        // Validar campos obligatorios
        if (empty($bookId) || empty($userId) || empty($loanDate) || empty($expectedReturnDate)) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> Todos los campos son obligatorios
                      </div>';
            return $alert;
        }

        // Validar fechas
        if (strtotime($loanDate) > strtotime($expectedReturnDate)) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> La fecha de devolución debe ser posterior a la fecha de préstamo
                      </div>';
            return $alert;
        }

        // Verificar si el libro existe y tiene copias disponibles
        $checkBook = $this->SelectData("Unique", "books", "id", $bookId);
        
        if (count($checkBook) == 0) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> El libro seleccionado no existe
                      </div>';
            return $alert;
        }

        $book = $checkBook[0];
        
        if ($book['available_quantity'] <= 0) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> No hay copias disponibles de este libro
                      </div>';
            return $alert;
        }

        // Verificar si el usuario existe
        $checkUser = $this->SelectData("Unique", "users", "id", $userId);
        
        if (count($checkUser) == 0) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> El usuario seleccionado no existe
                      </div>';
            return $alert;
        }

        // Preparar datos del préstamo
        $loanData = [
            [
                "field_name" => "book_id",
                "field_mark" => ":BookId",
                "field_value" => $bookId
            ],
            [
                "field_name" => "user_id",
                "field_mark" => ":UserId",
                "field_value" => $userId
            ],
            [
                "field_name" => "loan_date",
                "field_mark" => ":LoanDate",
                "field_value" => $loanDate
            ],
            [
                "field_name" => "expected_return_date",
                "field_mark" => ":ExpectedReturnDate",
                "field_value" => $expectedReturnDate
            ],
            [
                "field_name" => "status",
                "field_mark" => ":Status",
                "field_value" => "active"
            ]
        ];

        // Guardar préstamo
        $saveLoan = $this->SaveData("loans", $loanData);

        if ($saveLoan) {
            // Disminuir cantidad disponible
            $newQuantity = $book['available_quantity'] - 1;
            $this->UpdateBookQuantity($bookId, $newQuantity);

            $alert = '<div class="notification is-success is-light">
                        <strong>¡Éxito!</strong> Préstamo registrado correctamente
                      </div>';
        } else {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> No se pudo registrar el préstamo
                      </div>';
        }

        return $alert;
    }

    // Lista los préstamos con paginación y búsqueda
    public function LoanListController($page, $rows, $url, $search) {
        $page = (int) $page;
        $rows = (int) $rows;
        $table = "";

        $page = ($page <= 1) ? 1 : $page;
        $start = ($page - 1) * $rows;

        // Construir consulta
        if (isset($search) && $search != "") {
            $query = "SELECT l.*, b.title as book_title, u.name as user_name 
                      FROM loans l 
                      INNER JOIN books b ON l.book_id = b.id 
                      INNER JOIN users u ON l.user_id = u.id 
                      WHERE b.title LIKE '%$search%' OR u.name LIKE '%$search%' 
                      ORDER BY l.id DESC LIMIT $start, $rows";
            
            $queryTotal = "SELECT COUNT(l.id) as total 
                          FROM loans l 
                          INNER JOIN books b ON l.book_id = b.id 
                          INNER JOIN users u ON l.user_id = u.id 
                          WHERE b.title LIKE '%$search%' OR u.name LIKE '%$search%'";
        } else {
            $query = "SELECT l.*, b.title as book_title, u.name as user_name 
                      FROM loans l 
                      INNER JOIN books b ON l.book_id = b.id 
                      INNER JOIN users u ON l.user_id = u.id 
                      ORDER BY l.id DESC LIMIT $start, $rows";
            
            $queryTotal = "SELECT COUNT(id) as total FROM loans";
        }

        $connection = $this->Connect();
        $result = $connection->query($query);
        $totalResult = $connection->query($queryTotal);
        $totalRow = $totalResult->fetch_assoc();
        $total = (int) $totalRow['total'];

        $numberPages = ceil($total / $rows);

        $table .= '
        <div class="table-container">
            <table class="table is-fullwidth is-striped is-hoverable">
                <thead>
                    <tr class="has-background-danger-light">
                        <th>ID</th>
                        <th>Libro</th>
                        <th>Usuario</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th class="has-text-centered">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

        if ($total >= 1) {
            while ($row = $result->fetch_assoc()) {
                // Determinar estado
                $statusClass = "";
                $statusText = "";
                
                if ($row['status'] == 'returned') {
                    $statusClass = "is-light";
                    $statusText = "Devuelto";
                } elseif ($row['status'] == 'active') {
                    // Verificar si está vencido
                    if (strtotime($row['expected_return_date']) < strtotime(date('Y-m-d'))) {
                        $statusClass = "is-danger";
                        $statusText = "Vencido";
                    } else {
                        $statusClass = "is-success";
                        $statusText = "Activo";
                    }
                } else {
                    $statusClass = "is-warning";
                    $statusText = "Vencido";
                }

                $table .= '
                <tr>
                    <td>' . $row['id'] . '</td>
                    <td><strong>' . $row['book_title'] . '</strong></td>
                    <td>' . $row['user_name'] . '</td>
                    <td>' . date('d/m/Y', strtotime($row['loan_date'])) . '</td>
                    <td>' . date('d/m/Y', strtotime($row['expected_return_date'])) . '</td>
                    <td><span class="tag ' . $statusClass . '">' . $statusText . '</span></td>
                    <td class="has-text-centered">
                        <div class="buttons is-centered">';

                if ($row['status'] == 'active') {
                    $table .= '
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="loan_id" value="' . $row['id'] . '">
                                <button class="button is-small is-success" type="submit" name="return_book">
                                    <span class="icon is-small">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span>Devolver</span>
                                </button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="loan_id" value="' . $row['id'] . '">
                                <button class="button is-small is-info" type="submit" name="renew_loan">
                                    <span class="icon is-small">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <span>Renovar</span>
                                </button>
                            </form>';
                } else {
                    $table .= '
                            <span class="tag is-light">Sin acciones</span>';
                }

                $table .= '
                        </div>
                    </td>
                </tr>';
            }
        } else {
            $table .= '
                <tr>
                    <td colspan="7" class="has-text-centered">
                        <p class="has-text-grey">No hay préstamos registrados</p>
                    </td>
                </tr>';
        }

        $table .= '
                </tbody>
            </table>
        </div>';

        if ($total >= 1 && $numberPages > 1) {
            $table .= '<div class="mt-5">' . $this->PaginationTables($page, $numberPages, $url, 5) . '</div>';
        }

        $connection->close();
        return $table;
    }

    // Procesa la devolución de un libro
    public function ReturnBookController() {
        $loanId = $this->CleanData($_POST['loan_id']);

        if (empty($loanId)) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> ID de préstamo no válido
                      </div>';
            return $alert;
        }

        // Obtener detalles del préstamo
        $checkLoan = $this->SelectData("Unique", "loans", "id", $loanId);
        
        if (count($checkLoan) == 0) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> El préstamo no existe
                      </div>';
            return $alert;
        }

        $loan = $checkLoan[0];

        if ($loan['status'] == 'returned') {
            $alert = '<div class="notification is-warning is-light">
                        <strong>¡Atención!</strong> Este libro ya fue devuelto
                      </div>';
            return $alert;
        }

        // Actualizar estado del préstamo
        $loanData = [
            [
                "field_name" => "status",
                "field_mark" => ":Status",
                "field_value" => "returned"
            ],
            [
                "field_name" => "actual_return_date",
                "field_mark" => ":ActualReturnDate",
                "field_value" => date('Y-m-d')
            ]
        ];

        $condition = [
            "condition_field" => "id",
            "condition_mark" => ":Id",
            "condition_value" => $loanId
        ];

        $updateLoan = $this->UpdateData("loans", $loanData, $condition);

        if ($updateLoan) {
            // Aumentar cantidad disponible
            $checkBook = $this->SelectData("Unique", "books", "id", $loan['book_id']);
            $book = $checkBook[0];
            $newQuantity = $book['available_quantity'] + 1;
            $this->UpdateBookQuantity($loan['book_id'], $newQuantity);

            $alert = '<div class="notification is-success is-light">
                        <strong>¡Éxito!</strong> Libro devuelto correctamente
                      </div>';
        } else {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> No se pudo procesar la devolución
                      </div>';
        }

        return $alert;
    }

    // Renueva un préstamo
    public function RenewLoanController() {
        $loanId = $this->CleanData($_POST['loan_id']);

        if (empty($loanId)) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> ID de préstamo no válido
                      </div>';
            return $alert;
        }

        // Obtener detalles del préstamo
        $checkLoan = $this->SelectData("Unique", "loans", "id", $loanId);
        
        if (count($checkLoan) == 0) {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> El préstamo no existe
                      </div>';
            return $alert;
        }

        $loan = $checkLoan[0];

        if ($loan['status'] != 'active') {
            $alert = '<div class="notification is-warning is-light">
                        <strong>¡Atención!</strong> Solo se pueden renovar préstamos activos
                      </div>';
            return $alert;
        }

        // Sumar 14 días a la fecha de devolución esperada
        $currentReturnDate = strtotime($loan['expected_return_date']);
        $newReturnDate = date('Y-m-d', strtotime('+14 days', $currentReturnDate));

        // Actualizar préstamo
        $loanData = [
            [
                "field_name" => "expected_return_date",
                "field_mark" => ":ExpectedReturnDate",
                "field_value" => $newReturnDate
            ]
        ];

        $condition = [
            "condition_field" => "id",
            "condition_mark" => ":Id",
            "condition_value" => $loanId
        ];

        $updateLoan = $this->UpdateData("loans", $loanData, $condition);

        if ($updateLoan) {
            $alert = '<div class="notification is-success is-light">
                        <strong>¡Éxito!</strong> Préstamo renovado hasta ' . date('d/m/Y', strtotime($newReturnDate)) . '
                      </div>';
        } else {
            $alert = '<div class="notification is-danger is-light">
                        <strong>¡Error!</strong> No se pudo renovar el préstamo
                      </div>';
        }

        return $alert;
    }

    // Actualiza la cantidad disponible de un libro
    private function UpdateBookQuantity($bookId, $quantity) {
        $bookData = [
            [
                "field_name" => "available_quantity",
                "field_mark" => ":Quantity",
                "field_value" => $quantity
            ]
        ];

        $condition = [
            "condition_field" => "id",
            "condition_mark" => ":Id",
            "condition_value" => $bookId
        ];

        return $this->UpdateData("books", $bookData, $condition);
    }

    // Obtiene los libros disponibles para selección de préstamo
    public function GetAvailableBooks() {
        $connection = $this->Connect();
        $query = "SELECT b.id, b.title, b.available_quantity, a.name as author_name 
                  FROM books b 
                  INNER JOIN authors a ON b.author_id = a.id 
                  WHERE b.available_quantity > 0 
                  ORDER BY b.title ASC";
        
        $result = $connection->query($query);
        $books = [];
        
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        
        $connection->close();
        return $books;
    }

    // Obtiene todos los usuarios para selección de préstamo
    public function GetAllUsers() {
        $connection = $this->Connect();
        $query = "SELECT id, name, email FROM users ORDER BY name ASC";
        
        $result = $connection->query($query);
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        $connection->close();
        return $users;
    }
}