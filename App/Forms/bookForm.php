<?php

require_once "../../Config/app.php";
require_once "../Views/Inc/sessionStart.php";
require_once "../../autoload.php";

use App\Controllers\BookController;

if (isset($_POST['bookModule'])) {
    $bookNew = new BookController();
    
    if($_POST['bookModule'] == "register") {
       $bookNew->RegisterBookController();
    }
    if($_POST['bookModule'] == "delete") {
       $bookNew->DeleteBookController();
    }
    if($_POST['bookModule'] == "update") {
       $bookNew->UpdateBookController();
    }
    
} else {
    session_destroy();
    header("Location: " . APP_URL. "login/");
}
