<?php
include 'config.php';  // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_vehiculo = $_POST['id'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $matricula = $_POST['matricula'];
    $estado = $_POST['estado'];

    // Actualizar el vehículo
    $query = "UPDATE Vehiculos SET Marca='$marca', Modelo='$modelo', Año='$anio', Matricula='$matricula', Estado='$estado' WHERE ID=$id_vehiculo";
    
    if ($conn->query($query) === TRUE) {
        echo "<div class='alert alert-success'>Vehículo actualizado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Obtener los detalles del vehículo para mostrar en el formulario
$id = $_GET['id'];
$query = "SELECT * FROM Vehiculos WHERE ID=$id";
$result = $conn->query($query);
$vehiculo = $result->fetch_assoc();
?>

<h2>Editar Vehículo</h2>

<form method="POST" action="editar_vehiculo.php">
    <input type="hidden" name="id" value="<?= $vehiculo['ID'] ?>">
    <div class="mb-3">
        <label for="marca" class="form-label">Marca</label>
        <input type="text" class="form-control" name="marca" value="<?= $vehiculo['Marca'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="modelo" class="form-label">Modelo</label>
        <input type="text" class="form-control" name="modelo" value="<?= $vehiculo['Modelo'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="anio" class="form-label">Año</label>
        <input type="number" class="form-control" name="anio" value="<?= $vehiculo['Año'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="matricula" class="form-label">Matrícula</label>
        <input type="text" class="form-control" name="matricula" value="<?= $vehiculo['Matricula'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-control" name="estado" required>
            <option value="Disponible" <?= ($vehiculo['Estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
            <option value="En Reparación" <?= ($vehiculo['Estado'] == 'En Reparación') ? 'selected' : '' ?>>En Reparación</option>
            <option value="Reparado" <?= ($vehiculo['Estado'] == 'Reparado') ? 'selected' : '' ?>>Reparado</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Vehículo</button>
</form>
