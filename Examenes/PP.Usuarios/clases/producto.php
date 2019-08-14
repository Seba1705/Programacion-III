<?php
    class Producto{

        public $id;
        public $nombre;
        public $precio;
        public $imagen;
        public $nombreUsuario;
        public $cantidad;
        
        public function __construct($id ,$nombre, $precio, $cantidad, $nombreUsuario, $imagen){
            $this->id = $id;
            $this->nombre = $nombre;
            $this->precio = $precio;
            $this->cantidad = $cantidad;
            $this->nombreUsuario = $nombreUsuario; 
            $this->imagen = $imagen;
        }

        public function toCSV(){
            return $this->id.";".$this->nombre.";".$this->precio.";".$this->cantidad.";".$this->nombreUsuario.";".$this->imagen.PHP_EOL;
        }

        public function toString(){
            return  'Id: ' . $this->id . ', Nombre: '. $this->nombre . ', Precio: ' . $this->precio . PHP_EOL .
                    'Cantidad: ' . $this->cantidad . ', Nombre de Usuario: '. $this->nombreUsuario . ', Ruta Imagen ' . $this->imagen . PHP_EOL;
        }

        /////////////////////////////////////////////////////////////////
        // CARGAR PRODUCTO
        /////////////////////////////////////////////////////////////////

        public static function cargarProducto(){
            if( Validaciones::existePeticionPOST() ){
                if( isset($_POST['id']) && !empty($_POST['id']) &&
                    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['precio']) && !empty($_POST['precio']) &&
                    isset($_POST['cantidad']) && !empty($_POST['cantidad']) &&
                    isset($_POST['usuario']) && !empty($_POST['usuario']) &&
                    isset($_FILES['imagen']) && !empty($_FILES['imagen']) ){
                    if( Usuario::existeNombre($_POST['usuario']) ){
                        if( !Producto::existeId( $_POST['id'] )){
                            //Imagen
                            $origen = $_FILES["imagen"]["tmp_name"];
                            $nombreOriginal = $_FILES["imagen"]["name"];
                            $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                            $destinoFoto = "./img/".$_POST['id']."-".$_POST['nombre'].".".$ext;
                            move_uploaded_file($origen, $destinoFoto);
                            
                            $producto = new Producto(   $_POST['id'], 
                                                        $_POST['nombre'], 
                                                        $_POST['precio'], 
                                                        $_POST['cantidad'],
                                                        $_POST['usuario'],
                                                        $destinoFoto );
                            Archivo::guardarUno( './archivos/productos.txt', $producto );
                            echo 'Se cargo el pruducto: ' . PHP_EOL . $producto->toString();
                        }else{
                            echo 'Ya existe un pructo con el id: ' . $_POST['id'];
                        }
                    }else{
                        echo 'No existe un usuario con ese nombre';
                    }
                }else{
                    echo 'No se configuraron todas las variables';
                }
            }else{
                echo 'Debe llamarse con el metodo POST';
            }
        }

        public static function existeId( $id ){
            $listaDeProductos = Archivo::leerProductos();
            foreach( $listaDeProductos as $producto ){
                if( strcasecmp($producto->id, $id) == 0 )
                    return true;
            } 
            return false;
        }

        /////////////////////////////////////////////////////////////////
        // LISTAR PRODUCTO
        /////////////////////////////////////////////////////////////////

        public static function listarProducto(){
            if( Validaciones::existePeticionGET() ){
                $productos = Archivo::leerProductos();
                echo 'LISTA DE PRODUCTOS' . PHP_EOL . PHP_EOL;
                if( count($productos) > 0){
                    foreach( $productos as $item ){
                        echo $item->toString();
                    }
                }else{
                    echo 'No hay Productos para mostrar';
                }
            }else{
                echo 'Debe llamarse con el metodo GET';
            } 
        }
    }
?>