<?php
include 'conexion.php'; // Conexión a la base de datos
include 'header.php'; // Encabezado

$mensaje = "";

// Procesar formulario para agregar vehículo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $matricula = $_POST['matricula'];
    $estado = $_POST['estado'];
    $fecha_registro = date('Y-m-d');

    $sql = "INSERT INTO vehiculos (Marca, Modelo, Año, Matricula, Estado, Fecha_Registro) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $marca, $modelo, $anio, $matricula, $estado, $fecha_registro);

    if ($stmt->execute()) {
        // Mensaje de éxito con SweetAlert
        $mensaje = "<script>
            Swal.fire({
                title: '✅ Vehículo agregado',
                text: 'El vehículo se ha registrado correctamente.',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href='vehiculos.php';
            });
        </script>";
    } else {
        // Mensaje de error con SweetAlert
        $mensaje = "<script>
            Swal.fire({
                title: '❌ Error al registrar',
                text: 'No se pudo registrar el vehículo. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Reintentar'
            });
        </script>";
    }
    $stmt->close();
}

// Verificar si hay una búsqueda
$mostrar_tabla = false;
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $conn->real_escape_string($_GET['buscar']);
    $query = "SELECT ID, Marca, Modelo, Año, Matricula, Estado, Fecha_Registro 
              FROM vehiculos 
              WHERE Matricula LIKE '%$busqueda%'";
    $result = $conn->query($query);
    $mostrar_tabla = true;
}

?>

<div class="container mt-4">
    <h2 class="text-center">🚗 Gestión de Vehículos</h2>

    <!-- Formulario de búsqueda -->
    <div class="container mt-3">
        <form method="GET" action="vehiculos.php" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="🔍 Buscar por matrícula" value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>" required>
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="vehiculos.php" class="btn btn-secondary ms-2">🔄 Limpiar</a>
        </form>
    </div>

    <!-- Formulario para añadir vehículo -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center">➕ Añadir Vehículo</h5>
                    <form action="vehiculos.php" method="POST">
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca:</label>
                            <input type="text" name="marca" id="marca" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo" class="form-label">Modelo:</label>
                            <input type="text" name="modelo" id="modelo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año:</label>
                            <input type="number" name="anio" id="anio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matrícula:</label>
                            <input type="text" name="matricula" id="matricula" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado:</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="Disponible">Disponible</option>
                                <option value="En Reparación">En Reparación</option>
                                <option value="Reparado">Reparado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">💾 Guardar Vehículo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de vehículos (Solo se muestra si hay búsqueda) -->
    <?php if ($mostrar_tabla): ?>
        <table class="table table-striped table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Matrícula</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['Marca']}</td>
                            <td>{$row['Modelo']}</td>
                            <td>{$row['Año']}</td>
                            <td>{$row['Matricula']}</td>
                            <td>{$row['Estado']}</td>
                            <td>{$row['Fecha_Registro']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron resultados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php
echo $mensaje; // Mostrar alertas
$conn->close();
include 'footer.php';
?>
