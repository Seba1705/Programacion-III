<?php
    class Venta{
        /*4-(2 pts.) ​Ruta: ventas ​(POST). Recibe el email del usuario y el sabor,tipo y cantidad ,si el item existe en ​Pizza.xxx,
        y hay stock​ ​guardar en el archivo de texto ​Venta.xxx​ todos los datos , más el precio de la venta, un id
        autoincremental y descontar la cantidad vendida. Si no cumple las condiciones para realizar la venta, informar el
        motivo.*/
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

        public static function alta($objeto){
            Archivo::guardarUno('./archivos/ventas.txt', $objeto);
        }
    }

?>