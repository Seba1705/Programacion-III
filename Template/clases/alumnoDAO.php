<?php
    class AlumnoDAO{
        public static function guardarAlumnoEnArchivo($alumno){
            Archivo::guardarUno('./archivos/alumnos.json', $alumno);
        }
        
        public static function guardarListaDeAlumnos($lista){
            Archivo::guardarTodos('./archivos/alumnos.json', $lista);
        }

        public static function retornarListaDeALumnos(){
            $datos = Archivo::leerTodos('./archivos/alumnos.json');
            $alumnos = array();
            foreach ($datos as $key => $value) {
                array_push($alumnos, new Alumno($value->nombre, $value->apellido, $value->legajo, $value->foto));
            }
            return $alumnos;
        }
    }
?>