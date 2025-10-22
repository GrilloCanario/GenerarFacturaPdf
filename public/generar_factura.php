<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Factura.php';

use Root\Workspace\Factura;
use Dompdf\Dompdf;

// Verificar que se envió el formulario por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Recoger datos del formulario
$numero = $_POST['numero'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$clienteNombre = $_POST['cliente_nombre'] ?? '';
$clienteDireccion = $_POST['cliente_direccion'] ?? '';

$fechaFormateada = date('d/m/Y', strtotime($fecha));

// Crear instancia de factura con datos básicos
$factura = new Factura(
    $numero,
    $fechaFormateada,
    [
        'nombre' => $clienteNombre,
        'direccion' => $clienteDireccion
    ]
);

// Procesar conceptos desde el formulario
$conceptos = (int)$_POST['conceptos'];
for ($i = 1; $i <= $conceptos; $i++) {
    $desc = $_POST["desc$i"] ?? '';
    $cant = (int)($_POST["cant$i"] ?? 0);
    $precio = (float)($_POST["precio$i"] ?? 0);
    
    // Solo añadir conceptos válidos
    if ($desc && $cant > 0 && $precio >= 0) {
        $factura->agregarConcepto($desc, $cant, $precio);
    }
}

// Generar HTML de la factura con estilos básicos
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #f0f0f0; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>FACTURA</h1>
    <p>Número: ' . $factura->getNumero() . ' | Fecha: ' . $factura->getFecha() . '</p>
    
    <h3>Cliente</h3>
    <p>' . $factura->getCliente()['nombre'] . '<br>' . $factura->getCliente()['direccion'] . '</p>
    
    <table>
        <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>';

// Generar filas de la tabla con cada concepto
foreach ($factura->getConceptos() as $concepto) {
    $html .= 
        '<tr>
            <td>' . $concepto['descripcion'] . '</td>
            <td>' . $concepto['cantidad'] . '</td>
            <td>' . number_format($concepto['precioUnitario'], 2) . ' €</td>
            <td>' . number_format($concepto['subtotal'], 2) . ' €</td>
        </tr>';
}

// Fila del total
$html .= 
        '<tr class="total">
            <td colspan="3">TOTAL</td>
            <td>' . number_format($factura->calcularTotal(), 2) . ' €</td>
        </tr>
    </table>
    </body>
    </html>';

// Generar PDF con dompdf y enviarlo para descarga
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream('factura-' . $factura->getNumero() . '.pdf');