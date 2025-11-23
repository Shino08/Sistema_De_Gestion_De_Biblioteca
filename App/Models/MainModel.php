<?php

    namespace App\Models;
    
    use App\Models\MySQLiResultWrapper;

    /**
     * Main Model - Base model with core database operations using mysqli
     * 
     * This class provides the fundamental database connection and CRUD operations
     * for the library management system using mysqli instead of PDO.
     */
    class MainModel {

        private $server = "localhost";
        private $database = "library_db";
        private $user = "root";
        private $pass = "";

        /**
         * Establishes connection to the database using mysqli
         * 
         * @return mysqli Database connection object
         */
        protected function Connect(){
            $connection = new \mysqli($this->server, $this->user, $this->pass, $this->database);
            
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }
            
            $connection->set_charset("utf8");
            return $connection;
        }

        /**
         * Executes a simple query without parameters
         * 
         * @param string $query SQL query to execute
         * @return MySQLiResultWrapper Query result wrapper
         */
        protected function ExecuteQuery($query) {
            $connection = $this->Connect();
            $result = $connection->query($query);
            $affectedRows = $connection->affected_rows;
            $connection->close();
            return new MySQLiResultWrapper($result, $affectedRows);
        }

        /**
         * Cleans and sanitizes input data to prevent SQL injection and XSS
         * 
         * @param string $data Data to clean
         * @return string Cleaned data
         */
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

        /**
         * Verifies data against a regex pattern
         * 
         * @param string $filter Regex pattern
         * @param string $data Data to verify
         * @return bool True if data does NOT match pattern, false if it matches
         */
        protected function VerifyData($filter, $data){
            if (preg_match("/^".$filter."$/", $data)) {
                return false;
            } else {
                return true;
            }
        }

        /**
         * Inserts data into a table using prepared statements
         * 
         * @param string $table Table name
         * @param array $data Array of field data with keys: field_name, field_mark, field_value
         * @return bool True on success, false on failure
         */
        protected function SaveData($table, $data){
            $connection = $this->Connect();
            
            $query = "INSERT INTO $table (";
            $fields = [];
            $marks = [];
            $values = [];
            $types = "";

            foreach ($data as $key) {
                $fields[] = $key["field_name"];
                $marks[] = "?";
                $values[] = $key["field_value"];
                $types .= "s"; // Assuming all strings, can be enhanced
            }

            $query .= implode(", ", $fields) . ") VALUES (" . implode(", ", $marks) . ")";

            $stmt = $connection->prepare($query);
            
            if (!$stmt) {
                $connection->close();
                return false;
            }

            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            
            $stmt->close();
            $connection->close();
            
            return new MySQLiResultWrapper(null, $affectedRows);
        }

        /**
         * Selects data from a table
         * 
         * @param string $type "Unique" for single row, "Normal" for multiple rows
         * @param string $table Table name
         * @param string $field Field name for WHERE clause or SELECT clause
         * @param mixed $id Value for WHERE clause
         * @return array|null Array of results or null on failure
         */
        public function SelectData($type, $table, $field, $id = null){
            $connection = $this->Connect();
            
            $type = $this->CleanData($type);
            $table = $this->CleanData($table);
            $field = $this->CleanData($field);

            if ($type == "Unique") {
                $id = $this->CleanData($id);
                $query = "SELECT * FROM $table WHERE $field = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", $id);
            } elseif($type == "Normal") {
                $query = "SELECT $field FROM $table";
                $stmt = $connection->prepare($query);
            } else {
                $connection->close();
                return null;
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            $stmt->close();
            $connection->close();
            
            return $data;
        }

        /**
         * Updates data in a table using prepared statements
         * 
         * @param string $table Table name
         * @param array $data Array of field data
         * @param array $condition Condition for WHERE clause
         * @return bool True on success, false on failure
         */
        protected function UpdateData($table, $data, $condition){
            $connection = $this->Connect();
            
            $query = "UPDATE $table SET ";
            $sets = [];
            $values = [];
            $types = "";

            foreach ($data as $key) {
                $sets[] = $key["field_name"] . " = ?";
                $values[] = $key["field_value"];
                $types .= "s";
            }

            $query .= implode(", ", $sets);
            $query .= " WHERE " . $condition["condition_field"] . " = ?";
            
            $values[] = $condition["condition_value"];
            $types .= "s";

            $stmt = $connection->prepare($query);
            
            if (!$stmt) {
                $connection->close();
                return false;
            }

            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            
            $stmt->close();
            $connection->close();
            
            return new MySQLiResultWrapper(null, $affectedRows);
        }

        /**
         * Deletes data from a table
         * 
         * @param string $table Table name
         * @param string $field Field name for WHERE clause
         * @param mixed $id Value for WHERE clause
         * @return bool True on success, false on failure
         */
        protected function DeleteData($table, $field, $id){
            $connection = $this->Connect();
            
            $query = "DELETE FROM $table WHERE $field = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            
            $stmt->close();
            $connection->close();
            
            return new MySQLiResultWrapper(null, $affectedRows);
        }

        /**
         * Generates HTML pagination for tables
         * 
         * @param int $page Current page number
         * @param int $numberPage Total number of pages
         * @param string $url Base URL for pagination links
         * @param int $buttons Number of page buttons to show
         * @return string HTML pagination markup
         */
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