<section>
    <div>
        <h1>➕ Agregar Nuevo Usuario</h1>
        
        <div>
            <a href="<?php echo APP_URL; ?>userManagement/1/">← Volver a la lista</a>
        </div>

        <hr>

        <?php
        if(isset($_SESSION['alert'])){
            echo "<p><strong>".$_SESSION['alert']."</strong></p>";
            unset($_SESSION['alert']);
        }
        ?>

        <form action="<?php echo APP_URL; ?>App/Forms/userForm.php" method="POST">
            <input type="hidden" name="userModule" value="register">
            
            <div>
                <label for="user_name">Nombre:</label>
                <input type="text" id="user_name" name="user_name" placeholder="Nombre" required>
            </div>

            <div>
                <label for="user_lastName">Apellido:</label>
                <input type="text" id="user_lastName" name="user_lastName" placeholder="Apellido" required>
            </div>

            <div>
                <label for="user_email">Email:</label>
                <input type="email" id="user_email" name="user_email" placeholder="correo@ejemplo.com" required>
            </div>

            <div>
                <label for="user_phone">Teléfono:</label>
                <input type="tel" id="user_phone" name="user_phone" placeholder="+1234567890" required>
            </div>

            <hr>

            <div>
                <button type="submit">💾 Guardar</button>
                <a href="<?php echo APP_URL; ?>userManagement/1/">Cancelar</a>
            </div>
        </form>

    </div>
</section>
