<?php
session_start();
if (!isset($_SESSION['logueado'])) {
    header("Location: index.php");
    exit();
}
include 'db.php';

$rol = $_SESSION['rol'];
$puedeEditar = in_array($rol, ['superadmin', 'admin']);

// Agendar Cita (solo superadmin y admin)
if ($puedeEditar && isset($_POST['agregar'])) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO citas (paciente_id, fecha, hora, nota) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'isss', $_POST['paciente_id'], $_POST['fecha'], $_POST['hora'], $_POST['nota']);
    mysqli_stmt_execute($stmt);
    header("Location: citas.php");
    exit();
}

// Eliminar Cita (solo superadmin y admin)
if ($puedeEditar && isset($_GET['eliminar'])) {
    $stmt = mysqli_prepare($conexion, "DELETE FROM citas WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $_GET['eliminar']);
    mysqli_stmt_execute($stmt);
    header("Location: citas.php");
    exit();
}

$pacientes = mysqli_query($conexion, "SELECT * FROM pacientes ORDER BY nombre");
$citas = mysqli_query($conexion, "SELECT citas.*, pacientes.nombre, pacientes.apellido
    FROM citas JOIN pacientes ON citas.paciente_id = pacientes.id
    ORDER BY citas.fecha DESC, citas.hora DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Citas Médicas</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="menu.php"><i class="fas fa-clinic-medical"></i> Medik</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fas fa-home"></i> Inicio</a></li>
        <?php if ($puedeEditar): ?>
        <li class="nav-item"><a class="nav-link" href="pacientes.php"><i class="fas fa-user"></i> Pacientes</a></li>
        <?php endif; ?>
        <li class="nav-item active"><a class="nav-link" href="citas.php"><i class="fas fa-calendar-alt"></i> Citas</a></li>
        <?php if ($rol === 'superadmin'): ?>
        <li class="nav-item"><a class="nav-link" href="usuarios.php"><i class="fas fa-users-cog"></i> Usuarios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
    </ul>
</nav>

<div class="container mt-4">
    <h2><i class="fas fa-calendar-check"></i> Gestión de Citas</h2>

    <?php if ($puedeEditar): ?>
    <!-- Formulario Agendar Cita -->
    <div class="card my-4 p-3 shadow-sm">
        <h5>Agendar Nueva Cita</h5>
        <form method="post" class="form-row mt-2">
            <div class="col-md-3 mb-2">
                <select name="paciente_id" class="form-control" required>
                    <option value="">Seleccionar Paciente...</option>
                    <?php while ($p = mysqli_fetch_assoc($pacientes)): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="col-md-2 mb-2">
                <input type="time" name="hora" class="form-control" required>
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="nota" class="form-control" placeholder="Nota o motivo">
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" name="agregar" class="btn btn-success btn-block"><i class="fas fa-plus"></i></button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="alert alert-info my-4"><i class="fas fa-info-circle"></i> Tu rol es de solo lectura: puedes consultar las citas pero no modificarlas.</div>
    <?php endif; ?>

    <!-- Tabla de Citas -->
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Nota</th>
                <?php if ($puedeEditar): ?><th>Acciones</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($citas)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                <td><?= $row['fecha'] ?></td>
                <td><?= $row['hora'] ?></td>
                <td><?= htmlspecialchars($row['nota'] ?? '') ?></td>
                <?php if ($puedeEditar): ?>
                <td>
                    <a href="editar_cita.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                    <a href="citas.php?eliminar=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea cancelar esta cita?')"><i class="fas fa-trash"></i> Eliminar</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($citas) === 0): ?>
            <tr><td colspan="<?= $puedeEditar ? 6 : 5 ?>" class="text-center text-muted">No hay citas registradas</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
