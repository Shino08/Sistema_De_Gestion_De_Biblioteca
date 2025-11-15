<?php

require_once "../../Config/app.php";
require_once "../Views/Inc/sessionStart.php";
require_once "../../autoload.php";

use App\Controllers\UserController;

if (isset($_POST['userModule'])) {
    $userNew = new UserController();
    
    if($_POST['userModule'] == "register") {
       echo $userNew->RegisterUserController();
       
    }
    if($_POST['userModule'] == "delete") {
       echo $userNew->DeleteUserController();
    }
    if($_POST['userModule'] == "update") {
       echo $userNew->UpdateUserController();
    }
    
} else {
    session_destroy();
    header("Location: " . APP_URL. "login/");
}
