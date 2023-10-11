<?php
// Incluimos el archivo de conexión a la base de datos
require_once('config.php');

// Verificamos si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperamos los datos del formulario de edición
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $imagen_url = $_POST['imagen_url'];

    // Actualizamos el producto en la base de datos
    try {
        $conn = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $conn->prepare('UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion, imagen_url = :imagen_url WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->bindParam(':nombre', $nombre);
        $query->bindParam(':precio', $precio);
        $query->bindParam(':descripcion', $descripcion);
        $query->bindParam(':imagen_url', $imagen_url);
        $query->execute();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    // Redirigimos a la página index.php después de la edición
    header('Location: index.php');
    exit;
}

// Verificamos si se ha proporcionado el parámetro de ID del producto en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtenemos los detalles del producto de la base de datos utilizando la ID
    try {
        $conn = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $conn->prepare('SELECT * FROM productos WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        $producto = $query->fetch();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    // Comprobamos si se encontró el producto
    if ($producto) {
?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <title>Editar Producto</title>
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        </head>
        <style>
        body {
         background-image: url('https://i.pinimg.com/originals/d1/81/72/d181729ed7d917b8faf86e61997cbbbc.jpg');
         background-size: cover;
         background-repeat: no-repeat;
            }
        header {
         background-image: url('https://cdn.pixabay.com/photo/2017/08/24/00/28/geometric-background-2675011_1280.jpg');
         font-weight: bold;
         height: 100px;
         text-align: center;
          }
        </style>
        <body>
        <header>
          <h1 class="text-white mb-3 mx-3 mt-3">Informatica PPC</h1>
        </header>
          <div class="container">
          <a href="index.php" class="btn btn-primary my-3">Volver a Inicio</a>
            <h2 class="text-white mb-3 mx-3 mt-3 my-4">Editar Producto</h2>
            <form action="editar-producto.php" method="POST"class="text-white">
              <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
              <div class="form-group">
                <label for="nombre"class="text-white">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $producto['nombre']; ?>" required>
              </div>
              <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" name="precio" class="form-control" value="<?php echo $producto['precio']; ?>" required>
              </div>
              <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" class="form-control" required><?php echo $producto['descripcion']; ?></textarea>
              </div>
              <div class="form-group">
                <label for="imagen_url">URL de la Imagen:</label>
                <input type="url" name="imagen_url" class="form-control" value="<?php echo $producto['imagen_url']; ?>" required>
              </div>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
          </div>
        </body>
        </html>
<?php
    } else {
        // Si no se encuentra el producto, redirigimos a la página index.php
        header('Location: index.php');
        exit;
    }
} else {
    // Si no se proporciona la ID del producto, redirigimos a la página index.php
    header('Location: index.php');
    exit;
}
?>
