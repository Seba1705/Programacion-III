<?php
    // 4.-(2 pt) Helado.php: Crear la clase HELADO con dos atributos privados: sabor y precio, que implemente la interface IVendible con el método PrecioMasIva(). HELADO tiene el método público y de clase RetornarArrayDeHelados(), que retorna un array de 5 helados distintos.
    class Helado implements IVendible{
        private $sabor;
        private $precio;

        public function __construct( $sabor, $precio ){
            $this->sabor = $sabor;
            $this->precio = $precio;
        }

        public function getSabor(){
            return $this->sabor;
        }

        public function precioMasIva(){
            return $this->precio * 1.21;
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
    }

    interface IVendible {
        function PrecioMasIva();
    }
?>