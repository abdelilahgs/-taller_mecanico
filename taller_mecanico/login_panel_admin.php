<?php
session_start();
include 'conexion.php';  // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT ID, Nombre, password, Rol, Estado FROM usuarios WHERE Nombre = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Verificar si el usuario está activo
            if ($user['Estado'] == 'Activo') {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $user['ID'];
                $_SESSION['usuario_nombre'] = $user['Nombre'];
                $_SESSION['usuario_rol'] = $user['Rol'];

                // Redirigir al panel de administración
                header("Location: admin_panel.php");
                exit();
            } else {
                echo "<script>alert('⚠️ Tu cuenta aún no está activada.');</script>";
            }
        } else {
            echo "<script>alert('⚠️ Contraseña incorrecta');</script>";
        }
    } else {
        echo "<script>alert('⚠️ Usuario no encontrado');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Formulario de login -->
<form action="login_panel_admin.php" method="POST">
    <label for="usuario">Usuario:</label>
    <input type="text" name="usuario" required>
    
    <label for="password">Contraseña:</label>
    <input type="password" name="password" required>
    
    <button type="submit">Iniciar sesión</button>
</form>
