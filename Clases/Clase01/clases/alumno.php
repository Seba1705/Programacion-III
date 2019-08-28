<?php
    class Alumno extends Persona{
        public $legajo;

        function __construct( $nombre, $apellido, $legajo ){
            parent::__construct( $nombre, $apellido );
            $this->legajo = $legajo;
        }

        function toJSON(){
            return json_encode($this);            
        }

        public static function cargarAlumno(){
            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
                if( isset($_POST['nombre'], $_POST['apellido'], $_POST['legajo']) && 
                    !empty($_POST['nombre']) && $_POST['legajo'] && $_POST['apellido']){
        
                    $alumno = new Alumno( $_POST['nombre'], $_POST['apellido'], $_POST['legajo'] );
                    AlumnoDAO::guargarAlumno( $alumno );
        
                }else{
                    echo '{"mensaje":"Se debe llamar con el metodo POST"}';
                }
            }
        }

        public static function retornarAlumnos(){
            if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
                $alumnos = json_decode(AlumnoDAO::retornarAlumnos());
                foreach( $alumnos as $item ){
                    echo $item . PHP_EOL;
                }
            }else{
                echo '{"mensaje":"Se debe llamar con el metodo GET"}';
            }
        }
    }
?>