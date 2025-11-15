<div class="card-content main-container">
    <form action="" method="POST" class="login">
        
        <!-- Campo Email -->
        <div class="field">
            <label class="label">
                <span class="icon-text">
                    <span class="icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <span>Correo Electrónico</span>
                </span>
            </label>
            <div class="control has-icons-left">
                <input 
                    class="input is-rounded" 
                    type="email" 
                    name="email" 
                    placeholder="correo@ejemplo.com"
                    required
                    autofocus>
                <span class="icon is-small is-left">
                    <i class="fas fa-at"></i>
                </span>
            </div>
        </div>

        <!-- Campo Password -->
        <div class="field">
            <label class="label">
                <span class="icon-text">
                    <span class="icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <span>Contraseña</span>
                </span>
            </label>
            <div class="control has-icons-left">
                <input 
                    class="input is-rounded" 
                    type="password" 
                    name="password" 
                    placeholder="••••••••••" 
                    required>
                <span class="icon is-small is-left">
                    <i class="fas fa-shield-alt"></i>
                </span>
            </div>
        </div>

        <!-- Separador -->
        <hr>

        <!-- Botón de login -->
        <div class="field">
            <div class="control">
                <button class="button is-primary is-fullwidth is-rounded is-medium" type="submit">
                    <span class="icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    <span><strong>Iniciar Sesión</strong></span>
                </button>
            </div>
        </div>

        <!-- Mensaje informativo -->
        <div class="message is-small is-info mt-4">
            <div class="message-body">
                <p class="is-size-7">
                    <strong>Credenciales por defecto:</strong><br>
                    📧 Email: <code>admin@biblioteca.com</code><br>
                    🔑 Password: <code>admin123</code>
                </p>
            </div>
        </div>

    </form>
</div>

<?php

if(isset($_POST['email']) && isset($_POST['password'])) {
    $insLogin->StartSessionController();
}
?>