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

-- Usuarios iniciales (password: 123 para todos)
INSERT INTO usuarios (usuario, password, nombre, rol) VALUES
('admin123', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlCS4bZJ18JuywdBEmVUlq0IZlA0Vy', 'Super Administrador', 'superadmin'),
('recepcion', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlCS4bZJ18JuywdBEmVUlq0IZlA0Vy', 'Recepción', 'admin'),
('consulta', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlCS4bZJ18JuywdBEmVUlq0IZlA0Vy', 'Usuario de Consulta', 'usuario');
