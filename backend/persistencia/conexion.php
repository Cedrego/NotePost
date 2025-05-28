<?php
class Conexion {
    public static function getConexion(): mysqli {
        $host = "localhost";
        $usuario = "root";
        $contrasena = "";  // Vacío por defecto en XAMPP
        $baseDeDatos = "NotePost";

        $conn = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");

        return $conn;
    }
}
