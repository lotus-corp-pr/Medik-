<?php
session_start();
if (!isset($_SESSION['logueado'])) {
    header("Location: index.php");
    exit();
}
$rol = $_SESSION['rol'];
$esSuper = ($rol === 'superadmin');
$esAdmin = ($rol === 'admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Medik — Menú Principal</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="menu.php"><i class="fas fa-clinic-medical"></i> Medik</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fas fa-home"></i> Inicio</a></li>
        <?php if ($esSuper || $esAdmin): ?>
        <li class="nav-item"><a class="nav-link" href="pacientes.php"><i class="fas fa-user"></i> Pacientes</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="citas.php"><i class="fas fa-calendar-alt"></i> Citas</a></li>
        <?php if ($esSuper): ?>
        <li class="nav-item"><a class="nav-link" href="usuarios.php"><i class="fas fa-users-cog"></i> Usuarios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
    </ul>
</nav>

<div class="container mt-5 text-center">
    <h3 class="mb-1">Bienvenido al Sistema Medik de Gestión de Citas Médicas</h3>
    <p class="text-muted"><?= htmlspecialchars($_SESSION['nombre']) ?> — rol: <span class="badge badge-info"><?= $rol ?></span></p>

    <div class="d-flex justify-content-center flex-wrap">
        <?php if ($esSuper || $esAdmin): ?>
        <a href="pacientes.php" class="btn btn-primary btn-lg rounded-circle d-flex flex-column align-items-center justify-content-center mx-3 my-2 shadow" style="width: 180px; height: 180px; text-decoration: none;">
            <i class="fas fa-user fa-3x"></i><span class="mt-2 font-weight-bold">Pacientes</span>
        </a>
        <?php endif; ?>

        <a href="citas.php" class="btn btn-success btn-lg rounded-circle d-flex flex-column align-items-center justify-content-center mx-3 my-2 shadow" style="width: 180px; height: 180px; text-decoration: none;">
            <i class="fas fa-calendar-alt fa-3x"></i><span class="mt-2 font-weight-bold"><?= $esSuper || $esAdmin ? 'Citas' : 'Consultar Citas' ?></span>
        </a>

        <?php if ($esSuper): ?>
        <a href="usuarios.php" class="btn btn-dark btn-lg rounded-circle d-flex flex-column align-items-center justify-content-center mx-3 my-2 shadow" style="width: 180px; height: 180px; text-decoration: none;">
            <i class="fas fa-users-cog fa-3x"></i><span class="mt-2 font-weight-bold">Usuarios</span>
        </a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
