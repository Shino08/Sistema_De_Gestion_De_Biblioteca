    <section class="section">
        <div class="container">
            
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
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input" type="text" placeholder="Buscar por usuario o libro...">
                    </p>
                    <p class="control">
                        <button class="button is-danger">
                            🔍 Buscar
                        </button>
                    </p>
                </div>
            </div>

            <div class="box">
                <div class="table-container">
                    <table class="table is-fullwidth is-striped is-hoverable">
                        <thead>
                            <tr class="has-background-danger-light">
                                <th>ID</th>
                                <th>Libro</th>
                                <th>Usuario</th>
                                <th>Fecha Préstamo</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                                <th class="has-text-centered">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>Cien Años de Soledad</strong></td>
                                <td>Juan Pérez</td>
                                <td>10/11/2025</td>
                                <td>24/11/2025</td>
                                <td><span class="tag is-success">Activo</span></td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-success">
                                            <span class="icon is-small">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span>Devolver</span>
                                        </button>
                                        <button class="button is-small is-info">
                                            <span class="icon is-small">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span>Renovar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><strong>La Casa de los Espíritus</strong></td>
                                <td>María González</td>
                                <td>01/11/2025</td>
                                <td>15/11/2025</td>
                                <td><span class="tag is-danger">Vencido</span></td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-success">
                                            <span class="icon is-small">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span>Devolver</span>
                                        </button>
                                        <button class="button is-small is-warning">
                                            <span class="icon is-small">
                                                <i class="fas fa-exclamation"></i>
                                            </span>
                                            <span>Notificar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><strong>Don Quijote</strong></td>
                                <td>Carlos Ruiz</td>
                                <td>05/11/2025</td>
                                <td>08/11/2025</td>
                                <td><span class="tag is-light">Devuelto</span></td>
                                <td class="has-text-centered">
                                    <div class="buttons is-centered">
                                        <button class="button is-small is-info">
                                            <span class="icon is-small">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                            <span>Ver Detalle</span>
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

    <!-- Modal Nuevo Préstamo -->
    <div id="modalAgregar" class="modal">
        <div class="modal-background" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></div>
        <div class="modal-card">
            <header class="modal-card-head has-background-danger">
                <p class="modal-card-title has-text-white">➕ Registrar Nuevo Préstamo</p>
                <button class="delete" aria-label="close" onclick="document.getElementById('modalAgregar').classList.remove('is-active')"></button>
            </header>
            <section class="modal-card-body">
                <form action="crear_prestamo.php" method="POST">
                    
                    <div class="field">
                        <label class="label">Usuario</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="id_usuario" required>
                                    <option value="">Seleccione un usuario</option>
                                    <option value="1">Juan Pérez - juan.perez@email.com</option>
                                    <option value="2">María González - maria.gonzalez@email.com</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Libro</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="id_libro" required>
                                    <option value="">Seleccione un libro</option>
                                    <option value="1">Cien Años de Soledad (5 disponibles)</option>
                                    <option value="2" disabled>La Casa de los Espíritus (0 disponibles)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Fecha de Préstamo</label>
                                <div class="control">
                                    <input class="input" type="date" name="fecha_prestamo" required>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Fecha de Devolución</label>
                                <div class="control">
                                    <input class="input" type="date" name="fecha_devolucion_esperada" required>
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

</body>
</html>
