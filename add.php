<?php
session_start();

if (!isset($_SESSION['valid'])) {
	header('Location: login.php');
	exit();
}

// Regenerar ID de sesión por seguridad
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Add Data</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container mt-5">
		<!-- Enlaces de navegación -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">Gestión de Productos</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
					aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="index.php">Inicio</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="view.php">Ver Productos</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="logout.php">Cerrar Sesión</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>

	<div class="container mt-5">
		<div class="card">
			<div class="card-header">
				<h3>Añadir Producto</h3>
			</div>
			<div class="card-body">
				<?php
				// Incluyendo la conexión a la base de datos
				include_once("connection.php");

				// Obtener las categorías para el select
				$result_categories = mysqli_query($mysqli, "SELECT * FROM categories");

				if (isset($_POST['Submit'])) {
					$name = mysqli_real_escape_string($mysqli, $_POST['name']);
					$qty = mysqli_real_escape_string($mysqli, $_POST['qty']);
					$price = mysqli_real_escape_string($mysqli, $_POST['price']);
					$category_id = mysqli_real_escape_string($mysqli, $_POST['category_id']);
					$loginId = $_SESSION['id'];

					// Validación de campos vacíos
					$errors = [];
					if (empty($name)) {
						$errors[] = "El nombre del producto está vacío.";
					}
					if (empty($qty) || !is_numeric($qty)) {
						$errors[] = "La cantidad es inválida o está vacía.";
					}
					if (empty($price) || !is_numeric($price)) {
						$errors[] = "El precio es inválido o está vacío.";
					}
					if (empty($category_id)) {
						$errors[] = "Debe seleccionar una categoría.";
					}

					if (!empty($errors)) {
						echo '<div class="alert alert-danger" role="alert">';
						foreach ($errors as $error) {
							echo htmlspecialchars($error) . "<br/>";
						}
						echo '</div>';
						echo "<a href='javascript:self.history.back();' class='btn btn-danger'>Regresar</a>";
					} else {
						// Inserción de datos en la base de datos
						$stmt = $mysqli->prepare("INSERT INTO products (name, qty, price, category_id, login_id) VALUES (?, ?, ?, ?, ?)");
						$stmt->bind_param("siiii", $name, $qty, $price, $category_id, $loginId);

						if ($stmt->execute()) {
							echo '<div class="alert alert-success" role="alert">Producto añadido exitosamente.</div>';
							echo "<a href='view.php' class='btn btn-primary'>Ver Productos</a>";
						} else {
							echo '<div class="alert alert-danger" role="alert">Hubo un error al añadir el producto.</div>';
						}

						$stmt->close();
					}
				}
				?>
				<form action="add.php" method="post" class="mt-4">
					<div class="form-group mb-3">
						<label for="name" class="form-label">Nombre del Producto</label>
						<input type="text" name="name" class="form-control" id="name" placeholder="Introduce el nombre del producto" required>
					</div>
					<div class="form-group mb-3">
						<label for="qty" class="form-label">Cantidad</label>
						<input type="number" name="qty" class="form-control" id="qty" placeholder="Introduce la cantidad" required>
					</div>
					<div class="form-group mb-3">
						<label for="price" class="form-label">Precio</label>
						<input type="text" name="price" class="form-control" id="price" placeholder="Introduce el precio" required>
					</div>
					<div class="form-group mb-3">
						<label for="category_id" class="form-label">Categoría</label>
						<select name="category_id" class="form-select" required>
							<option value="">Selecciona una categoría</option>
							<?php
							while ($row = mysqli_fetch_assoc($result_categories)) {
								echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
							}
							?>
						</select>
					</div>
					<button type="submit" name="Submit" class="btn btn-success">Añadir Producto</button>
				</form>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS y dependencias -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>