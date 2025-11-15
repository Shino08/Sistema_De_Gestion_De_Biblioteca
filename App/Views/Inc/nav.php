<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo APP_URL; ?>dashboard">
            <?php echo APP_NAME; ?>
        </a>
        <div class="navbar-burger" data-target="navbarExampleTransparentExample">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbarExampleTransparentExample" class="navbar-menu">
 
        <div class="navbar-start">
            <a class="navbar-item" href="<?php echo APP_URL; ?>dashboard">
                <- Regresar
            </a>


        </div>

        <div class="navbar-end">
            <div class="navbar-item has-dropdown is-hoverable">
                    <?php if(isset($_SESSION['email'])): ?>
                    <a class="navbar-link">
                        <?php echo $_SESSION['email']; ?>
                    </a>
                    <?php endif; ?>

                <div class="navbar-dropdown is-boxed">

                    <hr class="navbar-divider">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>logOut/">
                        Salir
                    </a>

                </div>
            </div>
        </div>

    </div>
</nav>