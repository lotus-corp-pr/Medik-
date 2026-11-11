<?php
session_start();
if (!isset($_SESSION['logueado'])) {
    header("Location: index.php");
    exit();
}
// Solo superadmin y admin pueden gestionar pacientes
if (!in_array($_SESSION['rol'], ['superadmin', 'admin'])) {
    header("Location: menu.php");
    exit();
}
include 'db.php';

// Guardar nuevo paciente
if (isset($_POST['agregar'])) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO pacientes (nombre, apellido, telefono) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $_POST['nombre'], $_POST['apellido'], $_POST['telefono']);
    mysqli_stmt_execute($stmt);
    header("Location: pacientes.php");
    exit();
}

// Eliminar paciente
if (isset($_GET['eliminar'])) {
    $stmt = mysqli_prepare($conexion, "DELETE FROM pacientes WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $_GET['eliminar']);
    mysqli_stmt_execute($stmt);
    header("Location: pacientes.php");
    exit();
}

$pacientes = mysqli_query($conexion, "SELECT * FROM pacientes ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Pacientes</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="menu.php"><i class="fas fa-clinic-medical"></i> Medik</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fas fa-home"></i> Inicio</a></li>
        <li class="nav-item active"><a class="nav-link" href="pacientes.php"><i class="fas fa-user"></i> Pacientes</a></li>
        <li class="nav-item"><a class="nav-link" href="citas.php"><i class="fas fa-calendar-alt"></i> Citas</a></li>
        <?php if ($_SESSION['rol'] === 'superadmin'): ?>
        <li class="nav-item"><a class="nav-link" href="usuarios.php"><i class="fas fa-users-cog"></i> Usuarios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
    </ul>
</nav>

<div class="container mt-4">
    <h2><i class="fas fa-users"></i> Gestión de Pacientes</h2>

    <!-- Formulario Agregar -->
    <div class="card my-4 p-3 shadow-sm">
        <h5>Agregar Nuevo Paciente</h5>
        <form method="post" class="form-row mt-2">
            <div class="col-md-4 mb-2">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="telefono" class="form-control" placeholder="Teléfono" required>
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" name="agregar" class="btn btn-primary btn-block"><i class="fas fa-plus"></i></button>
            </div>
        </form>
    </div>

    <!-- Tabla de Registros -->
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($pacientes)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['apellido']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td>
                    <a href="editar_paciente.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                    <a href="pacientes.php?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar este paciente?')"><i class="fas fa-trash"></i> Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($pacientes) === 0): ?>
            <tr><td colspan="5" class="text-center text-muted">No hay pacientes registrados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
