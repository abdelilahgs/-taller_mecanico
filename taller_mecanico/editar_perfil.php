<?php
include 'conexion.php';  // Conexión a la base de datos
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$mensaje = "";

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);

    $query_check_email = "SELECT * FROM usuarios WHERE email = ? AND id != ?";
    $stmt_check_email = $conn->prepare($query_check_email);
    $stmt_check_email->bind_param("si", $email, $user_id);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        $mensaje = "<div class='alert alert-danger text-center'>Este correo ya está en uso.</div>";
    } else {
        $update_query = "UPDATE usuarios SET username = ?, email = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $username, $email, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $mensaje = "<div class='alert alert-success text-center'>Perfil actualizado correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger text-center'>Error al actualizar el perfil.</div>";
        }
        $update_stmt->close();
    }
    $stmt_check_email->close();

    // Cambio de contraseña
    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_query = "UPDATE usuarios SET password = ? WHERE id = ?";
                $update_password_stmt = $conn->prepare($update_password_query);
                $update_password_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_password_stmt->execute()) {
                    $mensaje = "<div class='alert alert-success text-center'>Contraseña cambiada correctamente.</div>";
                } else {
                    $mensaje = "<div class='alert alert-danger text-center'>Error al cambiar la contraseña.</div>";
                }
                $update_password_stmt->close();
            } else {
                $mensaje = "<div class='alert alert-danger text-center'>Las nuevas contraseñas no coinciden.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-danger text-center'>La contraseña actual es incorrecta.</div>";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
            padding-top: 50px;
        }
        .container {
            max-width: 500px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }
        .btn-save {
            background-color: #27ae60;
            color: white;
        }
        .btn-save:hover {
            background-color: #218c53;
        }
        .btn-cancel {
            background-color: #e74c3c;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #c0392b;
        }
        .btn-back {
            background-color: #3498db;
            color: white;
        }
        .btn-back:hover {
            background-color: #2980b9;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .btn-container {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Perfil</h2>

    <?= $mensaje; ?>

    <form action="editar_perfil.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <hr>

        <h5 class="text-center">Cambiar Contraseña</h5>

        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <input type="password" class="form-control" id="current_password" name="current_password">
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>

        <div class="btn-container">
            <button type="submit" class="btn btn-cancel">💾 Guardar</button>
            <a href="perfil_cliente.php" class="btn btn-warning">❌ Cancelar</a>
            <a href="perfil_cliente.php" class="btn btn-back">🔙 Volver</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
