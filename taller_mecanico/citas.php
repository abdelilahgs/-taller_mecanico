<?php
include 'conexion.php'; // Conexión a la base de datos
include 'header.php';

// Procesar la solicitud de agendar una nueva cita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'] ?? null;
    $cliente_nombre = $_POST['cliente'] ?? null;

    if (!$fecha || !$cliente_nombre) {
        echo "<script>alert('Todos los campos son obligatorios');</script>";
    } else {
        // Insertar la cita directamente con el nombre del cliente
        $sql_insert_cita = "INSERT INTO Citas (Fecha_Cita, Usuario_ID, Estado) VALUES (?, ?, 'Pendiente')";
        $stmt_insert_cita = $conn->prepare($sql_insert_cita);
        if (!$stmt_insert_cita) {
            die("Error en prepare (insertar cita): " . $conn->error);
        }
        $stmt_insert_cita->bind_param("ss", $fecha, $cliente_nombre);

        // Alerta de guardado con exito
        if ($stmt_insert_cita->execute()) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    Swal.fire({
                        title: '¡Cita Guardada!',
                        text: 'Tu cita ha sido agendada con éxito.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'citas.php';
                    });
                </script>";
        } else {
            // Alerta de error
            echo "<script>alert('Error al guardar la cita: " . $stmt_insert_cita->error . "');</script>";
        }
        $stmt_insert_cita->close();
    }
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <button onclick="history.back()" class="btn btn-secondary">🔙 Volver Atrás</button>
        <button onclick="location.reload()" class="btn btn-info">🔄 Refrescar Página</button>
    </div>

    <h2 class="text-center">📅 Agendar Citas</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-primary shadow-sm p-3">
                <div class="card-body">
                    <h4 class="card-title text-center">📌 Agendar Nueva Cita</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="cliente" class="form-label">👤 Nombre del Cliente:</label>
                            <input type="text" id="cliente" name="cliente" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">📅 Fecha de la cita:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">💾 Agendar Cita</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
