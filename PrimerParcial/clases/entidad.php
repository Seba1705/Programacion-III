<?php
    class Entidad{
        public $id;
        public $nombre;

        public function __construct(){

        }

        public function toJKson(){
            return json_encode($this);
        }

        public static function alta($objeto){
            Archivo::guardarUno('', $objeto);
        }

        public static function baja(){
            
        }

        public static function modicacion(){
            
        }

        public static function mostrar($objeto){
            return $objeto->toJKson();
        }


    }
?>
