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

        public static function leerProveedores(){
            $listaDeProveedores = array();
            $archivo = fopen( './archivos/proveedores.txt', 'r' );
            do{
                $proveedor = trim( fgets($archivo) );
                if( $proveedor != '' ){
                    $proveedor = explode( ';', $proveedor );
                    array_push( $listaDeProveedores, new Proveedor($proveedor[0], $proveedor[1], $proveedor[2], $proveedor[3]) );
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            return $listaDeProveedores;
        }
        
    }
?>