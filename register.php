<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registro</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container mt-5">
		<a href="index.php" class="btn btn-secondary mb-4">Inicio</a>

		<?php
		include("connection.php");

		if (isset($_POST['submit'])) {
			$name = mysqli_real_escape_string($mysqli, $_POST['name']);
			$email = mysqli_real_escape_string($mysqli, $_POST['email']);
			$user = mysqli_real_escape_string($mysqli, $_POST['username']);
			$pass = mysqli_real_escape_string($mysqli, $_POST['password']);

			// Validación de campos vacíos
			if ($name == "" || $email == "" || $user == "" || $pass == "") {
				echo "<div class='alert alert-danger'>Todos los campos son obligatorios. Por favor, complete todos los campos.</div>";
				echo "<a href='register.php' class='btn btn-secondary'>Volver</a>";
			} else {
				// Encriptar la contraseña usando password_hash
				$passwordHash = password_hash($pass, PASSWORD_DEFAULT);

				// Insertar datos en la base de datos
				if (mysqli_query($mysqli, "INSERT INTO login(name, email, username, password) VALUES('$name', '$email', '$user', '$passwordHash')")) {
					echo "<div class='alert alert-success'>Registro exitoso</div>";
					echo "<a href='login.php' class='btn btn-primary'>Iniciar sesión</a>";
				} else {
					echo "<div class='alert alert-danger'>No se pudo completar el registro. Inténtalo de nuevo.</div>";
				}
			}
		} else {
		?>
			<div class="card">
				<div class="card-body">
					<h2 class="text-center mb-4">Registro</h2>
					<form name="form1" method="post" action="">
						<div class="mb-3">
							<label for="name" class="form-label">Nombre Completo</label>
							<input type="text" class="form-control" id="name" name="name">
						</div>
						<div class="mb-3">
							<label for="email" class="form-label">Correo Electrónico</label>
							<input type="email" class="form-control" id="email" name="email">
						</div>
						<div class="mb-3">
							<label for="username" class="form-label">Usuario</label>
							<input type="text" class="form-control" id="username" name="username">
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">Contraseña</label>
							<input type="password" class="form-control" id="password" name="password">
						</div>
						<button type="submit" name="submit" class="btn btn-success w-100">Registrar</button>
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