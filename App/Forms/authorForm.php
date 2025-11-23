<?php

require_once "../../Config/app.php";
require_once "../Views/Inc/sessionStart.php";
require_once "../../autoload.php";

use App\Controllers\AuthorController;

if (isset($_POST['authorModule'])) {
    $authorNew = new AuthorController();
    
    if($_POST['authorModule'] == "register") {
       $authorNew->RegisterAuthorController();
    }
    if($_POST['authorModule'] == "delete") {
       $authorNew->DeleteAuthorController();
    }
    if($_POST['authorModule'] == "update") {
       $authorNew->UpdateAuthorController();
    }
    
} else {
    session_destroy();
    header("Location: " . APP_URL. "login/");
}
