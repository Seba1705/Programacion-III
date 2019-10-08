<?php
    class Venta{
        public $id;
        public $email;
        public $tipo;
        public $cantidad;
        public $sabor;
        public $precio;

        public function __construct($id, $email, $tipo, $cantidad, $sabor, $precio){
            $this->id = $id;
            $this->email = $email;
            $this->tipo = $tipo;
            $this->cantidad = $cantidad;
            $this->sabor = $sabor;
            $this->precio = $precio;
        }

        public function toJSON(){
            return json_encode($this);
        }

        public static function alta($objeto){
            Archivo::guardarUno('./archivos/ventas.txt', $objeto);
        }

        public static function retornarVentas(){
            $datos = Archivo::leerArchivo('./archivos/ventas.txt');
            $ventas = array();
            foreach ($datos as $key => $value) {
                $venta = new Venta($value->id, $value->email, $value->tipo, $value->cantidad, $value->sabor, $value->precio);
                array_push($ventas, $venta);
            }
            return $ventas;
        }

        public static function generarNuevoId(){
            $rutaArchivo = './archivos/idVentas.txt';      
            $archivo = fopen($rutaArchivo, 'r');
            $ultimoId = fgets($archivo);
            fclose($archivo);
            $nuevoId = number_format($ultimoId) + 1;
            $nuevoArchivo = fopen($rutaArchivo, 'w');
            fwrite($nuevoArchivo, $nuevoId);
            return $nuevoId;
        }  
        
        public static function filtrarPorTipo($tipo){
            $consultados = array_filter(Venta::retornarVentas(), function( $objeto ) use ( $tipo ){
                return strcasecmp( $objeto->tipo, $tipo ) == 0;
            });
            echo 'Ventas del tipo: ' . $tipo . PHP_EOL . PHP_EOL;
            array_map('Venta::mostrarVenta', $consultados);
        }

        public static function filtrarPorSabor($sabor){
            $consultados = array_filter(Venta::retornarVentas(), function( $objeto ) use ( $sabor ){
                return strcasecmp( $objeto->sabor, $sabor ) == 0;
            });
            echo PHP_EOL . 'Ventas del sabor: ' . $sabor . PHP_EOL . PHP_EOL;
            array_map('Venta::mostrarVenta', $consultados);
        }

        public static function mostrarVenta($objeto){
            echo $objeto->toJSON() . PHP_EOL;
        }
    }
?>