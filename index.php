<?php session_start(); ?>
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
	<!-- Encabezado -->
	<nav class="navbar navbar-light bg-light">
		<div class="container-fluid">
			<span class="navbar-brand mb-0 h1">Bienvenido a mi página</span>
		</div>
	</nav>

	<div class="container mt-5">
		<?php
		if (isset($_SESSION['valid'])) {
			include("connection.php");
			$result = mysqli_query($mysqli, "SELECT * FROM login");
		?>
			<!-- Contenido para usuarios autenticados -->
			<div class="alert alert-success text-center">
				Bienvenido <strong><?php echo $_SESSION['name']; ?></strong>!
				<a href='logout.php' class="btn btn-danger ms-3">Cerrar sesión</a>
			</div>
			<div class="text-center mt-4">
				<a href='view.php' class="btn btn-primary">Ver y Agregar Productos</a>
			</div>
		<?php
		} else {
		?>
			<!-- Contenido para usuarios no autenticados -->
			<div class="alert alert-warning text-center">
				Debes estar registrado para ver esta página.
			</div>
			<div class="text-center">
				<a href='login.php' class="btn btn-primary me-2">Iniciar sesión</a>
				<a href='register.php' class="btn btn-success">Registrarse</a>
			</div>
		<?php
		}
		?>


		<!-- Bootstrap JS -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>