<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller Mecánico</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">🏠 Taller Mecánico</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="reparaciones.php">🔧 Reparaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="citas.php">📅 Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="facturacion.php">🧾 Facturación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vehiculos.php">🚗 Vehículos</a>
                </li>

                <!-- Botón de acceso directo al Panel de Administración -->
                <li class="nav-item ms-3">
                    <a href="admin_panel.php" class="btn btn-primary">📊 Panel de Administración</a>
                </li>

                <!-- Botón de Iniciar / Cerrar Sesión -->
                <li class="nav-item ms-3">
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <a href="logout.php" class="btn btn-danger">🚪 Cerrar Sesión</a>
                    <?php else : ?>
                        <a href="login.php" class="btn btn-outline-light">🔑 Iniciar Sesión</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
