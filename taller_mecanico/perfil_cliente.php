<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir a la página de login
    exit();
}

// Obtener los datos del usuario logueado
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Cliente</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            padding-top: 40px;
        }

        .container {
            max-width: 800px;
            margin-top: 30px;
        }

        .profile-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h3 {
            font-size: 2rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .profile-header p {
            font-size: 1rem;
            color: #7f8c8d;
        }

        .profile-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #ecf0f1;
            border-radius: 8px;
        }

        .profile-info .row {
            margin-bottom: 10px;
        }

        .profile-info span {
            font-weight: 600;
            color: #34495e;
        }

        .profile-info p {
            font-size: 1.1rem;
            color: #7f8c8d;
        }

        .btn-custom {
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1.1rem;
        }

        .btn-back {
            background-color: #3498db;
            color: #fff;
            border: none;
        }

        .btn-edit {
            background-color: #f39c12;
            color: #fff;
            border: none;
        }
        
        .btn-back:hover, .btn-edit:hover {
            background-color: #2980b9;
        }

        .footer {
            background-color: #ecf0f1;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }

    </style>
</head>
<body>

<div class="container">
    <!-- Botón para regresar al dashboard -->
    <a href="dashboard_cliente.php" class="btn btn-custom btn-back mb-4">Volver al Dashboard</a>

    <div class="profile-card">
        <div class="profile-header">
            <h3>Bienvenido, <?= htmlspecialchars($user['username']); ?></h3>
            <p class="text-muted">Aquí puedes ver y actualizar tus datos personales</p>
        </div>

        <div class="profile-info">
            <div class="row">
                <div class="col-md-6">
                    <span>Nombre de Usuario:</span>
                    <p><?= htmlspecialchars($user['username']); ?></p>
                </div>
                <div class="col-md-6">
                    <span>Correo Electrónico:</span>
                    <p><?= htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>

        <div class="profile-info">
            <div class="row">
                <div class="col-md-6">
                    <span>Rol:</span>
                    <p><?= ucfirst(htmlspecialchars($user['role'])); ?></p>
                </div>
                <div class="col-md-6">
                    <span>Estado:</span>
                    <p><?= ucfirst(htmlspecialchars($user['estado'])); ?></p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <!-- Botón para editar el perfil -->
            <a href="editar_perfil.php" class="btn btn-custom btn-edit">Editar Perfil</a>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
