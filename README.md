# 📚 Sistema de Gestión de Biblioteca

Sistema completo de gestión de biblioteca desarrollado en PHP con arquitectura MVC, utilizando mysqli para la gestión de base de datos MySQL.

## 🎯 Características Principales

- ✅ **Auto-creación de base de datos** - El sistema crea automáticamente toda la estructura de base de datos
- ✅ **CRUD completo de Autores** - Gestión completa de autores de libros
- ✅ **CRUD completo de Libros** - Administración de catálogo de libros con control de stock
- ✅ **CRUD completo de Usuarios** - Gestión de usuarios del sistema
- ✅ **Gestión de Préstamos** - Sistema completo de préstamos con:
  - Registro de préstamos con validación de disponibilidad
  - Devolución de libros
  - Renovación de préstamos (14 días adicionales)
  - Control automático de cantidad disponible
  - Estados: Activo, Vencido, Devuelto
- ✅ **Búsqueda y Paginación** - En todas las entidades
- ✅ **Seguridad** - Prepared statements, validación de datos, password hashing

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL 5.7+ / MariaDB
- **Arquitectura:** MVC (Model-View-Controller)
- **Conexión BD:** mysqli (prepared statements)
- **Frontend:** HTML puro (sin frameworks CSS/JS)
- **Seguridad:** password_hash/password_verify, sanitización de datos

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Servidor web (Apache/Nginx)
- Extensión mysqli habilitada en PHP

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/Shino08/Sistema_De_Gestion_De_Biblioteca.git
cd Sistema_De_Gestion_De_Biblioteca
```

### 2. Configurar el servidor

Coloca el proyecto en la carpeta de tu servidor web:

- **XAMPP/LAMPP:** `/opt/lampp/htdocs/` o `C:\xampp\htdocs\`
- **WAMP:** `C:\wamp64\www\`
- **MAMP:** `/Applications/MAMP/htdocs/`

### 3. Configurar la base de datos

Edita el archivo `Config/server.php` con tus credenciales:

```php
const DB_SERVER = "localhost";
const DB_NAME = "library_db";
const DB_USER = "root";
const DB_PASS = "";
```

### 4. Crear la base de datos

1. Accede a: `http://localhost/Sistema_De_Gestion_De_Biblioteca/index.php?views=createSystemDb`
2. Haz clic en **"Crear Base de Datos"**
3. El sistema creará automáticamente:
   - Base de datos `library_db`
   - Tablas: `authors`, `users`, `books`, `loans`
   - Usuario administrador por defecto

### 5. Iniciar sesión

Usa las credenciales por defecto:

- **Usuario:** `admin`
- **Contraseña:** `admin123`

> ⚠️ **Importante:** Cambia la contraseña del administrador después del primer inicio de sesión.

## 📁 Estructura del Proyecto

```
Sistema_De_Gestion_De_Biblioteca/
├── App/
│   ├── Controllers/          # Controladores MVC
│   │   ├── AuthorController.php
│   │   ├── BookController.php
│   │   ├── DatabaseController.php
│   │   ├── LoanController.php
│   │   ├── LoginController.php
│   │   └── UserController.php
│   ├── Forms/               # Procesadores de formularios
│   │   ├── authorForm.php
│   │   ├── bookForm.php
│   │   ├── loanForm.php
│   │   └── userForm.php
│   ├── Models/              # Modelos de datos
│   │   ├── MainModel.php
│   │   ├── MySQLiResultWrapper.php
│   │   └── ViewsModel.php
│   └── Views/               # Vistas HTML
│       ├── Content/         # Vistas de contenido
│       └── Inc/             # Componentes reutilizables
├── Config/                  # Archivos de configuración
│   ├── app.php
│   └── server.php
├── autoload.php            # Autoloader de clases
├── index.php               # Punto de entrada
└── README.md
```

## 🗄️ Estructura de Base de Datos

### Tabla: `authors`

```sql
- id (INT, PK, AUTO_INCREMENT)
- name (VARCHAR 255)
- nationality (VARCHAR 100)
- birth_date (DATE)
- created_at (TIMESTAMP)
```

### Tabla: `users`

```sql
- id (INT, PK, AUTO_INCREMENT)
- name (VARCHAR 255)
- email (VARCHAR 255, UNIQUE)
- username (VARCHAR 100, UNIQUE)
- password (VARCHAR 255)
- role (ENUM: 'admin', 'librarian', 'user')
- created_at (TIMESTAMP)
```

### Tabla: `books`

```sql
- id (INT, PK, AUTO_INCREMENT)
- title (VARCHAR 255)
- isbn (VARCHAR 20, UNIQUE)
- author_id (INT, FK → authors.id)
- quantity (INT)
- available_quantity (INT)
- publication_year (YEAR)
- created_at (TIMESTAMP)
```

