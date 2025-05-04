<?php
session_start();

// Verificar si ya está logueado
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin_panel.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Validar el usuario y la contraseña
    if ($usuario === 'admin' && $password === '123') {
        $_SESSION['admin'] = true;  // Iniciar sesión
        header("Location: admin_panel.php");  // Redirigir al panel de administración
        exit;
    } else {
        // Si el usuario o la contraseña son incorrectos
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador</title>    
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-dark text-white d-flex justify-content-center align-items-center vh-100">
    <a href="index.php" class="position-absolute top-0 start-0 m-1 btn btn-dark btn-lg">
        🔙 Volver a Inicio
    </a>

    <div class="card p-4 text-dark" style="width: 22rem;">
        <h3 class="text-center text-warning">🔐 Acceso Supervisor</h3>

        <?php if (isset($error)): ?>
            <script>
                Swal.fire({
                    title: "Error",
                    text: "<?php echo $error; ?>",
                    icon: "error",
                    confirmButtonColor: "#ff8800",
                    confirmButtonText: "Intentar de nuevo"
                });
            </script>
        <?php endif; ?>

        <form method="POST" action="panel_login.php">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" placeholder="Nombre de usuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Ingresar</button>
        </form>
    </div>

</body>
</html>
