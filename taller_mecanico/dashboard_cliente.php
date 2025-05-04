<?php
session_start();

// Verificar si el usuario está logueado y si es un cliente
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'cliente') {
    header("Location: login.php"); // Redirigir al login si no está logueado o no es cliente
    exit();
}

// Cerrar sesión si se solicita
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Cliente</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #000000;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ffd700 !important;
        }
        .container {
            max-width: 1100px;
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 40px;
            color: #ffffff;
            background-color: #000000;
            padding: 20px;
            border-radius: 10px;
        }
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
            background-color: #000000;
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
            color: #000000;
            margin-bottom: 10px;
        }
        footer {
            background-color: #000000;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Taller Mecánico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="dashboard_cliente.php?logout=true">Cerrar sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <ul class="panel-menu">
            <li>
                <a href="citas_usuario.php">
                    <i class="bi bi-calendar-check"></i>
                    <br>Mis Citas
                </a>
            </li>
            <li>
                <a href="facturas_usuario.php">
                    <i class="bi bi-file-earmark-richtext"></i>
                    <br>Mis Facturas
                </a>
            </li>
            <li>
                <a href="perfil_cliente.php">
                    <i class="bi bi-person-circle"></i>
                    <br>Mi Perfil
                </a>
            </li>
        </ul>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
