-- Elimina la base de datos si existe
DROP DATABASE IF EXISTS NotePost;
CREATE DATABASE NotePost CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE NotePost;

-- Tabla de avatares
CREATE TABLE avatar (
    id INT PRIMARY KEY,
    rutaImagen VARCHAR(255) NOT NULL
);

-- Tabla de fondos
CREATE TABLE fondo (
    id INT PRIMARY KEY,
    rutaImagen VARCHAR(255) NOT NULL
);
-- Tabla de usuarios
CREATE TABLE usuarios (
    nickname VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    avatar INT NULL,
    FOREIGN KEY (avatar) REFERENCES avatar(id)
);

-- Tabla de posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor_nickname VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    fechaPost DATETIME NOT NULL,
    privado TINYINT(1) DEFAULT 0,
    fondoId INT NULL,
    FOREIGN KEY (autor_nickname) REFERENCES usuarios(nickname),
    FOREIGN KEY (fondoId) REFERENCES fondo(id)
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
    nombre VARCHAR(255) PRIMARY KEY
);

CREATE TABLE solicitudes (
    solicitante VARCHAR(255),
    recibidor VARCHAR(255),
    aceptada BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (solicitante, recibidor),
    FOREIGN KEY (solicitante) REFERENCES usuarios(nickname),
    FOREIGN KEY (recibidor) REFERENCES usuarios(nickname)
 );

CREATE TABLE amigos (
    usuario1 VARCHAR(255) NOT NULL,
    usuario2 VARCHAR(255) NOT NULL,
    PRIMARY KEY (usuario1, usuario2),
    FOREIGN KEY (usuario1) REFERENCES usuarios(nickname) ON DELETE CASCADE,
    FOREIGN KEY (usuario2) REFERENCES usuarios(nickname) ON DELETE CASCADE
);

CREATE TABLE likes (
    id_post INT NOT NULL,
    usuario VARCHAR(255) NOT NULL,
    accion ENUM('like', 'dislike') NOT NULL,
    PRIMARY KEY (id_post, usuario),
    FOREIGN KEY (id_post) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario) REFERENCES usuarios(nickname) ON DELETE CASCADE
) ;

CREATE TABLE post_tag (
    post_id INT NOT NULL,
    tag_id  VARCHAR(255) NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(nombre) ON DELETE CASCADE
);
