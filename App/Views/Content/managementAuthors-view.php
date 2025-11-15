    <section class="section">
        <div class="container">
            
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <h1 class="title is-2">👨‍💼 Gestión de Autores</h1>
                            <p class="subtitle is-5">Administra los autores de la biblioteca</p>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <button class="button is-primary is-medium" onclick="document.getElementById('modalAgregar').classList.add('is-active')">
                            <span class="icon">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>Agregar Autor</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input" type="text" placeholder="Buscar autor por nombre...">
                    </p>
                    <p class="control">
                        <button class="button is-info">
                            🔍 Buscar
                        </button>
                    </p>
                </div>
            </div>

            <div class="box">
                <div class="table-container">
                    <table class="table is-fullwidth is-striped is-hoverable">
                        <thead>
                            <tr class="has-background-info-light">
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Nacionalidad</th>
                                <th>Fecha de Nacimiento</th>
                                <th class="has-text-centered">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Gabriel</td>
                                <td>García Márquez</td>
                                <td>Colombia</td>
                                <td>06/03/1927</td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-warning">
                                            <span class="icon is-small">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            <span>Editar</span>
                                        </button>
                                        <button class="button is-small is-danger">
                                            <span class="icon is-small">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Isabel</td>
                                <td>Allende</td>
                                <td>Chile</td>
                                <td>02/08/1942</td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-warning">
                                            <span class="icon is-small">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            <span>Editar</span>
                                        </button>
                                        <button class="button is-small is-danger">
                                            <span class="icon is-small">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <!-- Modal Agregar Autor -->
    <div id="modalAgregar" class="modal">
        <div class="modal-background" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></div>
        <div class="modal-card">
            <header class="modal-card-head has-background-primary">
                <p class="modal-card-title has-text-white">➕ Agregar Nuevo Autor</p>
                <button class="delete" aria-label="close" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></button>
            </header>
            <section class="modal-card-body">
                <form action="../../Controllers/AuthorsController.php" method="POST">
                    
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control">
                            <input class="input" type="text" name="author_name" placeholder="Nombre del autor" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Apellido</label>
                        <div class="control">
                            <input class="input" type="text" name="author_lastname" placeholder="Apellido del autor" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Nacionalidad</label>
                        <div class="control">
                            <input class="input" type="text" name="author_nationality" placeholder="País de origen">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Fecha de Nacimiento</label>
                        <div class="control">
                            <input class="input" type="date" name="author_birthdate">
                        </div>
                    </div>

                    <div class="field is-grouped is-grouped-right">
                        <p class="control">
                            <button class="button is-primary" type="submit">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>Guardar</span>
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

</body>
</html>
