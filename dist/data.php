<?php
// getUserData.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "roxana";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID del usuario desde la solicitud GET
$user_id = $_GET['id'];

// Consulta a la base de datos
$sql = "SELECT * FROM usuarios WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrar datos del usuario
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Nombre: " . $row["nombre"]. " - Email: " . $row["email"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
