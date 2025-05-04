<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reparaciones - Taller Mecánico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Arial', sans-serif;
        }
        .hero {
            background: url('img/reparaciones.jpg') center/cover no-repeat;
            height: 40vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        .card {
            transition: transform 0.3s ease-in-out;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-custom {
            background-color: #ff5722;
            color: white;
            border-radius: 20px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: #e64a19;
        }
        .container {
            padding-top: 20px;
            padding-bottom: 20px;
        }
    </style>
    
</head>
<body>


<div class="container">
    <h2 class="text-center mt-4">Nuestros Servicios</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="card-title">🔧 Mecánica General</h5>
                <p class="card-text">Reparación y mantenimiento de motores, frenos y transmisión.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="card-title">⚡ Electricidad Automotriz</h5>
                <p class="card-text">Diagnóstico y reparación de sistemas eléctricos y baterías.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="card-title">🛠️ Diagnóstico Computarizado</h5>
                <p class="card-text">Revisión avanzada con tecnología de punta.</p>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5">
        <a href="agregar_reparacion.php" class="btn btn-custom btn-lg">Agendar una Reparación</a>
    </div>
   

    <h2 class="text-center mt-5">Mantenimiento Básico de Vehículos</h2>
    <p class="text-center">Realizar un mantenimiento regular a tu vehículo ayuda a prolongar su vida útil y evitar problemas mayores.</p>

    
    <div class="row justify-content-center">
         <!-- motor -->
        <div class="col-md-4">
            <div class="card border-dark shadow-sm p-3">
                <img src="img/motor.jpg" class="card-img-top" alt="Motor del coche">
                <div class="card-body">
                    <h5 class="card-title">Motor</h5>
                    <p class="card-text">Revisar el nivel de aceite y cambiarlo cada 10,000 km para evitar desgaste.</p>
                </div>
            </div>
        </div>
         <!-- frenos -->
        <div class="col-md-4">
            <div class="card border-danger shadow-sm p-3">
                <img src="img/frenos.jpg" class="card-img-top" alt="Frenos del coche">
                <div class="card-body">
                    <h5 class="card-title">Frenos</h5>
                    <p class="card-text">Verificar el estado de las pastillas y discos cada 20,000 km para una conducción segura.</p>
                </div>
            </div>
        </div>
         <!-- bateria -->
        <div class="col-md-4">
            <div class="card border-primary shadow-sm p-3">
                <img src="img/bateria.jpg" class="card-img-top" alt="Batería del coche">
                <div class="card-body">
                    <h5 class="card-title">Batería</h5>
                    <p class="card-text">Reemplazar cada 3-5 años y asegurarse de que los bornes estén limpios y sin corrosión.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
         <!-- neumaticos -->
        <div class="col-md-4">
            <div class="card border-success shadow-sm p-3">
                <img src="img/neumaticos.jpg" class="card-img-top" alt="Neumáticos del coche">
                <div class="card-body">
                    <h5 class="card-title">Neumáticos</h5>
                    <p class="card-text">Verificar la presión y la profundidad del dibujo cada mes para un mejor rendimiento.</p>
                </div>
            </div>
        </div>
         <!-- luces -->
        <div class="col-md-4">
            <div class="card border-warning shadow-sm p-3">
                <img src="img/luces.jpg" class="card-img-top" alt="Luces del coche">
                <div class="card-body">
                    <h5 class="card-title">Luces</h5>
                    <p class="card-text">Comprobar el correcto funcionamiento de todas las luces y reemplazar las fundidas.</p>
                </div>
            </div>
        </div>
                    <!-- aceite -->
        <div class="col-md-4">
            <div class="card border-danger shadow-sm p-3">
                <img src="img/aceite.jpg" class="card-img-top" alt="Aceite">
                <div class="card-body">
                    <h5 class="card-title">Aceite</h5>
                    <p class="card-text">El cambio de aceite y filtro debe hacerse cada año o cada 10.000 km.</p>
                </div>
            </div>
        </div>
    </div>

        <div class="row justify-content-center mt-4">
            <!-- Correa de distribución -->
            <div class="col-md-4">
                <div class="card border-info shadow-sm p-3">
                    <img src="img/correa.jpg" class="card-img-top" alt="Correa de distribución">
                    <div class="card-body">
                        <h5 class="card-title">Correa de Distribución</h5>
                        <p class="card-text">Sustituir entre 60,000 y 100,000 km para evitar fallos graves en el motor.</p>
                    </div>
                </div>
            </div>

            <!-- Amortiguadores -->
            <div class="col-md-4">
                <div class="card border-secondary shadow-sm p-3">
                    <img src="img/amortiguadores.jpg" class="card-img-top" alt="Amortiguadores">
                    <div class="card-body">
                        <h5 class="card-title">Amortiguadores</h5>
                        <p class="card-text">Revisar cada 50,000 km para mantener la estabilidad y confort del vehículo.</p>
                    </div>
                </div>
            </div>

            <!-- Filtros de aire -->
            <div class="col-md-4">
                <div class="card border-primary shadow-sm p-3">
                    <img src="img/filtro_aire.jpg" class="card-img-top" alt="Filtro de aire">
                    <div class="card-body">
                        <h5 class="card-title">Filtro de Aire</h5>
                        <p class="card-text">Sustituir cada 15,000 km para un mejor rendimiento del motor.</p>
                    </div>
                </div>
            </div>
        </div>
    
   
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