### Tabla: `loans`

```sql
- id (INT, PK, AUTO_INCREMENT)
- book_id (INT, FK → books.id)
- user_id (INT, FK → users.id)
- loan_date (DATE)
- expected_return_date (DATE)
- actual_return_date (DATE, NULL)
- status (ENUM: 'active', 'returned', 'overdue')
- created_at (TIMESTAMP)
```

## 📖 Uso del Sistema

### Gestión de Autores

1. **Crear Autor:**

   - Navega a "Gestión de Autores"
   - Clic en "Nuevo Autor"
   - Completa: Nombre, Apellido, Nacionalidad, Fecha de nacimiento
   - Guardar

2. **Editar/Eliminar:**
   - Usa los botones en la lista de autores
   - No se puede eliminar un autor con libros asociados

### Gestión de Libros

1. **Crear Libro:**

   - Navega a "Gestión de Libros"
   - Clic en "Nuevo Libro"
   - Completa: Título, Autor, ISBN, Año, Género, Stock
   - El sistema gestiona automáticamente la cantidad disponible

2. **Editar/Eliminar:**
   - Usa los botones en la lista de libros

### Gestión de Préstamos

1. **Registrar Préstamo:**

   - Navega a "Gestión de Préstamos"
   - Clic en "Nuevo Préstamo"
   - Selecciona usuario y libro disponible
   - Define fechas (por defecto: hoy + 14 días)
   - La cantidad disponible se reduce automáticamente

2. **Devolver Libro:**

   - Clic en "Devolver" en un préstamo activo
   - La cantidad disponible aumenta automáticamente

3. **Renovar Préstamo:**

   - Clic en "Renovar" en un préstamo activo
   - Se extiende 14 días la fecha de devolución

4. **Buscar:**
   - Usa el campo de búsqueda para filtrar por usuario o libro

## 🔒 Seguridad

- **Prepared Statements:** Todas las consultas SQL usan prepared statements para prevenir SQL injection
- **Sanitización:** Método `CleanData()` limpia todas las entradas del usuario
- **Password Hashing:** Las contraseñas se almacenan con `password_hash()` (bcrypt)
- **Validación:** Validación de tipos de datos y rangos en todos los formularios
- **Integridad Referencial:** Claves foráneas previenen eliminación de registros relacionados

## 🎨 Características Técnicas

### Arquitectura MVC

- **Models:** Gestión de datos y lógica de base de datos
- **Views:** Presentación HTML pura
- **Controllers:** Lógica de negocio y flujo de la aplicación

### MySQLi con Compatibilidad PDO

El sistema usa `MySQLiResultWrapper` para mantener compatibilidad con código existente:

```php
// Interfaz compatible con PDO
$result->rowCount()    // Número de filas
$result->fetch()       // Siguiente fila
$result->fetchAll()    // Todas las filas
$result->fetchColumn() // Una columna
```

### Paginación Automática

Todas las listas incluyen paginación configurable:

```php
$controller->ListController($page, $rows, $url, $search);
```

## 🐛 Solución de Problemas

### Error de conexión a la base de datos

Verifica:

- MySQL/MariaDB está ejecutándose
- Credenciales en `Config/server.php` son correctas
- Usuario tiene permisos para crear bases de datos

### No puedo iniciar sesión

Verifica:

- La base de datos fue creada correctamente
- Estás usando las credenciales: `admin` / `admin123`
- La tabla `users` existe y tiene el usuario administrador

### Error al crear préstamo

Verifica:

- El libro tiene copias disponibles (`available_quantity > 0`)
- Las fechas son válidas (devolución > préstamo)
- El usuario y libro existen en la base de datos

## 📝 Notas de Desarrollo

### Migración de PDO a mysqli

El sistema fue migrado de PDO a mysqli manteniendo compatibilidad:

```php
// Antes (PDO)
$connection = new PDO("mysql:host=...");

// Ahora (mysqli)
$connection = new \mysqli($server, $user, $pass, $database);
```

### Convenciones de Código

- **Nombres de variables:** camelCase
- **Nombres de clases:** PascalCase
- **Nombres de métodos:** PascalCase
- **Comentarios:** Español
- **Nombres de BD:** snake_case (inglés)

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👨‍💻 Autor

**Shino08**

- GitHub: [@Shino08](https://github.com/Shino08)

## 📞 Soporte

Si encuentras algún problema o tienes preguntas:

- Abre un [Issue](https://github.com/Shino08/Sistema_De_Gestion_De_Biblioteca/issues)
- Contacta al desarrollador

---

⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub!
