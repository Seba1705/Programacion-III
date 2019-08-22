<?php
    class Archivo{
        public static function existePeticionPOST(){
            return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
        }

        public static function existePeticionGET(){
            return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
        }

        public static function guardarUno( $path, $objeto ){
            $archivo = fopen( $path, 'a+' );
            fwrite( $archivo, $objeto->toCsv() );
            fclose( $archivo );
        }

        public static function guardarTodos( $path, $lista ){
            $archivo = fopen( $path, 'w' );
            foreach( $lista as $objeto ){
                fwrite( $archivo, $objeto->toCsv() );
            }
            fclose( $archivo );
        }

        public static function retornarAlumnos(){
            $listaDeAlumnos = array();
            $archivo = fopen( './archivos/alumnos.txt', 'r' );
            do{
                $alumno = trim( fgets($archivo) );
                if( $alumno != '' ){
                    $alumno = explode( ';', $alumno );
                    array_push( $listaDeAlumnos, new Alumno($alumno[0], $alumno[1], $alumno[2], $alumno[3]) );
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            return $listaDeAlumnos;
        }

        public static function retornarMaterias(){
            $listaDeMaterias = array();
            $archivo = fopen( './archivos/materias.txt', 'r' );
            do{
                $materia = trim( fgets($archivo) );
                if( $materia != '' ){
                    $materia = explode( ';', $materia );
                    array_push( $listaDeMaterias, new Materia($materia[0], $materia[1], $materia[2], $materia[3]) );
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            return $listaDeMaterias;
        }

        public static function retornarInscripciones(){
            $listaDeInscripciones = array();
            $archivo = fopen( './archivos/inscripciones.txt', 'r' );
            do{
                $inscripcion = trim( fgets($archivo) );
                if( $inscripcion != '' ){
                    $inscripcion = explode( ';', $inscripcion );
                    array_push( $listaDeInscripciones, new Inscripcion( $inscripcion[0], 
                                                                        $inscripcion[1], 
                                                                        $inscripcion[2], 
                                                                        $inscripcion[3], 
                                                                        $inscripcion[4]));
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            return $listaDeInscripciones;
        }
    }
?>