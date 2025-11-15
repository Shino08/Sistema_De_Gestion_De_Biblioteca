    <section class="section">
        <div class="container">
            
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <h1 class="title is-2">📖 Gestión de Libros</h1>
                            <p class="subtitle is-5">Administra el catálogo de libros</p>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <button class="button is-primary is-medium" onclick="document.getElementById('modalAgregar').classList.add('is-active')">
                            <span class="icon">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>Agregar Libro</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input" type="text" placeholder="Buscar libro por título, ISBN o autor...">
                    </p>
                    <p class="control">
                        <div class="select">
                            <select>
                                <option>Todos los géneros</option>
                                <option>Ficción</option>
                                <option>No Ficción</option>
                                <option>Ciencia</option>
                                <option>Historia</option>
                            </select>
                        </div>
                    </p>
                    <p class="control">
                        <button class="button is-warning">
                            🔍 Buscar
                        </button>
                    </p>
                </div>
            </div>

            <div class="box">
                <div class="table-container">
                    <table class="table is-fullwidth is-striped is-hoverable">
                        <thead>
                            <tr class="has-background-warning-light">
                                <th>ID</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>ISBN</th>
                                <th>Año</th>
                                <th>Género</th>
                                <th>Stock</th>
                                <th class="has-text-centered">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>Cien Años de Soledad</strong></td>
                                <td>García Márquez</td>
                                <td>978-0307474728</td>
                                <td>1967</td>
                                <td><span class="tag is-info">Ficción</span></td>
                                <td><span class="tag is-success">5</span></td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-info">
                                            <span class="icon is-small">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </button>
                                        <button class="button is-small is-warning">
                                            <span class="icon is-small">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </button>
                                        <button class="button is-small is-danger">
                                            <span class="icon is-small">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><strong>La Casa de los Espíritus</strong></td>
                                <td>Allende</td>
                                <td>978-1501117015</td>
                                <td>1982</td>
                                <td><span class="tag is-info">Ficción</span></td>
                                <td><span class="tag is-danger">0</span></td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-info">
                                            <span class="icon is-small">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </button>
                                        <button class="button is-small is-warning">
                                            <span class="icon is-small">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </button>
                                        <button class="button is-small is-danger">
                                            <span class="icon is-small">
                                                <i class="fas fa-trash"></i>
                                            </span>
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

    <!-- Modal Agregar Libro -->
    <div id="modalAgregar" class="modal">
        <div class="modal-background" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></div>
        <div class="modal-card">
            <header class="modal-card-head has-background-warning">
                <p class="modal-card-title">➕ Agregar Nuevo Libro</p>
                <button class="delete" aria-label="close" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></button>
            </header>
            <section class="modal-card-body">
                <form action="crear_libro.php" method="POST">
                    
                    <div class="field">
                        <label class="label">Título del Libro</label>
                        <div class="control">
                            <input class="input" type="text" name="titulo" placeholder="Título del libro" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Autor</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="id_autor" required>
                                    <option value="">Seleccione un autor</option>
                                    <option value="1">Gabriel García Márquez</option>
                                    <option value="2">Isabel Allende</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">ISBN</label>
                                <div class="control">
                                    <input class="input" type="text" name="isbn" placeholder="978-0000000000">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Año de Publicación</label>
                                <div class="control">
                                    <input class="input" type="number" name="anio_publicacion" placeholder="2024" min="1000" max="2099">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Género</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="genero" required>
                                            <option value="">Seleccione género</option>
                                            <option value="Ficción">Ficción</option>
                                            <option value="No Ficción">No Ficción</option>
                                            <option value="Ciencia">Ciencia</option>
                                            <option value="Historia">Historia</option>
                                            <option value="Biografía">Biografía</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Stock</label>
                                <div class="control">
                                    <input class="input" type="number" name="stock" placeholder="0" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-grouped is-grouped-right mt-5">
                        <p class="control">
                            <button class="button is-warning" type="submit">
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
