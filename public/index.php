
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Facturas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input, textarea { width: 300px; padding: 5px; margin: 5px 0; }
        button { padding: 8px 15px; margin: 5px; }
        .concepto { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .concepto input { width: 120px; }
        h1, h3 { color: #333; }
    </style>
</head>
<body>
    <h1>Generador de Facturas</h1>
    
    <?php
    // Controla cuántos conceptos mostrar con un contador (mínimo 1)
    $conceptos = isset($_POST['conceptos']) ? (int)$_POST['conceptos'] : 1;
    if (isset($_POST['mas'])) $conceptos++; // Botón "+ Concepto" añade uno más
    ?>
    
    <form action="generar_factura.php" method="POST">
        <input type="hidden" name="conceptos" value="<?php echo $conceptos; ?>">
        
        <h3>Datos de la Factura</h3>
        <label>Número de Factura:</label><br>
        <input type="text" name="numero" value="<?php echo str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT); ?>" required><br>
        
        <label>Fecha:</label><br>
        <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required><br>

        <h3>Datos del Cliente</h3>
        <label>Nombre del Cliente:</label><br>
        <input type="text" name="cliente_nombre" placeholder="Juan Pérez García" required><br>
        
        <label>Dirección del Cliente:</label><br>
        <textarea name="cliente_direccion" placeholder="Calle Mayor, 123, 28001 Madrid" required></textarea><br>

        <h3>Conceptos</h3>
        
        <?php 
        // Genera conceptos según el contador
        for ($i = 1; $i <= $conceptos; $i++): ?>
        <div class="concepto">
            <label>Descripción <?php echo $i; ?>:</label>
            <input type="text" name="desc<?php echo $i; ?>" <?php echo $i == 1 ? 'required' : ''; ?>><br>
            <label>Cantidad:</label>
            <input type="number" name="cant<?php echo $i; ?>" value="<?php echo $i == 1 ? '1' : '0'; ?>" min="0">
            <label>Precio:</label>
            <input type="number" name="precio<?php echo $i; ?>" step="0.01" min="0">
        </div>
        <?php endfor; ?>

        <button type="submit">Generar PDF</button>
    </form>
    
    <!-- Formulario para añadir concepto (Suma 1 al contador de conceptos que se muestran) -->
    <form method="post">
        <input type="hidden" name="conceptos" value="<?php echo $conceptos; ?>">
        <button type="submit" name="mas">+ Concepto</button>
    </form>
</body>
</html>