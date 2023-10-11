
<?php
// Incluimos el archivo de conexión a la base de datos
require_once('config.php');
// Conectamos a la base de datos
try {
  $conn = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_GET['q'])) {
    $busqueda = $_GET['q'];
    // Realizamos la consulta en la base de datos para buscar productos que coincidan con la búsqueda
    $query = $conn->prepare('SELECT * FROM productos WHERE nombre LIKE :busqueda OR descripcion LIKE :busqueda');
    $query->bindValue(':busqueda', '%' . $busqueda . '%');
    $query->execute();
    $productos = $query->fetchAll();
  } else {
    // Obtenemos todos los productos
    $query = $conn->query('SELECT * FROM productos');
    $productos = $query->fetchAll();
  }
} catch (PDOException $e) {
  echo 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda Informatica PPC</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
</head>
<body>
  <header>
    <h1 class="text-white mb-3 mx-3 mt-3">Informatica PPC</h1>
  </header>
  <main>
    <section class="mb-3 mt-3 ml-3">
      <form action="index.php" method="GET">
        <input type="text" name="q" placeholder="Buscar producto">
        <button type="submit">Buscar</button>
      </form>
    </section>
    <!-- Muestra los resultados de la búsqueda -->
   
    <section class="productos d-flex flex-wrap mr-3 mb-3">
      <?php if (isset($productos) && count($productos) > 0) : ?>
        <?php foreach ($productos as $producto) : ?>
          <div class="card mb-3 mx-3" style="width: 18rem;">
            <img src="<?php echo $producto['imagen_url']; ?>" class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
              <p class="card-text"><?php echo $producto['descripcion']; ?></p>
              <p class="card-text"><?php echo $producto['precio']; ?> €</p>
              <a href="eliminar-producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger">Eliminar</a>
              <a href="editar-producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary">Editar</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>No se encontraron resultados para la búsqueda.</p>
      <?php endif; ?>
    </section>

         <button class="btn btn-primary  mb-3 mx-3 mt-3"style="width: 150px;"><a href="index.php" class="text-white">Inicio</a></button>

    <!-- Formulario para agregar nuevos productos -->
    <section class="agregar-producto mx-18">
      <h2 class="text-white mb-3 mx-3 mt-3" >Agregar Producto</h2>
      <form action="procesar-agregar.php" method="POST" class="mb-3 mt-3 ml-3 mr-5 text-white">
        <div class="form-group"> 
          <label for="nombre">Nombre:</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="precio">Precio:</label>
          <input type="number" name="precio" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="descripcion">Descripción:</label>
          <textarea name="descripcion" class="form-control" required></textarea>
        </div>
        <div class="form-group">
          <label for="imagen_url">URL de la Imagen:</label>
          <input type="url" name="imagen_url" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Producto</button>
      </form>
    </section>
  </main>
  <footer>
    <p class="text-white">Derechos de autor JC  &copy; 2023</p>
  </footer>
</body>
</html>

