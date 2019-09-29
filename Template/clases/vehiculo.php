<?php
    class Vehiculo{
       

        public $marca;
        public $modelo;
        public $patente;
        public $precio;

        function __construct($marca, $modelo, $patente, $precio){
            $this->marca = $marca;
            $this->modelo = $modelo;
            $this->patente = $patente;
            $this->precio = $precio;
        }
        
        function toJSON(){
            return json_encode($this);            
        }

        public static function agregar($objeto){
            Archivo::guardarUno('./archivos/vehiculos.txt', $objeto);
        }

        public static function mostrar(){
            $datos = Archivo::leerTodos('./archivos/vehiculos.txt');
            $vehiculos = array();
            foreach ($datos as $key => $value) {
                array_push($vehiculos, new Vehiculo($value->marca, $value->modelo, $value->patente, $value->precio));
            }
            return $vehiculos;
        }

        public static function modificar(){

        }

        public static function eliminar(){

        }

    }
?>