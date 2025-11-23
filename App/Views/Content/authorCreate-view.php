<section>
    <div>
        <h1>➕ Agregar Nuevo Autor</h1>
        
        <div>
            <a href="<?php echo APP_URL; ?>managementAuthors/1/">← Volver a la lista</a>
        </div>

        <hr>

        <?php
        if(isset($_SESSION['alert'])){
            echo "<p><strong>".$_SESSION['alert']."</strong></p>";
            unset($_SESSION['alert']);
        }
        ?>

        <form action="<?php echo APP_URL; ?>App/Forms/authorForm.php" method="POST">
            <input type="hidden" name="authorModule" value="register">
            
            <div>
                <label for="author_name">Nombre:</label>
                <input type="text" id="author_name" name="author_name" placeholder="Nombre" required>
            </div>

            <div>
                <label for="author_lastName">Apellido:</label>
                <input type="text" id="author_lastName" name="author_lastName" placeholder="Apellido" required>
            </div>

            <div>
                <label for="author_nationality">Nacionalidad:</label>
                <input type="text" id="author_nationality" name="author_nationality" placeholder="País de origen">
            </div>

            <div>
                <label for="author_birthdate">Fecha de Nacimiento:</label>
                <input type="date" id="author_birthdate" name="author_birthdate">
            </div>

            <hr>

            <div>
                <button type="submit">💾 Guardar</button>
                <a href="<?php echo APP_URL; ?>managementAuthors/1/">Cancelar</a>
            </div>
        </form>

    </div>
</section>
