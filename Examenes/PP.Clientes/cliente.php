<?php
    // 1.-(0,5 pt) Cliente.php: Crear la clase CLIENTE con atributos privados (nombre, correo y clave), un método de instancia ToString() y un método de clase GuardarEnArchivo(Cliente).
    class Cliente{
        private $nombre;
        private $correo;
        private $clave;

        public function __construct( $nombre, $correo, $clave ){
            $this->nombre = $nombre;
            $this->correo = $correo;
            $this->clave = $clave;
        }

        public function toString(){
            return 'Nombre: ' .$this->nombre. ' Correo: ' .$this->correo. ' Clave: ' .$this->clave. PHP_EOL;
        } 

        public function toCsv(){
            return $this->nombre. ';' .$this->correo. ';' .$this->clave. PHP_EOL;
        }

        public static function guardarEnArchivo( $path, $cliente ){
            $archivo = fopen( $path, 'a+' );
            fwrite( $archivo, $cliente->toCsv() );
            fclose( $archivo );
        }
    }
?>