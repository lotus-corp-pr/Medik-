<?php
// Medik — Instalador de usuarios
// Ejecuta este archivo UNA VEZ en el navegador (medik.site.je/medik1/instalar.php)
// y DESPUÉS ELIMÍNALO del servidor por seguridad.

ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$usuarios = [
    ['admin123', 'Super Administrador', 'superadmin'],
    ['recepcion', 'Recepción', 'admin'],
    ['consulta', 'Usuario de Consulta', 'usuario'],
];

echo "<h2>Instalación de usuarios — Medik</h2>";

foreach ($usuarios as [$usuario, $nombre, $rol]) {
    $hash = password_hash('123', PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conexion,
        "INSERT INTO usuarios (usuario, password, nombre, rol) VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE password = VALUES(password), nombre = VALUES(nombre), rol = VALUES(rol)");
    mysqli_stmt_bind_param($stmt, 'ssss', $usuario, $hash, $nombre, $rol);
    $ok = mysqli_stmt_execute($stmt);

    echo $ok
        ? "<p>✔ Usuario <b>$usuario</b> ($rol) creado/actualizado con contraseña <b>123</b></p>"
        : "<p>✘ Error con <b>$usuario</b>: " . mysqli_error($conexion) . "</p>";
}

echo "<hr><p><b>Listo.</b> Ahora puedes iniciar sesión en <a href='index.php'>index.php</a>.</p>";
echo "<p style='color:red'><b>IMPORTANTE: borra este archivo (instalar.php) del servidor ahora.</b></p>";
