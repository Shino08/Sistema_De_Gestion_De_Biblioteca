<?php

namespace App\Controllers;

use App\Models\MainModel;

# Controlador de Base de Datos
# maneja la creación de la base de datos y la inicialización de la estructura de tablas
# para el sistema de gestión de biblioteca.

class DatabaseController extends MainModel {

    # Crea la base de datos si no existe
    public function CreateDatabase() {
        // Conectar sin seleccionar base de datos
        $connection = new \mysqli(DB_SERVER, DB_USER, DB_PASS);
        
        if ($connection->connect_error) {
            return [
                "type" => "error",
                "message" => "Error de conexión: " . $connection->connect_error
            ];
        }
        
        $connection->set_charset("utf8");
        
        // Crear base de datos si no existe
        $dbName = DB_NAME;
        $query = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        
        if ($connection->query($query)) {
            $connection->close();
            return [
                "type" => "success",
                "message" => "Base de datos '$dbName' creada exitosamente"
            ];
        } else {
            $error = $connection->error;
            $connection->close();
            return [
                "type" => "error",
                "message" => "Error al crear la base de datos: " . $error
            ];
        }
    }

    // Crea todas las tablas necesarias para el sistema de biblioteca
    public function CreateTables() {
        $connection = $this->Connect();
        $errors = [];
        $success = [];

        // Crear tabla de autores
        $authorsTable = "CREATE TABLE IF NOT EXISTS authors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            nationality VARCHAR(100),
            birth_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_name (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($connection->query($authorsTable)) {
            $success[] = "Tabla 'authors' creada exitosamente";
        } else {
            $errors[] = "Error al crear tabla 'authors': " . $connection->error;
        }

        // Crear tabla de usuarios
        $usersTable = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'librarian', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_username (username),
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($connection->query($usersTable)) {
            $success[] = "Tabla 'users' creada exitosamente";
        } else {
            $errors[] = "Error al crear tabla 'users': " . $connection->error;
        }

        // Crear tabla de libros
        $booksTable = "CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            isbn VARCHAR(20) UNIQUE,
            author_id INT NOT NULL,
            quantity INT DEFAULT 1,
            available_quantity INT DEFAULT 1,
            publication_year YEAR,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            INDEX idx_title (title),
            INDEX idx_isbn (isbn),
            INDEX idx_author (author_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($connection->query($booksTable)) {
            $success[] = "Tabla 'books' creada exitosamente";
        } else {
            $errors[] = "Error al crear tabla 'books': " . $connection->error;
        }

        // Crear tabla de préstamos
        $loansTable = "CREATE TABLE IF NOT EXISTS loans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            book_id INT NOT NULL,
            user_id INT NOT NULL,
            loan_date DATE NOT NULL,
            expected_return_date DATE NOT NULL,
            actual_return_date DATE NULL,
            status ENUM('active', 'returned', 'overdue') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            INDEX idx_status (status),
            INDEX idx_loan_date (loan_date),
            INDEX idx_book (book_id),
            INDEX idx_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($connection->query($loansTable)) {
            $success[] = "Tabla 'loans' creada exitosamente";
        } else {
            $errors[] = "Error al crear tabla 'loans': " . $connection->error;
        }

        $connection->close();

        if (count($errors) > 0) {
            return [
                "type" => "error",
                "message" => "Errores al crear tablas",
                "errors" => $errors,
                "success" => $success
            ];
        }

        return [
            "type" => "success",
            "message" => "Todas las tablas fueron creadas exitosamente",
            "details" => $success
        ];
    }

    // Crea el usuario administrador por defecto
    public function CreateDefaultAdmin() {
        // Verificar si el administrador ya existe
        $checkAdmin = $this->SelectData("Unique", "users", "username", "admin");
        
        if (count($checkAdmin) > 0) {
            return [
                "type" => "info",
                "message" => "Usuario administrador ya existe"
            ];
        }

        // Crear datos del administrador por defecto
        $adminData = [
            [
                "field_name" => "name",
                "field_mark" => ":Name",
                "field_value" => "Administrador"
            ],
            [
                "field_name" => "email",
                "field_mark" => ":Email",
                "field_value" => "admin@biblioteca.com"
            ],
            [
                "field_name" => "username",
                "field_mark" => ":Username",
                "field_value" => "admin"
            ],
            [
                "field_name" => "password",
                "field_mark" => ":Password",
                "field_value" => password_hash("admin123", PASSWORD_BCRYPT)
            ],
            [
                "field_name" => "role",
                "field_mark" => ":Role",
                "field_value" => "admin"
            ]
        ];

        $result = $this->SaveData("users", $adminData);

        if ($result) {
            return [
                "type" => "success",
                "message" => "Usuario administrador creado exitosamente (usuario: admin, contraseña: admin123)"
            ];
        } else {
            return [
                "type" => "error",
                "message" => "Error al crear usuario administrador"
            ];
        }
    }

    // Inicializa el sistema completo de base de datos
    public function InitializeSystem() {
        $messages = [];
        
        // Paso 1: Crear base de datos
        $dbResult = $this->CreateDatabase();
        $messages[] = $this->formatMessage($dbResult);

        // Paso 2: Crear tablas
        $tablesResult = $this->CreateTables();
        $messages[] = $this->formatMessage($tablesResult);

        // Paso 3: Crear administrador por defecto
        $adminResult = $this->CreateDefaultAdmin();
        $messages[] = $this->formatMessage($adminResult);

        return implode("", $messages);
    }

    // Formatea el mensaje de respuesta como HTML
    private function formatMessage($result) {
        $type = $result["type"];
        $message = $result["message"];
        
        $class = "";
        $icon = "";
        
        switch ($type) {
            case "success":
                $class = "notification is-success";
                $icon = "✓";
                break;
            case "error":
                $class = "notification is-danger";
                $icon = "✗";
                break;
            case "info":
                $class = "notification is-info";
                $icon = "ℹ";
                break;
            default:
                $class = "notification";
                $icon = "•";
        }

        $html = '<div class="' . $class . '">';
        $html .= '<strong>' . $icon . ' ' . $message . '</strong>';
        
        if (isset($result["details"]) && is_array($result["details"])) {
            $html .= '<ul class="mt-2">';
            foreach ($result["details"] as $detail) {
                $html .= '<li>' . $detail . '</li>';
            }
            $html .= '</ul>';
        }
        
        if (isset($result["errors"]) && is_array($result["errors"])) {
            $html .= '<ul class="mt-2">';
            foreach ($result["errors"] as $error) {
                $html .= '<li>' . $error . '</li>';
            }
            $html .= '</ul>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}