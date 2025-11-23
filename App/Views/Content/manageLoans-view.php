<?php
    use App\Controllers\LoanController;

    $loanController = new LoanController();
    $alert = "";

    // Handle form submissions
    if (isset($_POST['book_id']) && isset($_POST['user_id'])) {
        $alert = $loanController->RegisterLoanController();
    }

    if (isset($_POST['return_book'])) {
        $alert = $loanController->ReturnBookController();
    }

    if (isset($_POST['renew_loan'])) {
        $alert = $loanController->RenewLoanController();
    }

    // Get available books and users for the form
    $availableBooks = $loanController->GetAvailableBooks();
    $allUsers = $loanController->GetAllUsers();
?>

    <section class="section">
        <div class="container">
            
            <?php if ($alert != ""): ?>
                <div class="mb-4">
                    <?= $alert ?>
                </div>
            <?php endif; ?>

            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <h1 class="title is-2">🔄 Gestión de Préstamos</h1>
                            <p class="subtitle is-5">Administra los préstamos de libros</p>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <button class="button is-primary is-medium" onclick="document.getElementById('modalAgregar').classList.add('is-active')">
                            <span class="icon">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>Nuevo Préstamo</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="box">
                <form method="GET">
                    <input type="hidden" name="views" value="manageLoans">
                    <div class="field is-grouped">
                        <p class="control is-expanded">
                            <input class="input" type="text" name="search" placeholder="Buscar por usuario o libro..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                        </p>
                        <p class="control">
                            <button class="button is-danger" type="submit">
                                🔍 Buscar
                            </button>
                        </p>
                    </div>
                </form>
            </div>

            <div class="box">
                <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $search = isset($_GET['search']) ? $_GET['search'] : "";
                    echo $loanController->LoanListController($page, 10, "index.php?views=manageLoans&page=", $search);
                ?>
            </div>

        </div>
    </section>

    <!-- Modal Nuevo Préstamo -->
    <div id="modalAgregar" class="modal">
        <div class="modal-background" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></div>
        <div class="modal-card">
            <header class="modal-card-head has-background-danger">
                <p class="modal-card-title has-text-white">➕ Registrar Nuevo Préstamo</p>
                <button class="delete" aria-label="close" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></button>
            </header>
            <section class="modal-card-body">
                <form method="POST">
                    
                    <div class="field">
                        <label class="label">Usuario</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="user_id" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php foreach ($allUsers as $user): ?>
                                        <option value="<?= $user['id'] ?>"><?= $user['name'] ?> - <?= $user['email'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Libro</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="book_id" required>
                                    <option value="">Seleccione un libro</option>
                                    <?php foreach ($availableBooks as $book): ?>
                                        <option value="<?= $book['id'] ?>">
                                            <?= $book['title'] ?> - <?= $book['author_name'] ?> (<?= $book['available_quantity'] ?> disponibles)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Fecha de Préstamo</label>
                                <div class="control">
                                    <input class="input" type="date" name="loan_date" value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Fecha de Devolución</label>
                                <div class="control">
                                    <input class="input" type="date" name="expected_return_date" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification is-info is-light">
                        <p><strong>📋 Información:</strong></p>
                        <ul class="ml-4">
                            <li>El plazo máximo de préstamo es de 14 días</li>
                            <li>Verificar disponibilidad del libro antes de confirmar</li>
                        </ul>
                    </div>

                    <div class="field is-grouped is-grouped-right mt-5">
                        <p class="control">
                            <button class="button is-danger" type="submit">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>Registrar Préstamo</span>
                            </button>
                        </p>
                        <p class="control">
                            <button class="button is-light" type="button" onclick="document.getElementById('modalAgregar').classList.remove('is-active')">
                                Cancelar
                            </button>
                        </p>
                    </div>

                </form>
            </section>
        </div>
    </div>

