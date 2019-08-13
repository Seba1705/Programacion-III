<?php
    class Archivo{
        public static function guardarUno( $path, $objeto ){
            $rutaArchivo = $path;
            $archivo = fopen($rutaArchivo, 'a+');
            fwrite($archivo, $objeto->toCSV());
            fclose($archivo);
        }

        public static function guardarTodos( $path, $lista ){
            $rutaArchivo = $path;
            $archivo = fopen($rutaArchivo, 'w');
            foreach($lista as $item){
                fwrite($archivo, $item->toCSV());
            }
            fclose($archivo);
        }

        // Es Especifico de cada Entidad
        public static function leerUsuarios(){
            $rutaArchivo = './archivos/usuario.txt';
            $retorno = array(); //Lo va a devolver con las entidades leidas
            $archivo = fopen($rutaArchivo, 'r');
            do{
                $usuario = trim(fgets($archivo));
                if ($usuario != ""){
                    $usuario = explode(';', $usuario);
                    array_push( $retorno, new usuario($usuario[0], $usuario[1]) );
                }
            }while( !feof($archivo) );
            fclose($archivo); 
            return $retorno;   
        }
    }
?>