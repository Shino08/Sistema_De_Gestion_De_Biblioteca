<?php
# Formulario de prestamo
# maneja el procesamiento del formulario de registro de prestamos

    use App\Controllers\LoanController;

    $loanController = new LoanController();

    if (isset($_POST['book_id']) && isset($_POST['user_id'])) {
        echo $loanController->RegisterLoanController();
    }