<?php
session_start();
include 'config.php'; // Asegúrate de incluir la configuración de la base de datos

// Tiempo máximo de inactividad en segundos (5 minutos)
$inactive_time = 5 * 60; // 5 minutos en segundos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin'])) {
    header("Location: panel_login.php"); // Redirigir al panel_login si no está autenticado
    exit();
}

// Verificar el tiempo de inactividad
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_time) {
    // Si ha pasado más de 5 minutos desde la última actividad, redirigir al login
    session_unset(); // Limpiar la sesión
    session_destroy(); // Destruir la sesión
    header("Location: panel_login.php"); // Redirigir al panel_login
    exit();
}

// Actualizar el tiempo de actividad
$_SESSION['last_activity'] = time();

// Verificar si el usuario es administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: panel_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <!-- Agrega el enlace a la hoja de estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        .card {
            width: 250px;
            height: 300px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            text-align: center;
            padding: 20px;
        }
        .card-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .btn-custom {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 10px;
        }
        .container {
            margin-top: 30px;
        }
        h3 {
            font-size: 28px;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">

    <?php include 'header.php'; ?> <!-- Incluir el encabezado -->

    <!-- Zona de navegación con botones -->
    <div class="container-fluid bg-white p-3">
        <div class="d-flex justify-content-between">
            <h2>Bienvenido al Panel de Administración</h2>
        </div>
    </div>

    <!-- Contenido de la página de administración -->
    <div class="container mt-4">
    <h3 class="text-center">Administración de Taller</h3>
    <p class="text-center">Gestiona los registros de reparaciones, citas, vehículos y facturaciones.</p>

    <div class="row justify-content-center mt-4">
    <div class="col-12 col-md-3 mb-3">
        <!-- Envolvemos toda la tarjeta con el enlace -->
        <a href="reparaciones_registro.php" class="text-decoration-none">
            <div class="card shadow-lg border-light rounded">
                <div class="card-body text-center">
                    <h4 class="card-title">Reparaciones</h4>
                    <p class="card-text">Administra las reparaciones del taller.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-3 mb-3">
        <!-- Envolvemos toda la tarjeta con el enlace -->
        <a href="citas_registro.php" class="text-decoration-none">
            <div class="card shadow-lg border-light rounded">
                <div class="card-body text-center">
                    <h4 class="card-title">Citas</h4>
                    <p class="card-text">Gestiona las citas de los clientes.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-3 mb-3">
        <!-- Envolvemos toda la tarjeta con el enlace -->
        <a href="facturaciones_registro.php" class="text-decoration-none">
            <div class="card shadow-lg border-light rounded">
                <div class="card-body text-center">
                    <h4 class="card-title">Facturación</h4>
                    <p class="card-text">Administra las facturas del taller.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-md-3 mb-3">
        <!-- Envolvemos toda la tarjeta con el enlace -->
        <a href="vehiculos_registro.php" class="text-decoration-none">
            <div class="card shadow-lg border-light rounded">
                <div class="card-body text-center">
                    <h4 class="card-title">Vehículos</h4>
                    <p class="card-text">Registra y gestiona los vehículos.</p>
                </div>
            </div>
        </a>
    </div>
</div>

</div>

    <?php include 'footer.php'; ?> <!-- Incluir el pie de página -->

    <!-- Agregar scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
