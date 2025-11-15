<section class="section">
    <div class="container">
        
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <div>
                        <h1 class="title is-2">👥 Gestión de Usuarios</h1>
                        <p class="subtitle is-5">Administra los usuarios de la biblioteca</p>
                    </div>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <button class="button is-primary is-medium" onclick="document.getElementById('modalAdd').classList.add('is-active')">
                        <span class="icon">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <span>Agregar Usuario</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <input class="input" type="text" placeholder="Buscar usuario por nombre o email...">
                </p>
                <p class="control">
                    <button class="button is-success">
                        🔍 Buscar
                    </button>
                </p>
            </div>
        </div>

        <div class="box">

    <?php
    
        use App\Controllers\UserController;
        $userList = new UserController();
        
        echo $userList->UserListController($url[1], 15, $url[0], "");

    ?>

    </div>
</section>

<!-- Modal Agregar Usuario -->
<div id="modalAdd" class="modal">
    <div class="modal-background" onclick="document.getElementById('modalAdd').classList.remove('is-active')"></div>
    <div class="modal-card">
        <header class="modal-card-head has-background-success">
            <p class="modal-card-title has-text-white">➕ Agregar Nuevo Usuario</p>
            <button class="delete" aria-label="close" onclick="document.getElementById('modalAdd').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
            <form action="<?php echo APP_URL; ?>App/Forms/userForm.php" method="POST" class="Form">

                <input type="hidden" name="userModule" value="register">
                
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" name="user_name" placeholder="Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Apellido</label>
                            <div class="control">
                                <input class="input" type="text" name="user_lastName" placeholder="Apellido" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" name="user_email" placeholder="correo@ejemplo.com" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Teléfono</label>
                    <div class="control has-icons-left">
                        <input class="input" type="tel" name="user_phone" placeholder="+1234567890">
                        <span class="icon is-small is-left">
                            <i class="fas fa-phone"></i>
                        </span>
                    </div>
                </div>

                <div class="field is-grouped is-grouped-right mt-5">
                    <p class="control">
                        <button class="button is-success" type="submit">
                            <span class="icon">
                                <i class="fas fa-save"></i>
                            </span>
                            <span>Guardar</span>
                        </button>
                    </p>
                </div>

            </form>
        </section>
    </div>
</div>

<?php

    $userData = new UserController();

    $id = $_GET['id'] ?? null; // o definir la variable como corresponda
    
    $data = $userData->SelectData("Unique", "users", "user_id", $id);

if($data->rowCount() == 1){
    $row = $data->fetch();
?>

<!-- Modal Editar Usuario -->
<div id="modalEditar" class="modal">
    <div class="modal-background" onclick="document.getElementById('modalEditar').classList.remove('is-active')"></div>
    <div class="modal-card">
        <header class="modal-card-head has-background-success">
            <p class="modal-card-title has-text-white">➕ Editar Usuario</p>
            <button class="delete" aria-label="close" onclick="document.getElementById('modalEditar').classList.remove('is-active')"></button>
        </header> 
        <section class="modal-card-body">
            <form action="<?php echo APP_URL; ?>App/Forms/userForm.php" method="POST" class="Form">
                <input type="hidden" name="userModule" value="update">
                <input type="hidden" id="edit_user_id" name="user_id" value="<?php echo $row['user_id']; ?>">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Nombre</label>
                            <div class="control">
                                <input class="input" type="text" id="edit_first_name" name="user_name" placeholder="Nombre" required value="<?php echo $row['first_name']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">Apellido</label>
                            <div class="control">
                                <input class="input" type="text" id="edit_last_name" name="user_lastName" placeholder="Apellido" required value="<?php echo $row['last_name']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left">
                        <input class="input" type="email" id="edit_email" name="user_email" placeholder="correo@ejemplo.com" required value="<?php echo $row['email']; ?>">
                        <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Teléfono</label>
                    <div class="control has-icons-left">
                        <input class="input" type="tel" id="edit_phone" name="user_phone" placeholder="+1234567890" value="<?php echo $row['phone']; ?>">
                        <span class="icon is-small is-left"><i class="fas fa-phone"></i></span>
                    </div>
                </div>
                <div class="field is-grouped is-grouped-right mt-5">
                    <p class="control">
                        <button class="button is-success" type="submit">
                            <span class="icon"><i class="fas fa-save"></i></span><span>Guardar</span>
                        </button>
                    </p>
                    <p class="control">
                        <button class="button is-light" type="button" onclick="document.getElementById('modalEditar').classList.remove('is-active')">
                            Cancelar
                        </button>
                    </p>
                </div>
            </form>
            <?php
            }
            ?>
        </section>
    </div>
</div>

<script>
function openEditModal(id, firstName, lastName, email, phone) {
    document.getElementById('modalEditar').classList.add('is-active');
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_first_name').value = firstName;
    document.getElementById('edit_last_name').value = lastName;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone;
}

</script>
