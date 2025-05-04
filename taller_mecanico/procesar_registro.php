<?php
include 'config.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($password)) {
        die("❌ Error: Todos los campos son obligatorios.");
    }

    // Verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT ID FROM Usuarios WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("❌ Error: El correo ya está registrado.");
    }
    $stmt->close();

    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario en la base de datos con estado 'Pendiente'
    $stmt = $conn->prepare("INSERT INTO Usuarios (Nombre, Email, Contraseña, Rol, Estado) VALUES (?, ?, ?, 'Cliente', 'Pendiente')");
    $stmt->bind_param("sss", $nombre, $email, $password_hash);

    if ($stmt->execute()) {
        echo "✅ Registro exitoso. Espera la aprobación del administrador.";
    } else {
        echo "❌ Error al registrar usuario.";
    }

    $stmt->close();
    $conn->close();
}
?>
``
