<?php
use App\Controllers\AuthorController;

$authorData = new AuthorController();

$id = $url[1] ?? null;

$data = $authorData->SelectData("Unique", "authors", "author_id", $id);

if($data->rowCount() == 1){
    $row = $data->fetch();
?>

<section>
    <div>
        <h1>✏️ Editar Autor</h1>
        
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
            <input type="hidden" name="authorModule" value="update">
            <input type="hidden" name="author_id" value="<?php echo $row['author_id']; ?>">
            
            <div>
                <label for="author_name">Nombre:</label>
                <input type="text" id="author_name" name="author_name" value="<?php echo $row['first_name']; ?>" required>
            </div>

            <div>
                <label for="author_lastName">Apellido:</label>
                <input type="text" id="author_lastName" name="author_lastName" value="<?php echo $row['last_name']; ?>" required>
            </div>

            <div>
                <label for="author_nationality">Nacionalidad:</label>
                <input type="text" id="author_nationality" name="author_nationality" value="<?php echo $row['nationality']; ?>">
            </div>

            <div>
                <label for="author_birthdate">Fecha de Nacimiento:</label>
                <input type="date" id="author_birthdate" name="author_birthdate" value="<?php echo $row['birth_date']; ?>">
            </div>

            <hr>

            <div>
                <button type="submit">💾 Guardar Cambios</button>
                <a href="<?php echo APP_URL; ?>managementAuthors/1/">Cancelar</a>
            </div>
        </form>

    </div>
</section>

<?php
} else {
    echo "<p>Error: Autor no encontrado</p>";
    echo "<a href='".APP_URL."managementAuthors/1/'>Volver a la lista</a>";
}
?>
