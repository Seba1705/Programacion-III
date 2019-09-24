<?php
    class Archivo
    {
        public static function guardarUno($path, $objeto){
            if (file_exists($path)) {

                $archivo = fopen($path, 'a+');
                fwrite($archivo, $objeto->toJSON() . PHP_EOL);
                fclose($archivo);
                echo '{"mensaje":"Guardado correctamente"}';

            } else {
                echo '{"mensaje":"El archivo no existe"}';
            }
        }

        public static function guardarTodos($path, $lista){
            $archivo = fopen($path, 'w');
            foreach($lista as $item){
                fwrite($archivo, $item->toJSON() . PHP_EOL);
            }
            fclose($archivo);
        }

        public static function leerTodos($path){
                $retorno = array();
                $archivo = fopen($path, 'r');
                do{
                    $item = trim(fgets($archivo));
                    if ($item != ''){
                        $objeto = json_decode($item);
                        array_push($retorno, $objeto);
                    }
                }while(!feof($archivo));
                fclose($archivo); 
                return $retorno;   
        } 
    }
?>