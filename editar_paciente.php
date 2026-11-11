<?php
session_start();
if (!isset($_SESSION['logueado'])) {
    header("Location: index.php");
    exit();
}
if (!in_array($_SESSION['rol'], ['superadmin', 'admin'])) {
    header("Location: menu.php");
    exit();
}
include 'db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "SELECT * FROM pacientes WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$paciente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$paciente) {
    header("Location: pacientes.php");
    exit();
}

if (isset($_POST['actualizar'])) {
    $stmt = mysqli_prepare($conexion, "UPDATE pacientes SET nombre=?, apellido=?, telefono=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sssi', $_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $id);
    mysqli_stmt_execute($stmt);
    header("Location: pacientes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Editar Paciente</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3><i class="fas fa-user-edit"></i> Editar Paciente</h3>
    <form method="post" class="mt-3 card p-4 shadow-sm">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Apellido</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($paciente['apellido']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($paciente['telefono']) ?>" class="form-control" required>
        </div>
        <button type="submit" name="actualizar" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
        <a href="pacientes.php" class="btn btn-secondary mt-2">Cancelar</a>
    </form>
</div>
</body>
</html>
