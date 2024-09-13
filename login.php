<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Iniciar Sesión</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container mt-5">
		<a href="index.php" class="btn btn-secondary mb-4">Inicio</a>
		<?php
		include("connection.php");

		if (isset($_POST['submit'])) {
			$user = mysqli_real_escape_string($mysqli, $_POST['username']);
			$pass = mysqli_real_escape_string($mysqli, $_POST['password']);

			// Verificando si los campos están vacíos
			if ($user == "" || $pass == "") {
				echo "<div class='alert alert-danger'>El campo de usuario o contraseña está vacío.</div>";
				echo "<a href='login.php' class='btn btn-secondary'>Volver</a>";
			} else {
				// Seleccionando el usuario y la contraseña desde la base de datos
				$result = mysqli_query($mysqli, "SELECT * FROM login WHERE username='$user'")
					or die("No se pudo ejecutar la consulta.");

				$row = mysqli_fetch_assoc($result);

				// Verificar si el usuario existe y si la contraseña es válida
				if (is_array($row) && !empty($row) && password_verify($pass, $row['password'])) {
					$_SESSION['valid'] = $row['username'];
					$_SESSION['name'] = $row['name'];
					$_SESSION['id'] = $row['id'];

					// Redireccionar a la página principal después del inicio de sesión
					header('Location: index.php');
					exit();
				} else {
					echo "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
					echo "<a href='login.php' class='btn btn-secondary'>Volver</a>";
				}
			}
		} else {
		?>
			<div class="card">
				<div class="card-body">
					<h2 class="text-center">Iniciar Sesión</h2>
					<form name="form1" method="post" action="" class="mt-4">
						<div class="mb-3">
							<label for="username" class="form-label">Usuario</label>
							<input type="text" class="form-control" id="username" name="username" required>
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">Contraseña</label>
							<input type="password" class="form-control" id="password" name="password" required>
						</div>
						<button type="submit" name="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
					</form>
				</div>
			</div>
		<?php
		}
		?>
	</div>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>