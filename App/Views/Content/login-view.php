<div>
    <form action="" method="POST">
        <h2>Iniciar Sesión</h2>
        
        <div>
            <label for="email">Correo Electrónico:</label>
            <input 
                type="email" 
                id="email"
                name="email" 
                placeholder="correo@ejemplo.com"
                required
                autofocus>
        </div>

        <div>
            <label for="password">Contraseña:</label>
            <input 
                type="password"
                id="password" 
                name="password" 
                placeholder="••••••••••" 
                required>
        </div>

        <hr>

        <div>
            <button type="submit">
                <strong>Iniciar Sesión</strong>
            </button>
        </div>

        <div>
            <p>
                <strong>Credenciales por defecto:</strong><br>
                📧 Email: <code>admin@biblioteca.com</code><br>
                🔑 Password: <code>admin123</code>
            </p>
        </div>

    </form>
</div>

<?php

if(isset($_POST['email']) && isset($_POST['password'])) {
    $insLogin->StartSessionController();
}
?>