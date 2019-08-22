<?php
    //Crear, en ./clases,la clase LAMPARITA con atributos privados (tipo, precio, color y pathFoto), constructor con todos los parametros opcionales. La clase LAMPARITA debe implementar la interface IVendible (posee el método PrecioConIVA)
    class Lamparita implements IVendible{
        private $tipo;
        private $precio;
        private $color;
        private $pathFoto;

        public function __construct( $tipo = '', $precio = '', $color = '', $pathFoto = '' ){
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->color = $color;
            $this->pathFoto = $pathFoto;
        }

        //método de instancia ToString():string. Retorna los datos de la instancia (separado por un guión medio).
        public function toString(){
            return $this->tipo. '-' .$this->precio. '-' .$this->color. '-' .$pathFoto. PHP_EOL; 
        }

        public function precioConIVA(){
            return $this->precio * 1.21;
        }

        //Agregar (de instancia): agrega un nuevo registro en la tabla lamparitas (de la base lamparitas_bd)
        public function agregar(){

        }

        // Eliminar (de instancia): Elimina de la base de datos el registro coincidente con la instancia actual.
        public function eliminar(){

        }

        //Modificar (de clase): Modifica en la base de datos el registro coincidente con el parámetro recibido.
        public static function modificar( $lamparita ){

        }

        //TraerTodas (de clase): Retorna un array de lamparitas, obtenidas de la base de datos.
        public static function traerTodas(){

        }
    }

    interface IVendible{
        function precioConIVA();
    }
?>