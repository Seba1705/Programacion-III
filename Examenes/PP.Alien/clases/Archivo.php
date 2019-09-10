<?php
    class Archivo
    {
        public static function guardar($path, $objeto){
            if( file_exists($path) ){
                $archivo = fopen( $path, 'a' );
                fwrite($archivo, $objeto->ToJson() . PHP_EOL);
                fclose($archivo);
                return true;
            }
            return false;
        }

        public function leer($path){
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