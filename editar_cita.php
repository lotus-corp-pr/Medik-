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
$stmt = mysqli_prepare($conexion, "SELECT * FROM citas WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$cita = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$cita) {
    header("Location: citas.php");
    exit();
}

$pacientes = mysqli_query($conexion, "SELECT * FROM pacientes ORDER BY nombre");

if (isset($_POST['actualizar'])) {
    $stmt = mysqli_prepare($conexion, "UPDATE citas SET paciente_id=?, fecha=?, hora=?, nota=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'isssi', $_POST['paciente_id'], $_POST['fecha'], $_POST['hora'], $_POST['nota'], $id);
    mysqli_stmt_execute($stmt);
    header("Location: citas.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Editar Cita</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3><i class="fas fa-edit"></i> Editar Cita</h3>
    <form method="post" class="mt-3 card p-4 shadow-sm">
        <div class="form-group">
            <label>Paciente</label>
            <select name="paciente_id" class="form-control" required>
                <?php while ($p = mysqli_fetch_assoc($pacientes)): ?>
                <option value="<?= $p['id'] ?>" <?= ($p['id'] == $cita['paciente_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha" value="<?= $cita['fecha'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Hora</label>
            <input type="time" name="hora" value="<?= $cita['hora'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nota</label>
            <input type="text" name="nota" value="<?= htmlspecialchars($cita['nota'] ?? '') ?>" class="form-control">
        </div>
        <button type="submit" name="actualizar" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
        <a href="citas.php" class="btn btn-secondary mt-2">Cancelar</a>
    </form>
</div>
</body>
</html>
