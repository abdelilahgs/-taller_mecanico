<?php
session_start();

// Verificar si el usuario ya está logueado (si ya lo está, redirigirlo a su panel)
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard_cliente.php");
    exit();
}

include 'conexion.php'; // Conexión a la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Cifrar la contraseña
    $role = 'cliente'; // El rol del usuario es 'cliente'
    $status = 'pendiente'; // El cliente estará pendiente hasta ser aprobado por el administrador

    // Verificar si el correo ya está registrado
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Este correo electrónico ya está registrado.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $query = "INSERT INTO usuarios (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $username, $email, $password, $role, $status);
        $stmt->execute();

        $success = "Cuenta registrada exitosamente. Espera la aprobación del administrador.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cuenta - Cliente</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos globales */
        body {
            background: #f0f0f0; /* Fondo más claro */
            display: flex;
            flex-direction: column; /* Los elementos estarán en una columna */
            justify-content: center; /* Centra los elementos verticalmente */
            align-items: center; /* Centra los elementos horizontalmente */
            height: 100vh; /* Asegura que el body ocupe toda la altura de la ventana */
            margin: 0; /* Elimina el margen por defecto */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
        }

        .login-card {
            background-color: #ffffff; /* Fondo blanco para la tarjeta de registro */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            width: 100%;
            max-width: 400px;
            margin-top: 20px; /* Separación entre el botón y la tarjeta */
        }

        .login-card h3 {
            margin-bottom: 20px;
            color: #333; /* Texto en gris oscuro */
        }

        .login-card .form-control {
            background-color: #f9f9f9; /* Fondo claro en los campos */
            color: #333; /* Texto oscuro en los campos */
            border: 1px solid #ccc; /* Borde suave */
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .login-card .form-control:focus {
            border-color: #007bff; /* Borde azul cuando el campo está enfocado */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Sombra azul */
        }

        .login-card .btn {
            background-color: #007bff; /* Botón azul */
            border-radius: 10px;
            padding: 10px;
            width: 100%;
        }

        .login-card .btn:hover {
            background-color: #0056b3; /* Botón azul más oscuro al pasar el ratón */
        }

        footer {
            background-color: #343a40; /* Fondo oscuro para el footer */
            color: white; /* Texto blanco en el footer */
            text-align: center;
            padding: 10px;
            margin-top: auto; /* Asegura que el footer esté al final de la página */
            width: 100%;
        }

        .btn-info {
            background-color: #17a2b8; /* Color azul para el botón de volver */
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 1.1rem;
            width: auto;
            display: block;
            margin-bottom: 20px; /* Separación entre el botón y la tarjeta de login */
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-info:hover {
            background-color: #138496; /* Cambio en el color del botón de volver */
            transform: scale(1.05); /* Animación de expansión */
        }
    </style>
</head>
<body>
    <br>
    <div class="container">
        <!-- Botón para ir a la página principal -->
        <a href="index.php" class="btn btn-info btn-lg">Volver al inicio</a>

        <!-- Tarjeta de registro -->
        <div class="login-card">
            <h3>Registro de Cuenta - Cliente</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Formulario de Registro -->
            <form action="registro_cliente.php" method="POST">
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

                <button type="submit" class="btn btn-primary">Registrar Cuenta</button>
            </form>

            <p class="mt-3">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 Taller Mecánico - Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
