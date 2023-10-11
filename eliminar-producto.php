<?php
// Incluimos el archivo de conexión a la base de datos
require_once('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $conn = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $conn->prepare('DELETE FROM productos WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        // Redirige al usuario a la página principal después de eliminar el producto
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'No se ha proporcionado un ID válido.';
}
?>
