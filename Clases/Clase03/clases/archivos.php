<?php
    class Archivo
    {

        public static function guardarUno( $path, $objeto )
        {
            if ( file_exists($path) ) {
                $archivo = fopen($path, 'a+');
                fwrite( $archivo, $objeto->toJSON() . PHP_EOL );
                fclose( $archivo );
            } else {
                echo '{"mensaje":"El archivo no existe"}';
            }
        }

        public static function guardarTodos( $path, $lista ){
            $archivo = fopen( $path, 'w' );
            foreach( $lista as $item ){
                fwrite( $archivo, $objeto->toJSON() . PHP_EOL );
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