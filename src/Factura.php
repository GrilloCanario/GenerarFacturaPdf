<?php


class Factura {
    public function __construct(
        public string $nombre,
        public string $direccion,
        public int $num_factura,
        public int $fecha,
        public array $lista_conceptos = []
    ) {
    }    
    public function agregarConcepto(string $descripcion, int $cantidad, float $precioUnitario){

        }

    public function calcularTotal() {
        
    }
}