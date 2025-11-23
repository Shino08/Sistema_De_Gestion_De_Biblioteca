<section>
    <div>
        <h1>👨‍💼 Gestión de Autores</h1>
        <p>Administra los autores de la biblioteca</p>
        
        <div>
            <a href="<?php echo APP_URL; ?>authorCreate/">➕ Agregar Autor</a>
        </div>

        <hr>

        <?php
        if(isset($_SESSION['alert'])){
            echo "<p><strong>".$_SESSION['alert']."</strong></p>";
            unset($_SESSION['alert']);
        }
        ?>

        <div>
            <form action="<?php echo APP_URL; ?>managementAuthors/1/" method="GET">
                <input type="text" name="search" placeholder="Buscar autor por nombre...">
                <button type="submit">🔍 Buscar</button>
            </form>
        </div>

        <hr>

        <div>
            <?php
            use App\Controllers\AuthorController;
            $authorList = new AuthorController();
            
            $search = isset($_GET['search']) ? $_GET['search'] : "";
            echo $authorList->AuthorListController($url[1], 15, $url[0], $search);
            ?>
        </div>

    </div>
</section>
