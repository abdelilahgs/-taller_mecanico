<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener todos los usuarios
$query = "SELECT id, username, email, estado FROM usuarios ORDER BY estado DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .container {
            margin-top: 50px;
            background: #1f1f1f;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #0d6efd;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .pendiente { background-color: orange; color: white; }
        .aprobado { background-color: green; color: white; }
        .bloqueado { background-color: red; color: white; }
        .denegado { background-color: red; color: white; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <button onclick="history.back()" class="btn btn-secondary">🔙 Volver Atrás</button>
        <a href="logout.php" class="btn btn-danger px-3 py-1 fw-bold fs-6">
            🚪 Cerrar Sesión
        </a>
    </div>

    <h2>Gestión de Usuarios</h2>
    <br>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr id="user-<?= $user['id'] ?>">
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="status-badge <?= $user['estado'] ?>">
                            <?= ucfirst($user['estado']) ?>
                        </span>
                    </td>
                    <td>
                    <?php if ($user['estado'] == 'pendiente'): ?>
                        <button class="btn btn-success btn-sm" onclick="cambiarEstado(<?= $user['id'] ?>, 'aprobado')">✔ Aprobar</button>
                        <button class="btn btn-danger btn-sm" onclick="cambiarEstado(<?= $user['id'] ?>, 'denegado')">❌ Rechazar</button>
                    <?php elseif ($user['estado'] == 'aprobado'): ?>
                        <button class="btn btn-warning btn-sm" onclick="cambiarEstado(<?= $user['id'] ?>, 'bloqueado')">⛔ Bloquear</button>
                    <?php elseif ($user['estado'] == 'bloqueado'): ?>
                        <button class="btn btn-primary btn-sm" onclick="cambiarEstado(<?= $user['id'] ?>, 'aprobado')">🔓 Desbloquear</button>
                    <?php elseif ($user['estado'] == 'denegado'): ?>
                        <button class="btn btn-secondary btn-sm" onclick="cambiarEstado(<?= $user['id'] ?>, 'pendiente')">↺ Pendiente</button>
                    <?php endif; ?>
                </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function cambiarEstado(userId, estado) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción cambiará el estado del usuario.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, cambiar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("cambiar_estado.php", {
                user_id: userId,
                estado: estado
            }, function(response) {
                try {
                    const res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire("Éxito", "Estado actualizado correctamente.", "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Error", res.message || "Ocurrió un error.", "error");
                    }
                } catch (e) {
                    Swal.fire("Error", "Respuesta inesperada del servidor.", "error");
                }
            });
        }
    });
}
</script>


</body>
</html>