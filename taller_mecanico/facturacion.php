<?php 
include 'config.inc.php'; 
include 'header.php'; 
?>

<div class="container mt-5">
    <h2 class="text-center">📄 Facturación</h2>

    <div class="card p-4 mb-4">
        <h4 class="text-center">🔍 Buscar Factura</h4>
        <form action="generar_factura.php" method="GET">
            <div class="mb-3">
                <label for="usuario_id">Usuario ID:</label>
                <input type="text" name="usuario_id" id="usuario_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">🔍 Buscar</button>
        </form>
    </div>

    <div class="text-center">
        <a href="generar_factura.php" class="btn btn-success">➕ Generar Nueva Factura</a>
    </div>
</div>

<?php include 'footer.php'; ?>
