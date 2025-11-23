<?php
use App\Controllers\UserController;

$userData = new UserController();

$id = $url[1] ?? null;

$data = $userData->SelectData("Unique", "users", "user_id", $id);

if($data->rowCount() == 1){
    $row = $data->fetch();
?>

<section>
    <div>
        <h1>✏️ Editar Usuario</h1>
        
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
            <input type="hidden" name="userModule" value="update">
            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
            
            <div>
                <label for="user_name">Nombre:</label>
                <input type="text" id="user_name" name="user_name" value="<?php echo $row['first_name']; ?>" required>
            </div>

            <div>
                <label for="user_lastName">Apellido:</label>
                <input type="text" id="user_lastName" name="user_lastName" value="<?php echo $row['last_name']; ?>" required>
            </div>

            <div>
                <label for="user_email">Email:</label>
                <input type="email" id="user_email" name="user_email" value="<?php echo $row['email']; ?>" required>
            </div>

            <div>
                <label for="user_phone">Teléfono:</label>
                <input type="tel" id="user_phone" name="user_phone" value="<?php echo $row['phone']; ?>" required>
            </div>

            <hr>

            <div>
                <button type="submit">💾 Guardar Cambios</button>
                <a href="<?php echo APP_URL; ?>userManagement/1/">Cancelar</a>
            </div>
        </form>

    </div>
</section>

<?php
} else {
    echo "<p>Error: Usuario no encontrado</p>";
    echo "<a href='".APP_URL."userManagement/1/'>Volver a la lista</a>";
}
?>
