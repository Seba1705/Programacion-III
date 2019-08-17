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

        public static function generarID(){
            $rutaArchivo = './archivos/id.txt'; 

            $archivo = fopen($rutaArchivo, 'r');
            $id = number_format(fgets($archivo));
            fclose( $archivo );

            $nuevoId = $id + 1;
            $archivo2 = fopen( $rutaArchivo, 'w' );
            fwrite( $archivo2, $nuevoId );
            fclose( $archivo2 );
            return $nuevoId;
        }

        public static function validarSabor( $sabor ){
            if( strcasecmp('muzza', $sabor) == 0 || strcasecmp('jamon', $sabor) == 0 || strcasecmp('especial', $sabor) == 0 )
                return true;
            return false;
        }

        public static function validarTipo( $tipo ){
            if( strcasecmp('molde', $tipo) == 0 || strcasecmp('piedra', $tipo) == 0 )
                return true;
            return false;
        }

        public static function validarConbinacion( $tipo, $sabor ){
            $lista = Archivo::leerPizzas();
            foreach( $lista as $item ){
                if( strcasecmp($item->sabor, $sabor) == 0 && strcasecmp($item->tipo, $tipo) == 0 )
                    return false;
            }
            return true;
        }

        public static function leerPizzas(){
            $lista = array();
            $archivo = fopen( './archivos/pizzas.txt', 'r' );
            do{
                $pizza = trim( fgets($archivo) );
                if( $pizza != '' ){
                    $pizza = explode( ';', $pizza );
                    array_push( $lista, new Pizza($pizza[0], $pizza[1], $pizza[2], $pizza[3], $pizza[4], $pizza[5]) );
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            return $lista;
        }
        
    }
?>