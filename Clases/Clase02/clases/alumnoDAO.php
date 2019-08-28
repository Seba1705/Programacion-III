<?php
    // session_start();

    if(!isset($_SESSION['alumnos'])){
        $_SESSION['alumnos'] = [];
    }

    class AlumnoDAO{
        
        public static function guargarAlumno( $alumno ){
            array_push($_SESSION['alumnos'], $alumno->toJSON());
            echo '{"mensaje":"alumno cargado"}';
        }

        public static function retornarAlumnos(){
            return json_encode($_SESSION['alumnos']);
        }
    }
?>