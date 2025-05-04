<?php
session_start();
include 'conexion.php';

// Activar errores detallados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Verificar si ya está logueado
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: dashboard_admin.php");
    } elseif ($_SESSION['role'] == 'cliente') {
        header("Location: dashboard_cliente.php");
    }
    exit();
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT ID, username, role, password, status FROM usuarios WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($user['status'] == 'pendiente') {
            $error_message = "Tu cuenta está pendiente de aprobación.";
        } elseif ($user['status'] == 'bloqueado') {
            $error_message = "Tu cuenta ha sido bloqueada.";
        } elseif ($user['status'] == 'denegado') {
            $error_message = "Tu cuenta ha sido rechazada.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: " . ($user['role'] == 'admin' ? 'dashboard_admin.php' : 'dashboard_cliente.php'));
            exit();
        } else {
            $error_message = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error_message = "Usuario no encontrado.";
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
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
            background-color: #ffffff; /* Fondo blanco para la tarjeta de inicio de sesión */
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
    <br>
    <div class="container">
        <!-- Botón para ir a la página principal -->
        <a href="index.php" class="btn btn-info btn-lg">Volver al inicio</a>

        <!-- Tarjeta de login -->
        <div class="login-card">
            <h3>Iniciar Sesión</h3>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>

            <div class="mt-3 text-center">
                <p class="mb-0">¿No tienes cuenta? <a href="registro_cliente.php">Regístrate</a></p>
            </div>
        </div>
    </div>

    <!-- Mostrar alerta si hay error -->
    <?php if (!empty($error_message)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error de inicio de sesión',
                text: '<?= $error_message; ?>',
                confirmButtonColor: '#d33'
            });
        </script>
    <?php endif; ?>

    <!-- Footer -->
    <footer>
        &copy; 2025 Taller Mecánico - Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
