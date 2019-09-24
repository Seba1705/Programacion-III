<?php
    require_once './clases/persona.php';

    class Alumno extends Persona{
        public $legajo;
        public $foto;

        function __construct($nombre, $apellido, $legajo, $foto){
            parent::__construct( $nombre, $apellido );
            $this->legajo = $legajo;
            $this->foto = $foto;
        }

        function toJSON(){
            return json_encode($this);            
        }

        public static function agregar($alumno){
            AlumnoDAO::guardarAlumnoEnArchivo($alumno);
        }

        public static function mostrar(){
            
        }

       public static function modificar(){

        }

        public static function eliminar(){

        }

    }
?>