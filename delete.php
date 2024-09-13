<?php
session_start();

// Redirigir al login si el usuario no ha iniciado sesión
if (!isset($_SESSION['valid'])) {
	header('Location: login.php');
	exit();
}
?>

<?php
// Incluyendo la conexión a la base de datos
include("connection.php");


$id = $_GET['id'];


if ($stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?")) {

	$stmt->bind_param("i", $id);

	$stmt->execute();

	$stmt->close();


	header("Location: view.php");
} else {

	echo "<div class='container mt-5'><div class='alert alert-danger'>Error al eliminar el producto.</div></div>";
}

// Cerrando la conexión
$mysqli->close();
?>
