<?php
session_start();

if (!isset($_SESSION['valid'])) {
	header('Location: login.php');
	exit();
}

// Regenerar ID de sesión por seguridad
session_regenerate_id(true);
?>

<?php
// Incluyendo el archivo de conexión a la base de datos
include_once("connection.php");

if (isset($_POST['update'])) {
	$id = $_POST['id'];

	// Validar entradas y evitar inyecciones SQL
	$name = mysqli_real_escape_string($mysqli, $_POST['name']);
	$qty = mysqli_real_escape_string($mysqli, $_POST['qty']);
	$price = mysqli_real_escape_string($mysqli, $_POST['price']);
	$category_id = mysqli_real_escape_string($mysqli, $_POST['category_id']);

	// Verificar si hay campos vacíos
	$errors = [];
	if (empty($name)) {
		$errors[] = "El campo de nombre está vacío.";
	}
	if (empty($qty) || !is_numeric($qty) || $qty <= 0) {
		$errors[] = "El campo de cantidad es inválido o está vacío.";
	}
	if (empty($price) || !is_numeric($price) || $price <= 0) {
		$errors[] = "El campo de precio es inválido o está vacío.";
	}
	if (empty($category_id)) {
		$errors[] = "Debe seleccionar una categoría.";
	}

	if (!empty($errors)) {
		foreach ($errors as $error) {
			echo "<div class='alert alert-danger'>$error</div>";
		}
		echo "<a href='javascript:self.history.back();' class='btn btn-danger'>Regresar</a>";
	} else {
		// Preparar la consulta para actualizar los datos
		$stmt = $mysqli->prepare("UPDATE products SET name = ?, qty = ?, price = ?, category_id = ? WHERE id = ?");
		$stmt->bind_param("siiii", $name, $qty, $price, $category_id, $id);

		if ($stmt->execute()) {
			// Redireccionar a la página de visualización tras la actualización
			header("Location: view.php");
			exit();
		} else {
			echo "<div class='alert alert-danger'>Error al actualizar el producto. Inténtalo de nuevo.</div>";
		}

		$stmt->close();
	}
}
?>

<?php
// Obtener el id desde la URL
$id = $_GET['id'];

// Seleccionar datos asociados con este id en particular
$result = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$result->bind_param("i", $id);
$result->execute();
$res = $result->get_result()->fetch_assoc();

$name = $res['name'];
$qty = $res['qty'];
$price = $res['price'];
$category_id = $res['category_id'];
$result->close();

// Obtener las categorías para el select
$result_categories = $mysqli->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Editar Producto</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

	<div class="container mt-5">
		<div class="card">
			<div class="card-header">
				<h3>Editar Producto</h3>
			</div>
			<div class="card-body">
				<form method="post" action="edit.php">
					<div class="mb-3">
						<label for="name" class="form-label">Nombre del Producto</label>
						<input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
					</div>
					<div class="mb-3">
						<label for="qty" class="form-label">Cantidad</label>
						<input type="number" class="form-control" name="qty" value="<?php echo htmlspecialchars($qty); ?>" required>
					</div>
					<div class="mb-3">
						<label for="price" class="form-label">Precio</label>
						<input type="number" class="form-control" step="0.01" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
					</div>
					<div class="mb-3">
						<label for="category_id" class="form-label">Categoría</label>
						<select name="category_id" class="form-select" required>
							<?php while ($row = $result_categories->fetch_assoc()) { ?>
								<option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $category_id) ? 'selected' : ''; ?>>
									<?php echo $row['name']; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
					<button type="submit" name="update" class="btn btn-primary">Actualizar Producto</button>
					<a href="view.php" class="btn btn-secondary">Cancelar</a>
				</form>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS and dependencies -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>