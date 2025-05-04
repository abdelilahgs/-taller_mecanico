<?php include 'header.php'; ?>



<style>
    body {
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        font-family: 'Arial', sans-serif;
    }

    .hero {
        background: url('/img/fototaller.jpg') center/cover no-repeat;
        height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
    }

    .card {
        border-radius: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        text-align: center;
    }

    .btn {
        border-radius: 20px;
        font-weight: bold;
    }

    .container h2 {
        font-weight: bold;
        color: #343a40;
    }
</style>

<div class="hero">
    ¡Bienvenido a nuestro Taller Mecánico!
</div>

<div class="container text-center">
    <br>
    <h2>Servicios Profesionales de Mecánica</h2>
    <p>Ofrecemos reparación y mantenimiento de vehículos con la mejor calidad y rapidez.</p>

    <div class="row mt-4 justify-content-center">
    <!-- Tarjeta de Reparaciones -->
    <div class="col-md-3">
        <!-- Envolvemos toda la tarjeta con un enlace -->
        <a href="reparaciones.php" class="text-decoration-none">
            <div class="card border-dark shadow-sm p-2">
                <div class="card-body">
                    <h6 class="card-title">🔧 Reparaciones</h6>
                    <p class="card-text small">Realizamos todo tipo de reparaciones mecánicas y eléctricas.</p>
                    <!-- Eliminamos el botón, ya no es necesario -->
                </div>
            </div>
        </a>
    </div>

    <!-- Tarjeta de Citas -->
    <div class="col-md-3">
        <!-- Envolvemos toda la tarjeta con un enlace -->
        <a href="citas.php" class="text-decoration-none">
            <div class="card border-success shadow-sm p-2">
                <div class="card-body">
                    <h6 class="card-title">📅 Citas</h6>
                    <p class="card-text small">Agenda tu cita fácilmente y sin esperas.</p>
                    <!-- Eliminamos el botón, ya no es necesario -->
                </div>
            </div>
        </a>
    </div>

    <!-- Tarjeta de Facturación -->
    <div class="col-md-3">
        <!-- Envolvemos toda la tarjeta con un enlace -->
        <a href="facturacion.php" class="text-decoration-none">
            <div class="card border-warning shadow-sm p-2">
                <div class="card-body">
                    <h6 class="card-title">💰 Facturación</h6>
                    <p class="card-text small">Consulta y gestiona tus facturas de manera sencilla.</p>
                    <!-- Eliminamos el botón, ya no es necesario -->
                </div>
            </div>
        </a>
    </div>

    <!-- Tarjeta de Vehículos -->
    <div class="col-md-3">
        <!-- Envolvemos toda la tarjeta con un enlace -->
        <a href="vehiculos.php" class="text-decoration-none">
            <div class="card border-info shadow-sm p-2">
                <div class="card-body">
                    <h6 class="card-title">🚗 Vehículos</h6>
                    <p class="card-text small">Explora los vehículos disponibles en nuestro taller.</p>
                    <!-- Eliminamos el botón, ya no es necesario -->
                </div>
            </div>
        </a>
    </div>
</div>

</div>

<?php include 'footer.php'; ?>
