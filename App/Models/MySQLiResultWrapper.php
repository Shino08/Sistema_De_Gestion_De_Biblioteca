<?php

namespace App\Models;

// Envoltorio de Resultados MySQLi
// Proporciona una interfaz similar a PDO para resultados mysqli para mantener compatibilidad
class MySQLiResultWrapper {
    private $result;
    private $affectedRows;
    
    public function __construct($result, $affectedRows = 0) {
        $this->result = $result;
        $this->affectedRows = $affectedRows;
    }
    
    // Devuelve el número de filas afectadas o devueltas
    public function rowCount() {
        if ($this->result instanceof \mysqli_result) {
            return $this->result->num_rows;
        }
        return $this->affectedRows;
    }
    
    // Obtiene la siguiente fila como un array asociativo
    public function fetch() {
        if ($this->result instanceof \mysqli_result) {
            return $this->result->fetch_assoc();
        }
        return false;
    }
    
    // Obtiene todas las filas como un array de arrays asociativos
    public function fetchAll() {
        $rows = [];
        if ($this->result instanceof \mysqli_result) {
            while ($row = $this->result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
    
    // Obtiene una sola columna de la siguiente fila
    public function fetchColumn($column = 0) {
        if ($this->result instanceof \mysqli_result) {
            $row = $this->result->fetch_row();
            if ($row && isset($row[$column])) {
                return $row[$column];
            }
        }
        return false;
    }
}