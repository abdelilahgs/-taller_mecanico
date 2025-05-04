<?php
session_start();
include 'conexion.php';

// Verificar si es administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Obtener los usuarios pendientes
$sql = "SELECT ID, Nombre, Email FROM usuarios WHERE Estado = 'pendiente'";
$result = $conn->query($sql);
?>

<h2>Usuarios Pendientes de Aprobación</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $usuario['ID'] ?></td>
            <td><?= $usuario['Nombre'] ?></td>
            <td><?= $usuario['Email'] ?></td>
            <td>
                <a href="aprobar.php?id=<?= $usuario['ID'] ?>">Aprobar</a>
                <a href="rechazar.php?id=<?= $usuario['ID'] ?>">Rechazar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
