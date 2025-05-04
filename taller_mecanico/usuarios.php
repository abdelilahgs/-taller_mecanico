<?php 
include 'config.php'; 
include 'header.php'; 

// Obtener lista de usuarios
$result = $conn->query("SELECT ID, Nombre, Email, Rol, Estado FROM Usuarios");
?>

<h2>Gestión de Usuarios</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['ID'] ?></td>
        <td><?= $row['Nombre'] ?></td>
        <td><?= $row['Email'] ?></td>
        <td><?= $row['Rol'] ?></td>
        <td><?= $row['Estado'] ?></td>
        <td>
            <a href="editar_usuario.php?id=<?= $row['ID'] ?>">Editar</a> |
            <a href="eliminar_usuario.php?id=<?= $row['ID'] ?>" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'footer.php'; ?>
