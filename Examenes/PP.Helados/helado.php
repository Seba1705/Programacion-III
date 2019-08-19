<?php
    // 4.-(2 pt) Helado.php: Crear la clase HELADO con dos atributos privados: sabor y precio, que implemente la interface IVendible con el método PrecioMasIva(). HELADO tiene el método público y de clase RetornarArrayDeHelados(), que retorna un array de 5 helados distintos.
    class Helado implements IVendible{
        private $sabor;
        private $precio;
        private $foto;

        public function __construct( $sabor, $precio ){
            $this->sabor = $sabor;
            $this->precio = $precio;

        }

        public function toCsv(){
            return $this->sabor. ';' .$this->precio. ';' .$this->foto. PHP_EOL;
        }

        public function getSabor(){
            return $this->sabor;
        }

        public function getPrecio(){
            return $this->precio;
        }

        public function getFoto(){
            return $this->foto;
        }

        public function precioMasIva(){
            return $this->precio * 1.21;
        }

        public function setFoto( $foto ){
            $this->foto = $foto;
        }

        public function toString(){
            return 'Sabor: ' .$this->sabor. ' Precio: ' . $this->precio. ' Foto: ' .$this->foto. PHP_EOL;
        }
    
        public static function retornarArrayDeHelados(){
            $dulce = new Helado( 'Dulce de leche', 20 );
            $chocolate = new Helado( 'Chocolate', 25 );
            $americana = new Helado( 'Americana', 15 );
            $sambayon = new Helado( 'Sambayon', 30 );
            $kinder = new Helado( 'Kinder', 35 );

            $array = [ $dulce, $chocolate, $americana, $sambayon, $kinder ];

            return $array;
        }

        public static function retornarHeladosDeArchivo(){
            $helados = array();
            $archivo = fopen( './heladosArchivo/helados.txt', 'r' );
            do{
                $helado = trim( fgets($archivo) );
                if( $helado != '' ){
                    $helado = explode( ';', $helado );
                    $auxHelado = new helado($helado[0], $helado[1]);
                    $auxHelado->setFoto( $helado[2] );
                    array_push( $helados, $auxHelado );
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            return $helados;
        }
    }

    interface IVendible {
        function PrecioMasIva();
    }
?>