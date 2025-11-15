<?php

    namespace App\Models;
    use \PDO;

    # Modelo Base, contiene las funciones principales para la conexión a la base de datos y las consultas CRUD #
class MainModel{

    private $server = "localhost";
    private $database = "library_db";
    private $user = "root";
    private $pass = "";

    # Metodo para la conexion a la base de datos #

    protected function Connect(){
        $connection = new PDO("mysql:host=".$this->server.";dbname=".$this->database, $this->user, $this->pass);
        $connection->exec(("SET CHARACTER SET utf8"));
        return $connection;
    }

    # Metodo para la ejecucion de consultas #

    protected function ExecuteQuery($query) {
        $sql = $this->Connect()->prepare($query);
        $sql->execute();
        return $sql;
    }

    # Metodo para la limpieza de datos #

    public function CleanData($data){
        $words = ["<script>", "</script>", "<script src>", "<script type=>", "SELECT * FROM", "INSERT INTO", "DELETE FROM", "DROP TABLE", "DROP DATABASE", "TRUNCATE TABLE", "SHOW TABLES", "SHOW DATABASES", "<?php", "?>", "--", "^", "<", ">", "==", "=", ";", "::"];
        
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        foreach ($words as $word) {
            $data = str_ireplace($word, "", $data);
        }
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    # Metodo para la verificacion de datos usando expresiones regulares o filtros #

    protected function VerifyData($filter, $data){
        if (preg_match("/^".$filter."$/", $data)) {
            return false;
        } else {
            return true;
        }
    }

    # Metodo para la insercion de datos en la base de datos, de cualquier tabla #

    protected function SaveData($table, $data){
        $query = "INSERT INTO $table (";

        $C=0;
        foreach ($data as $key) {
            if ($C>=1) { $query.=","; }
            $query.=$key["field_name"];
            $C++;
        }

        $query.=") VALUES (";

        $C=0;
        foreach ($data as $key) {
            if ($C>=1) { $query.=","; }
            $query.=$key["field_mark"];
            $C++;
        }

        $query.=")";

        $sql = $this->Connect()->prepare($query);

        foreach ($data as $key) {
            $sql->bindParam($key["field_mark"], $key["field_value"]);
        }

        $sql->execute();

        return $sql;
    }

    # Metodo para la seleccion de datos en la base de datos #

    public function SelectData($type, $table, $field, $id){
        $type = $this->CleanData($type);
        $table = $this->CleanData($table);
        $field = $this->CleanData($field);
        $id = $this->CleanData($id);

        if ($type == "Unique") {
            $sql = $this->Connect()->prepare("SELECT * FROM $table WHERE $field=:ID");
            $sql->bindParam(":ID", $id);
            $sql->execute();
            return $sql;
        } elseif($type == "Normal") {
            $sql = $this->Connect()->prepare("SELECT $field FROM $table");
        }
        $sql->execute();
        return $sql;
        
    }

    # Metodo para la actualizacion de datos en la base de datos #
    protected function UpdateData($table, $data, $condition){
        $query = "UPDATE $table SET ";

        $C=0;
        foreach ($data as $key) {
            if ($C>=1) { $query.=" , "; }
            $query.=$key["field_name"]."=".$key["field_mark"];
            $C++;
        }

        $query.=" WHERE " .$condition["condition_field"] ."=".$condition["condition_mark"];

        $sql = $this->Connect()->prepare($query);

        foreach ($data as $key) {
            $sql->bindParam($key["field_mark"], $key["field_value"]);
        }

        $sql->bindParam($condition["condition_mark"], $condition["condition_value"]); 

        $sql->execute();

        return $sql;
    }

    protected function DeleteData($table, $field, $id){
        $sql = $this->Connect()->prepare("DELETE FROM $table WHERE $field=:id");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }

    protected function PaginationTables($page, $numberPage, $url, $buttons){
        $table = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';
        
        if ($page<=1) {
            $table.='
            <a class="pagination-previous is-disabled" disabled>Anterior</a>

            <ul class="pagination-list">
            ';
        } else {
            $table.='
            <a class="pagination-previous" href="'.$url.($page-1).'/">Anterior</a>
            <ul class="pagination-list">
            <li>
                <a class="pagination-link" href="'.$url.'1/">1</a>
            </li>
            <li><span class="pagination-ellipsis">&hellip;</span></li>
            ';
        }

        $ci = 0;
        for ($i=$page; $i<=$numberPage; $i++){

            if ($ci >= $buttons) {
                break;
            }

            if ($page == $i) {
                $table.='<li>
                <a class="pagination-link is-current" href="'.$url.$i.'/">'.$i.'</a>
            </li>';
            } else {
                $table.='<li>
                <a class="pagination-link" href="'.$url.$i.'/">'.$i.'</a>
            </li>';
            }

            $ci++;
             
        }

        if ($page == $numberPage) {
            $table.='
            </ul>
            <a class="pagination-next is-disabled" disabled>Siguiente</a>
            </nav>
            ';
        } else {
            $table.='
            <li><span class="pagination-ellipsis">&hellip;</span></li>
            <li>
                <a class="pagination-link" href="'.$url.$numberPage.'/">'.$numberPage.'</a>
            </li>
            </ul>
            <a class="pagination-next" href="'.$url.($page+1).'/">Siguiente</a>
            </nav>
            ';
        }
        
        $table .= "</ul></nav>";
        
        return $table;
    }
}