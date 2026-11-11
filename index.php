<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conexion, "SELECT id, usuario, password, nombre, rol FROM usuarios WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, 's', $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['logueado'] = true;
        $_SESSION['id'] = $data['id'];
        $_SESSION['usuario'] = $data['usuario'];
        $_SESSION['nombre'] = $data['nombre'];
        $_SESSION['rol'] = $data['rol'];
        header("Location: menu.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Inicio de Sesión</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 450px;">
    <h2 class="text-center"><i class="fas fa-clinic-medical"></i> Medik</h2>
    <p class="text-center text-muted">Sistema de Citas Médicas</p>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3 card p-4 shadow-sm">
        <div class="form-group">
            <label><i class="fas fa-user"></i> Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-lock"></i> Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</button>
    </form>
</div>
</body>
</html>
