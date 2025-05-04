<?php
session_start();

// Verificar si el usuario es administrador, si no redirigir al login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirigir al login si no está logueado o no es admin
    exit();
}

// Cerrar sesión si se solicita
if (isset($_GET['logout'])) {
    session_destroy();  // Destruir todas las variables de sesión
    header("Location: login.php");  // Redirigir a la página de login
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Taller Mecánico</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo para el fondo */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        /* Barra de navegación (Header) */
        .navbar {
            background-color: #000000; /* Cambiado a negro */
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #ffd700 !important; /* Color dorado al pasar el ratón */
        }

        .navbar-toggler-icon {
            background-color: white; /* Ícono del menú en color blanco */
        }

        /* Estilo para el contenido */
        .container {
            max-width: 1100px;
            margin-top: 50px;
        }

        /* Título centrado y color similar al header */
        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 40px;
            color: #ffffff; /* Color blanco */
            background-color: #000000; /* Cambiado a negro */
            padding: 20px;
            border-radius: 10px;
        }

        /* Estilo del menú del panel */
        .panel-menu {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .panel-menu li {
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            width: 200px;
            text-align: center;
            padding: 20px;
            transition: 0.3s;
        }

        .panel-menu li:hover {
            background-color: #000000; /* Cambiado a negro */
            color: white;
            transform: translateY(-5px);
        }

        .panel-menu a {
            text-decoration: none;
            color: #333;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .panel-menu li:hover a {
            color: white;
        }

        .panel-menu i {
            font-size: 3rem;
            color: #000000; /* Cambiado a negro */
            margin-bottom: 10px;
        }

        footer {
            background-color: #000000; /* Cambiado a negro */
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 50px;
        }
        
    </style>
</head>
<body>

    <!-- Barra de navegación (Header) -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Taller Mecánico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Mostrar solo el botón de cerrar sesión si el usuario está logueado -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="dashboard_admin.php?logout=true">Cerrar sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <h2>Bienvenido al Panel de Administración</h2>

        <ul class="panel-menu">
            <li>
                <a href="usuarios_registro.php">
                    <i class="bi bi-person-fill" style="font-size: 3rem; color:rgb(0, 0, 0); margin-bottom: 10px;"></i>
                    <br>Gestión de Usuarios
                </a>
            </li>
            <li>
                <a href="vehiculos_admin.php">
                    <i class="bi bi-car-front-fill" style="font-size: 3rem; color:rgb(0, 0, 0); margin-bottom: 10px;"></i>
                    <br>Gestión de Vehículos
                </a>
            </li>
            <li>
                <a href="reparacion_admin.php">
                    <i class="bi bi-wrench" style="font-size: 3rem; color:rgb(0, 0, 0); margin-bottom: 10px;"></i>
                    <br>Gestión de Reparaciones
                </a>
            </li>
            <li>
                <a href="citas_admin.php">
                    <i class="bi bi-calendar-check" style="font-size: 3rem; color:rgb(0, 0, 0); margin-bottom: 10px;"></i>
                    <br>Gestión de Citas
                </a>
            </li>
            <li>
                <a href="facturas_admin.php">
                <i class="bi bi-file-earmark-richtext" style="font-size: 3rem; color:rgb(0, 0, 0); margin-bottom: 10px;"></i>
                <br>Facturación
                </a>
            </li>
        </ul>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
