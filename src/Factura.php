<?php

namespace Root\Workspace;


class Factura
{
    private string $numero;
    private string $fecha;
    private array $cliente;
    private array $conceptos;

    public function __construct(string $numero, string $fecha, array $cliente)
    {
        $this->numero = $numero;
        $this->fecha = $fecha;
        $this->cliente = $cliente;
        $this->conceptos = [];
    }

    // Añade un concepto a la factura con cálculo automático del subtotal
    public function agregarConcepto(string $descripcion, int $cantidad, float $precioUnitario): void
    {
        $this->conceptos[] = [
            'descripcion' => $descripcion,
            'cantidad' => $cantidad,
            'precioUnitario' => $precioUnitario,
            'subtotal' => $cantidad * $precioUnitario
        ];
    }

    // Calcula el total de la factura sumando todos los subtotales
    public function calcularTotal(): float
    {
        $total = 0;
        foreach ($this->conceptos as $concepto) {
            $total += $concepto['subtotal'];
        }
        return $total;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function getCliente(): array
    {
        return $this->cliente;
    }

    public function getConceptos(): array
    {
        return $this->conceptos;
    }
}