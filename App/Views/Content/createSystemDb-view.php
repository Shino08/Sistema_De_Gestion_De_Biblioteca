    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half">
                    
                    <div class="box">
                        <h1 class="title is-2 has-text-centered">Crear Base de Datos</h1>
                        <p class="subtitle is-5 has-text-centered has-text-grey">
                            Configura la base de datos del sistema de biblioteca
                        </p>
                        <hr>

                        <form action="crear_db.php" method="POST">
                            <div class="field">
                                <label class="label">Nombre de la Base de Datos</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium" type="text" name="nameDataBase" 
                                           placeholder="Ej: biblioteca_db" required 
                                           pattern="[a-zA-Z0-9_]+">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-database"></i>
                                    </span>
                                </div>
                                <p class="help">Solo letras, números y guiones bajos (_)</p>
                            </div>

                            <div class="notification is-info is-light">
                                <p class="has-text-weight-semibold">ℹ️ Información:</p>
                                <ul class="ml-5">
                                    <li>Se creará la base de datos con el nombre ya especificado</li>
                                    <li>Se generarán las tablas: autores, usuarios, libros y préstamos</li>
                                    <li>El proceso es automático y seguro</li>
                                </ul>
                            </div>

                            <div class="field is-grouped is-grouped-centered mt-5">
                                <p class="control">
                                    <button class="button is-primary is-large" type="submit">
                                        <span class="icon">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span>Crear Base de Datos</span>
                                    </button>
                                </p>
                                <p class="control">
                                    <a href="../../../index.php" class="button is-light is-large">
                                        <span class="icon">
                                            <i class="fas fa-times"></i>
                                        </span>
                                        <span>Cancelar</span>
                                    </a>
                                </p>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>
</body>
</html>
