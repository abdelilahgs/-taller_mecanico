<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta la base de datos en la tabla `usuarios_admin`
    $stmt = $conn->prepare("SELECT id, usuario, password FROM usuarios_admin WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Verificar credenciales
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = true;
        $_SESSION['usuario'] = $admin['usuario'];
        header("Location: admin_panel.php");
        exit;
    } else {
        header("Location: login.php?error=invalid");
        exit;
    }
}
?>
