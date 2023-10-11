<?php
// Incluye el archivo de configuración y realiza la conexión a la base de datos
require_once('config.php');
// Verifica si se ha enviado una consulta de búsqueda
if (isset($_GET['q'])) {
  $busqueda = $_GET['q'];
  try {
    $conn = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Realiza la consulta en la base de datos para buscar productos que coincidan con la búsqueda
    $query = $conn->prepare('SELECT * FROM productos WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda');
    $query->bindValue(':busqueda', '%' . $busqueda . '%');
    $query->execute();
    $resultados = $query->fetchAll();
    // Muestra los resultados de la búsqueda en tu página
    foreach ($resultados as $producto) {
      echo '<div class="card mb-3 mx-3" style="width: 18rem;">';
      echo '<img src="' . $producto['imagen_url'] . '" class="card-img-top" alt="' . $producto['nombre'] . '">';
      echo '<div class="card-body">';
      echo '<h5 class="card-title">' . $producto['nombre'] . '</h5>';
      echo '<p class="card-text">' . $producto['descripcion'] . '</p>';
      echo '<p class="card-text">' . $producto['precio'] . ' €</p>';
      echo '<a href="eliminar-producto.php?id=' . $producto['id'] . '" class="btn btn-danger">Eliminar</a>';
      echo '</div>';
      echo '</div>';
    }
    // Si no se encontraron resultados
    if (count($resultados) == 0) {
      echo 'No se encontraron resultados para la búsqueda.';
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
}
?>