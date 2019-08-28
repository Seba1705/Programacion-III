<?php
    class Persona{
        public $nombre;
        public $apellido;

        function __construct( $nombre, $apellido ){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
        }

        public function saludar(){
            return "Hola :D soy $this->nombre";
        }
    }
?>