<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos
include 'header.php';
?>

<div class="container">
    <!-- Botón para ir a atras(admin_panel) -->
    <a href="admin_panel.php" class="btn btn-info btn-lg mx-1 mb-3">Volver</a>

    <h3 class="text-center mb-4">Registros de Citas</h3>

    <!-- Mostrar las citas registradas en la base de datos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehículo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta para obtener los registros
            $query = "SELECT * FROM citas";
            $result = $conn->query($query);

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr id="row-<?= $row['ID'] ?>">
                        <td><?= $row['ID'] ?></td>
                        <td><?= $row['Vehiculo_ID'] ?></td>
                        <td><?= $row['Fecha_Cita'] ?></td>
                        <td><?= $row['Estado'] ?></td>
                        <td>
                            <!-- Botón para eliminar -->
                            <button class="btn btn-danger btn-sm" onclick="eliminar(<?= $row['ID'] ?>, 'cita')">Eliminar</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No hay citas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <!-- Botones -->
    <div class="d-flex justify-content-between mb-3">
    <!-- Botón para ir a reparaciones -->
    <a href="reparaciones_registro.php" class="btn btn-secondary">Registro de Reparaciones</a>
    <!-- Botón para ir a facturaciones -->
    <a href="facturaciones_registro.php" class="btn btn-secondary">Registro de Facturaciones</a>
    </div>
</div>

<!-- Incluir SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Función de eliminar usando AJAX
function eliminar(id, tipo) {
    // Confirmar antes de eliminar
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Este registro será eliminado permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar solicitud AJAX para eliminar
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "eliminar_datos.php?id=" + id + "&tipo=" + tipo, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);

                    // Verificar si el servidor retornó un valor de éxito
                    if (response.status === 'success') {
                        // Mostrar una alerta personalizada de éxito
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El registro ha sido eliminado con éxito.',
                            icon: 'success',
                            timer: 2000,  // Desaparece después de 2 segundos
                            showConfirmButton: false,
                            didClose: () => {
                                // Eliminar la fila de la tabla después de la eliminación
                                document.getElementById("row-" + id).remove();
                            }
                        });
                    } else {
                        // Si hubo un error, mostrar una alerta de error personalizada
                        Swal.fire({
                            title: '¡Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo'
                        });
                    }
                }
            };
            xhr.send();
        }
    });
}
</script>

<?php include 'footer.php'; ?>
