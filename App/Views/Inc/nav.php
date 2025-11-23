<nav>
    <div>
        <a href="<?php echo APP_URL; ?>dashboard">
            <strong><?php echo APP_NAME; ?></strong>
        </a>
    </div>

    <ul>
        <li><a href="<?php echo APP_URL; ?>dashboard">← Regresar</a></li>
        
        <?php if(isset($_SESSION['email'])): ?>
        <li>
            Usuario: <?php echo $_SESSION['email']; ?>
            <ul>
                <li><a href="<?php echo APP_URL; ?>logOut/">Salir</a></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<hr>