<section>
    <div>
        <h1>👥 Gestión de Usuarios</h1>
        <p>Administra los usuarios de la biblioteca</p>
        
        <div>
            <a href="<?php echo APP_URL; ?>userCreate/">➕ Agregar Usuario</a>
        </div>

        <hr>

        <?php
        if(isset($_SESSION['alert'])){
            echo "<p><strong>".$_SESSION['alert']."</strong></p>";
            unset($_SESSION['alert']);
        }
        ?>

        <div>
            <form action="<?php echo APP_URL; ?>userManagement/1/" method="GET">
                <input type="text" name="search" placeholder="Buscar usuario por nombre o email...">
                <button type="submit">🔍 Buscar</button>
            </form>
        </div>

        <hr>

        <div>
            <?php
            use App\Controllers\UserController;
            $userList = new UserController();
            
            $search = isset($_GET['search']) ? $_GET['search'] : "";
            echo $userList->UserListController($url[1], 15, $url[0], $search);
            ?>
        </div>

    </div>
</section>
