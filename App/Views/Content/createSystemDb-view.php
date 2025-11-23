<?php
    use App\Controllers\DatabaseController;
    
    $dbController = new DatabaseController();
    $result = "";
    
    if (isset($_POST['create_db'])) {
        $result = $dbController->InitializeSystem();
    }
?>

    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    
                    <div class="box">
                        <h1 class="title is-2 has-text-centered">🗄️ Crear Base de Datos</h1>
                        <p class="subtitle is-5 has-text-centered has-text-grey">
                            Configura la base de datos del sistema de biblioteca
                        </p>
                        <hr>

                        <?php if ($result != ""): ?>
                            <div class="mb-5">
                                <?= $result ?>
                            </div>
                            <div class="has-text-centered">
                                <a href="index.php?views=login" class="button is-primary is-large">
                                    <span class="icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <span>Ir al Login</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="notification is-info is-light">
                                    <p class="has-text-weight-semibold">ℹ️ Información:</p>
                                    <ul class="ml-5">
                                        <li>Se creará la base de datos: <strong>library_db</strong></li>
                                        <li>Se generarán las tablas: <strong>authors, users, books, loans</strong></li>
                                        <li>Se creará un usuario administrador por defecto</li>
                                        <li>El proceso es automático y seguro</li>
                                    </ul>
                                </div>

                                <div class="notification is-warning is-light">
                                    <p class="has-text-weight-semibold">⚠️ Credenciales del Administrador:</p>
                                    <ul class="ml-5">
                                        <li><strong>Usuario:</strong> admin</li>
                                        <li><strong>Contraseña:</strong> admin123</li>
                                    </ul>
                                    <p class="mt-3"><em>Por favor, cambia la contraseña después del primer inicio de sesión.</em></p>
                                </div>

                                <div class="field is-grouped is-grouped-centered mt-5">
                                    <p class="control">
                                        <button class="button is-primary is-large" type="submit" name="create_db">
                                            <span class="icon">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span>Crear Base de Datos</span>
                                        </button>
                                    </p>
                                    <p class="control">
                                        <a href="index.php" class="button is-light is-large">
                                            <span class="icon">
                                                <i class="fas fa-times"></i>
                                            </span>
                                            <span>Cancelar</span>
                                        </a>
                                    </p>
                                </div>
                            </form>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>
    </section>

