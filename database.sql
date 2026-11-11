-- Sistema Medik — Citas Médicas y Gestión de Citas
-- Base de datos: if0_43398453_medik
-- Importar desde phpMyAdmin de InfinityFree

CREATE TABLE IF NOT EXISTS usuarios (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  rol ENUM('superadmin','admin','usuario') NOT NULL DEFAULT 'usuario'
);

CREATE TABLE IF NOT EXISTS pacientes (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  telefono VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS citas (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT(11) NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  nota VARCHAR(255),
  FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
);

-- NOTA: no insertamos usuarios aquí porque las contraseñas deben hashearse
-- con password_hash() desde PHP. Después de importar este archivo,
-- abre instalar.php en el navegador UNA VEZ para crear los usuarios:
--   admin123 / 123  -> superadmin
--   recepcion / 123 -> admin
--   consulta / 123  -> usuario
-- Luego borra instalar.php del servidor.
