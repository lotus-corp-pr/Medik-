# Medik — Sistema de Citas Médicas y Gestión de Citas

Sitio web con **PHP, MySQL y Bootstrap** para la gestión de pacientes y citas médicas, con **tres niveles de usuario**: super admin, admin y usuario.

Programador: Luis Ángel Campos Santacruz

## Tecnologías
1. **PHP** — lógica del backend, consultas y autenticación de usuarios.
2. **MySQL** — base de datos relacional (usuarios, pacientes y citas).
3. **Bootstrap 4.5** — interfaz responsive.
4. **Font Awesome 5** — íconos en menús y botones.

## Instalación en InfinityFree
1. Sube todos los archivos PHP a la carpeta `htdocs` de tu cuenta InfinityFree (con el **Administrador de Archivos** o por FTP).
2. Entra al **phpMyAdmin** de InfinityFree, selecciona la base `if0_43398453_medik` y importa el archivo `database.sql` (crea las tablas y los usuarios iniciales).
3. Abre tu sitio e inicia sesión.

> La conexión ya está configurada en `db.php` con los datos de tu hosting (`sql308.infinityfree.com`). Si InfinityFree te pide usar el "mysql hostname interno", cambia `$host` por el valor que aparezca en el panel de control.

### Usuarios iniciales (contraseña: `123`)
| Usuario | Rol | Permisos |
|---|---|---|
| `admin123` | Super Admin | Todo + gestión de usuarios |
| `recepcion` | Admin | Pacientes y citas |
| `consulta` | Usuario | Solo consulta de citas |

## Estructura (MVC simplificado)
```
htdocs/
├── db.php               # Conexión a la base de datos (modelo)
├── database.sql         # Script de base de datos
├── index.php            # Inicio de sesión
├── menu.php             # Menú principal según rol
├── pacientes.php        # Gestión de pacientes (superadmin/admin)
├── editar_paciente.php  # Edición de paciente
├── citas.php            # Gestión y consulta de citas
├── editar_cita.php      # Edición de cita
├── usuarios.php         # Gestión de usuarios (solo superadmin)
├── editar_usuario.php   # Edición de usuario y rol
└── logout.php           # Cierre de sesión
```

## Roles y permisos
- **Super Admin**: CRUD de pacientes, citas y usuarios (crea admins y usuarios, asigna roles, restablece contraseñas).
- **Admin**: CRUD de pacientes y citas.
- **Usuario**: solo lectura de la lista de citas.

## Seguridad incluida
- Contraseñas con `password_hash()` / `password_verify()`.
- Consultas preparadas (protección contra inyección SQL).
- Salida con `htmlspecialchars()` (protección contra XSS).
- Control de acceso por rol en cada página.
