<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos
include 'header.php';

// Verificar si el usuario está logueado y tiene el rol de administrador
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
?>

<div class="container">
    <!-- Botón para ir a atrás (admin_panel) -->
    <a href="admin_panel.php" class="btn btn-info btn-lg mx-1 mb-3">Volver</a>

    <!-- Mostrar enlace al Dashboard Admin solo si el usuario es admin -->
    <?php if ($isAdmin): ?>
        <div class="text-center mb-3">
            <a href="dashboard_admin.php" class="btn btn-success">Ir al Dashboard Admin</a>
        </div>
    <?php endif; ?>

    <h3 class="text-center mb-4">Registros de Reparaciones</h3>

    <!-- Mostrar las reparaciones registradas en la base de datos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehículo</th>
                <th>Descripción</th>
                <th>Costo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta para obtener los registros
            $query = "SELECT * FROM reparaciones";
            $result = $conn->query($query);

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr id="row-<?= $row['ID'] ?>">
                        <td><?= $row['ID'] ?></td>
                        <td><?= $row['Vehiculo_ID'] ?></td>
                        <td><?= $row['Descripcion'] ?></td>
                        <td><?= $row['Costo'] ?></td>
                        <td><?= $row['Estado'] ?></td>
                        <td>
                            <!-- Botón para eliminar -->
                            <button class="btn btn-danger btn-sm" onclick="eliminar(<?= $row['ID'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No hay reparaciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botones -->
    <div class="d-flex justify-content-end mb-3">
        <!-- Botón para ir a cita -->
        <a href="citas_registro.php" class="btn btn-secondary">Registro de Cita</a>
    </div>
</div>

<!-- Incluir SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Función de eliminar usando AJAX
function eliminar(id) {
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
            xhr.open("GET", "eliminar_datos.php?id=" + id, true);
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

<!-- Footer -->
<?php include 'footer.php'; ?>
