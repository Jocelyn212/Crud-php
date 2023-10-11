<?php
// Incluimos el archivo de conexión a la base de datos
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre']; 
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion']; 
    $imagen_url = $_POST['imagen_url']; 

    if (!empty($nombre) && !empty($precio) && !empty($descripcion) && !empty($imagen_url)) {
        try {
            $conn = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = $conn->prepare('INSERT INTO productos (nombre, precio, descripcion, imagen_url) VALUES (:nombre, :precio, :descripcion, :imagen_url)');
            $query->bindParam(':nombre', $nombre);
            $query->bindParam(':precio', $precio);
            $query->bindParam(':descripcion', $descripcion);
            $query->bindParam(':imagen_url', $imagen_url); 
            $query->execute();

            // Redirige al usuario a la página principal después de agregar el producto
            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Por favor, complete todos los campos.';
    }
}
?>
