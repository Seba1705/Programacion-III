<?php
    // session_start();

    // if(!isset($_SESSION['alumnos'])){
    //     $_SESSION['alumnos'] = [];
    // }

    class AlumnoDAO{
        
        public static function guargarAlumno( $alumno ){
            array_push($_SESSION['alumnos'], $alumno->toJSON());
            echo '{"mensaje":"alumno cargado"}';
        }

        public static function retornarAlumnos(){
            return json_encode($_SESSION['alumnos']);
        }

        public static function guardarUnoEnArchivo( $path, $alumno ){
            $archivo = fopen( $path, 'a+');
            fwrite( $archivo, $alumno->toJSON() . PHP_EOL );
            fclose( $archivo );
        }
        
        public static function guardarTodos( $path, $lista ){
            $archivo = fopen( $path, 'w' );
            foreach( $lista as $item ){
                fwrite( $archivo, $item->toJSON() . PHP_EOL );
            }
            fclose( $archivo );
        }

        public static function leerTodos( $path ){
            $retorno = array(); //Lo va a devolver con las entidades leidas
            $archivo = fopen( $path, 'r' );
            do{
                $item = trim(fgets($archivo));
                if ($item != ""){
                    // Antes de cargar parsearlo a la entidad correspondiente (Alumno)$item
                    $objeto = json_decode($item);
                    array_push( $retorno, $objeto );
                }
            }while( !feof($archivo) );
            fclose($archivo); 
            return $retorno;   
        }
    }
?>