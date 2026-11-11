<?php
// Conexión a la Base de Datos — InfinityFree (Medik)
$host = 'sql308.infinityfree.com';
$user = 'if0_43398453';
$password = 'Q6NFmJXURF';
$db = 'if0_43398453_medik';

$conexion = mysqli_connect($host, $user, $password, $db);

if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, 'utf8mb4');
?>
