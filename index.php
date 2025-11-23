<?php

require_once "./Config/app.php";
require_once "./autoload.php";
require_once "./App/Views/Inc/sessionStart.php";

if (isset($_GET['views'])) {
    $url = explode("/", $_GET['views']);
} else {
    $url = ['login'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./App/Views/Inc/head.php"; ?>
</head>
<body>
    <?php 
    use App\Controllers\ViewsControllers;
    use App\Controllers\LoginController;

    $insLogin = new LoginController();
    
    $insViews = new ViewsControllers();
    $view = $insViews->GetViewController($url[0]);
    
    if ($view == "login" || $view == "404") {
        require_once "./App/Views/Content/".$view."-view.php";
    } else {

        #Cerrar sesion#
        if (!isset($_SESSION['id']) || !isset($_SESSION['email'])) {
            $insLogin->CloseSessionController();
            exit();
        }
        
        require_once "./App/Views/Inc/nav.php";
        require_once $view;
    }

    ?>
</body>
</html>