<?php
session_start();

// Verificar si el usuario ha iniciado sesión, de lo contrario redirigir a la página de inicio de sesión
if (!isset($_SESSION['valid'])) {
	header('Location: login.php');
	exit();
}
?>

<?php
// Incluir el archivo de conexión a la base de datos
include_once("connection.php");

// Recuperar los productos del usuario actual en orden descendente
$result = mysqli_query($mysqli, "SELECT * FROM products WHERE login_id=" . $_SESSION['id'] . " ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Página Principal</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container mt-5">
		<!-- Barra de navegación -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
			<div class="container-fluid">
				<a class="navbar-brand" href="index.php">Inicio</a>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="add.php">Agregar Nuevo Producto</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="add_category.php">Agregar Nueva Categoría</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-danger" href="logout.php">Cerrar Sesión</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- Tabla de productos -->
		<table class="table table-striped">
			<thead class="table-dark">
				<tr>
					<th>Nombre</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>Categoría</th> <!-- Nueva columna para la categoría -->
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Obtener productos junto con sus categorías
				$result = mysqli_query($mysqli, "SELECT products.*, categories.name AS category_name FROM products 
					LEFT JOIN categories ON products.category_id = categories.id 
					WHERE login_id=" . $_SESSION['id'] . " ORDER BY products.id DESC");

				// Mostrar los productos y sus categorías en la tabla
				while ($res = mysqli_fetch_array($result)) {
					echo "<tr>";
					echo "<td>" . htmlspecialchars($res['name']) . "</td>";
					echo "<td>" . htmlspecialchars($res['qty']) . "</td>";
					echo "<td>" . htmlspecialchars($res['price']) . "</td>";
					echo "<td>" . htmlspecialchars($res['category_name']) . "</td>";  // Mostrar el nombre de la categoría
					echo "<td>";
					echo "<a href=\"edit.php?id=" . $res['id'] . "\" class='btn btn-sm btn-warning me-2'>Editar</a>";
					echo "<a href=\"delete.php?id=" . $res['id'] . "\" onClick=\"return confirm('¿Estás seguro de que quieres eliminar?')\" class='btn btn-sm btn-danger'>Eliminar</a>";
					echo "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>