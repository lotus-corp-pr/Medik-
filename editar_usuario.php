<?php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['rol'] !== 'superadmin') {
    header("Location: menu.php");
    exit();
}
include 'db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "SELECT id, usuario, nombre, rol FROM usuarios WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$usuario) {
    header("Location: usuarios.php");
    exit();
}

$error = '';
if (isset($_POST['actualizar'])) {
    $nombre = trim($_POST['nombre']);
    $rol = in_array($_POST['rol'], ['superadmin', 'admin', 'usuario']) ? $_POST['rol'] : 'usuario';
    $password = $_POST['password'];

    if ($usuario['rol'] === 'superadmin' && $rol !== 'superadmin' && $usuario['id'] == $_SESSION['id']) {
        $error = "No puedes cambiar tu propio rol de superadmin.";
    } else {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conexion, "UPDATE usuarios SET nombre=?, rol=?, password=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'sssi', $nombre, $rol, $hash, $id);
        } else {
            $stmt = mysqli_prepare($conexion, "UPDATE usuarios SET nombre=?, rol=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssi', $nombre, $rol, $id);
        }
        mysqli_stmt_execute($stmt);
        header("Location: usuarios.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Editar Usuario</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3><i class="fas fa-user-cog"></i> Editar Usuario</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger mt-3"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3 card p-4 shadow-sm">
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['usuario']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="usuario" <?= $usuario['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario (solo consulta)</option>
                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin (pacientes y citas)</option>
                <option value="superadmin" <?= $usuario['rol'] === 'superadmin' ? 'selected' : '' ?>>Super Admin (todo)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Contraseña nueva (dejar vacío para no cambiarla)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" name="actualizar" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
        <a href="usuarios.php" class="btn btn-secondary mt-2">Cancelar</a>
    </form>
</div>
</body>
</html>
