<?php
    class Pedido{
        public $producto;
        public $cantidad;
        public $idProveedor;
        public function __construct($id ,$producto, $cantidad){
            $this->producto = $producto;
            $this->cantidad = $cantidad;
            $this->idProveedor = $id;
        }   

        public function toCsv(){
            return $this->idProveedor.";".$this->producto.";".$this->cantidad.PHP_EOL;
        }

        public function toString(){
            return 'ID: ' .$this->idProveedor.", PRODUCTO: ".$this->producto.", CANTIDAD: ".$this->cantidad;
        }

        /////////////////////////////////////////////////////////////////
        // HACER PEDIDO
        /////////////////////////////////////////////////////////////////

        public static function hacerPedido(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['id']) && !empty($_POST['id']) && 
                    isset($_POST['producto']) && !empty($_POST['producto']) &&
                    isset($_POST['cantidad']) && !empty($_POST['cantidad']) ){
                    if( Proveedor::existeID( $_POST['id']) ){
                        $pedido = new Pedido( $_POST['id'], $_POST['producto'], $_POST['cantidad'] );
                        Archivo::guardarUno( './archivos/pedidos.txt', $pedido );
                        echo 'Pedido guardado!';
                    }else{
                        echo 'No existe proveedor con el id: ' . $_POST['id'];
                    }
                }else{
                    echo 'Debe ingresar datos del proveedor';
                }
            }else{
                echo 'Se debe llamar con el metodo POST';
            }   
        }

        /////////////////////////////////////////////////////////////////
        // LISTAR PEDIDOS
        /////////////////////////////////////////////////////////////////

        public static function listarPedidos(){
            if( Archivo::existePeticionGET() ){
                echo 'PEDIDOS' . PHP_EOL . PHP_EOL;
                array_map( 'Pedido::mostrarPedido', Archivo::leerPedidos() );
            }else{  
                echo 'Se debe llamar con el metodo GET';
            }
        }

        public static function mostrarPedido( $pedido ){
            $prov = Proveedor::buscarPorId( $pedido->idProveedor );
            echo $pedido->toString() . ', NOMBRE: ' . $prov->nombre . PHP_EOL;
        }

        /////////////////////////////////////////////////////////////////
        // LISTAR PEDIDOS PROVEEDOR
        /////////////////////////////////////////////////////////////////

        public static function listarPedidoProveedor(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['id']) && !empty($_GET['id']) ){
                    echo 'LISTA DE PEDIDOS' . PHP_EOL . PHP_EOL;
                    $id = $_GET['id'];
                    $pedidos = array_filter( Archivo::leerPedidos(), function( $pedido ) use ( $id ){
                        return strcasecmp( $pedido->idProveedor, $id) == 0;
                    });
                    if( count($pedidos) > 0){
                        array_map( 'Pedido::mostrarPedido', $pedidos );
                    }else{
                        echo 'El provedor con id: ' . $id . ' no tiene pedidos';
                    }
                }else{
                    echo 'Debe ingresar un id valido';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';   
            }
        }

    }
?>