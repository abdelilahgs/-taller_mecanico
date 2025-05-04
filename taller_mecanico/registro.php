<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Si el usuario ya está logueado, redirigirlo al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Manejo del registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Cifrar la contraseña
    $email = $_POST['email'];

    // Verificar si el usuario ya existe
    $query = "SELECT * FROM usuarios WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "El usuario ya está registrado.";
    } else {
        // Registrar al usuario como cliente (pendiente de aprobación)
        $query = "INSERT INTO usuarios (username, password, email, role, status) VALUES (?, ?, ?, 'cliente', 'pendiente')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $username, $password, $email);
        $stmt->execute();

        // Enviar mensaje de éxito
        $success_message = "Te has registrado con éxito. Tu cuenta está pendiente de aprobación por un administrador.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Crear Cuenta</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?= $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success"><?= $success_message; ?></div>
                        <?php endif; ?>

                        <form action="registro.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
