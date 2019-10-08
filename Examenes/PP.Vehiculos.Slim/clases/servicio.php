<?php
    class Servicio{
        public $id;
        public $tipo;
        public $precio;
        public $demora;

        function __construct($id, $tipo, $precio, $demora){
            $this->id = $id;
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->demora = $demora;
        }

        function toJSON(){
            return json_encode($this);            
        }

        public static function agregar($objeto){
            Archivo::guardarUno('./archivos/servicios.txt', $objeto);
        }

        public static function mostrar(){
            $datos = Archivo::leerTodos('./archivos/servicios.txt');
            $servicios = array();
            foreach ($datos as $key => $value) {
                array_push($servicios, new Servicio($value->id, $value->tipo, $value->precio, $value->demora));
            }
            return $servicios;
        }

        public static function modificar(){

        }

        public static function eliminar(){

        }

    }
?>