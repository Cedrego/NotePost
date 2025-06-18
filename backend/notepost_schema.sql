-- Elimina la base de datos si existe
DROP DATABASE IF EXISTS NotePost;
CREATE DATABASE NotePost CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE NotePost;

-- Tabla de usuarios
CREATE TABLE usuarios (
    nickname VARCHAR(50) PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    avatar INT NULL
);

-- Tabla de posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor_nickname VARCHAR(50) NOT NULL,
    contenido TEXT NOT NULL,
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    fechaPost DATETIME NOT NULL,
    privado TINYINT(1) DEFAULT 0,
    fondoId INT NULL,
    FOREIGN KEY (autor_nickname) REFERENCES usuarios(nickname)
);

-- Tabla de recordatorios
CREATE TABLE recordatorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    fechaRecordatorio DATETIME NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Tabla de tags
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    tag VARCHAR(50) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);