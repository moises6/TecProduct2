<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['valid'])) {
    header('Location: login.php');
    exit();
}

// Regenerar ID de sesión por seguridad
session_regenerate_id(true);

// Incluyendo la conexión a la base de datos
include("connection.php");

// Procesar el formulario cuando se envíe
if (isset($_POST['submit'])) {
    $category_name = mysqli_real_escape_string($mysqli, $_POST['category_name']);

    // Validar que el campo no esté vacío
    if (empty($category_name)) {
        $error = "El nombre de la categoría no puede estar vacío.";
    } else {
        // Insertar la nueva categoría en la base de datos
        $stmt = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $success = "Categoría añadida exitosamente.";
        } else {
            $error = "Error al añadir la categoría.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Añadir Categoría</title>
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
                <h3>Añadir Nueva Categoría</h3>
            </div>
            <div class="card-body">
                <!-- Mostrar mensajes de éxito o error -->
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } elseif (isset($success)) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php } ?>

                <!-- Formulario para añadir categoría -->
                <form action="add_category.php" method="post">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Nombre de la Categoría</label>
                        <input type="text" name="category_name" class="form-control" id="category_name" placeholder="Introduce el nombre de la categoría" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Añadir Categoría</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>