<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: menu.php");
    exit();
}
include 'db.php';

// Crear usuario
if (isset($_POST['agregar'])) {
    $usuario = trim($_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nombre = trim($_POST['nombre']);
    $rol = in_array($_POST['rol'], ['superadmin', 'admin', 'usuario']) ? $_POST['rol'] : 'usuario';

    $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (usuario, password, nombre, rol) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $password, $nombre, $rol);
    mysqli_stmt_execute($stmt);
    header("Location: usuarios.php");
    exit();
}

// Eliminar usuario (no puede eliminarse a sí mismo ni a otro superadmin)
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id !== intval($_SESSION['id'])) {
        $stmt = mysqli_prepare($conexion, "DELETE FROM usuarios WHERE id = ? AND rol <> 'superadmin'");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: usuarios.php");
    exit();
}

$usuarios = mysqli_query($conexion, "SELECT id, usuario, nombre, rol FROM usuarios ORDER BY id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Usuarios</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="menu.php"><i class="fas fa-clinic-medical"></i> Medik</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fas fa-home"></i> Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="pacientes.php"><i class="fas fa-user"></i> Pacientes</a></li>
        <li class="nav-item"><a class="nav-link" href="citas.php"><i class="fas fa-calendar-alt"></i> Citas</a></li>
        <li class="nav-item active"><a class="nav-link" href="usuarios.php"><i class="fas fa-users-cog"></i> Usuarios</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
    </ul>
</nav>

<div class="container mt-4">
    <h2><i class="fas fa-users-cog"></i> Gestión de Usuarios</h2>

    <!-- Formulario Agregar -->
    <div class="card my-4 p-3 shadow-sm">
        <h5>Crear Nuevo Usuario</h5>
        <form method="post" class="form-row mt-2">
            <div class="col-md-3 mb-2">
                <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
            </div>
            <div class="col-md-2 mb-2">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="col-md-3 mb-2">
                <select name="rol" class="form-control" required>
                    <option value="usuario">Usuario (solo consulta)</option>
                    <option value="admin">Admin (pacientes y citas)</option>
                    <option value="superadmin">Super Admin (todo)</option>
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" name="agregar" class="btn btn-primary btn-block"><i class="fas fa-plus"></i></button>
            </div>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($usuarios)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['usuario']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td>
                    <?php $badge = ['superadmin' => 'danger', 'admin' => 'warning', 'usuario' => 'secondary']; ?>
                    <span class="badge badge-<?= $badge[$row['rol']] ?>"><?= $row['rol'] ?></span>
                </td>
                <td>
                    <a href="editar_usuario.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                    <?php if ($row['rol'] !== 'superadmin' && $row['id'] != $_SESSION['id']): ?>
                    <a href="usuarios.php?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar este usuario?')"><i class="fas fa-trash"></i> Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
