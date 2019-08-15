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

        /////////////////////////////////////////////////////////////////
        // LISTAR PRODUCTO CON PARAMETROS
        /////////////////////////////////////////////////////////////////

        public static function listarProductosConParametros(){
            if( Validaciones::existePeticionGET() ){

                if( isset($_GET['criterio']) && !empty($_GET['criterio']) &&
                    isset($_GET['valor']) && !empty($_GET['valor']) ){
                    $criterio = $_GET['criterio'];
                    if( strcasecmp($criterio, 'producto') == 0 ||  strcasecmp($criterio, 'usuario') == 0 ){
                        switch( $criterio ){
                            case 'usuario':
                                Producto::productosPorUsuario( $_GET['valor']);
                                break;
                            case 'producto':
                                Producto::productosPorProducto( $_GET['valor']);
                                break;
                        }
                    }else{
                        echo 'Ingrese un criterio de busqueda valido';
                    }
                }else{
                    echo 'Debe ingresar un criterio de busqueda (Producto / Usuario) y su valor';
                }
            }else{
                echo 'Debe llamarse con el metodo GET';
            } 
        }

        public static function productosPorUsuario($valor){
            $lista = Archivo::leerProductos();
            $flag = true;
            echo 'LISTA DE PRODUCTOS' . PHP_EOL . PHP_EOL;
            foreach( $lista as $item ){
                if( strcasecmp($item->nombreUsuario, $valor) == 0){
                    echo $item->toString();
                    $flag = false;
                }
            }
            if( $flag ) echo 'No hay productos asociados al valor: ' . $valor;
            
        }

        public static function productosPorProducto($valor){
            $lista = Archivo::leerProductos();
            $flag = true;
            echo 'LISTA DE PRODUCTOS' . PHP_EOL . PHP_EOL;
            foreach( $lista as $item ){
                if( strcasecmp($item->nombre, $valor) == 0){
                    echo $item->toString();
                    $flag = false;
                }
            }
            if( $flag ) echo 'No hay productos asociados al valor: ' . $valor;
        }

        /////////////////////////////////////////////////////////////////
        // MODIFICAR PRODUCTO
        /////////////////////////////////////////////////////////////////

        public static function modificarProducto(){
            if( Validaciones::existePeticionPOST() ){
                if( isset($_POST['id']) && !empty($_POST['id']) &&
                    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['precio']) && !empty($_POST['precio']) &&
                    isset($_POST['cantidad']) && !empty($_POST['cantidad']) &&
                    isset($_POST['usuario']) && !empty($_POST['usuario']) &&
                    isset($_FILES['imagen']) && !empty($_FILES['imagen']) ){
                    if( Producto::existeId($_POST['id']) ){
                        if( Usuario::existeNombre($_POST['usuario']) ){
                            $productos = Archivo::leerProductos();
                            foreach( $productos as $item ){
                                if( strcasecmp($item->id, $_POST['id']) == 0){
                                    $item->precio = $_POST['precio'];
                                    $item->cantidad = $_POST['cantidad'];
                                    $item->usuario = $_POST['usuario'];
                                    $item->nombre = $_POST['nombre'];

                                    $origen = $_FILES["imagen"]["tmp_name"];
                                    $nombreOriginal = $_FILES["imagen"]["name"];
                                    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                                    $destinoFoto = "./img/".$_POST['id']."-".$_POST['nombre'].".".$ext;
                                    if(file_exists($destinoFoto)){
                                        copy($destinoFoto, "./backUpFotos/".$_POST['id']."_".date("Ymd").".".$ext);
                                    }
                                    move_uploaded_file($origen, $destinoFoto);

                                    echo 'Se modifico el producto: ' . $item->toString();
                                    break;
                                }
                            }
                            Archivo::guardarTodos( './archivos/productos.txt', $productos );
                        }else{
                            echo 'No existe usuario con el nombre: ' . $_POST['usuario'];
                        }
                    }else{
                        echo 'No existe producto con el id: ' . $_POST['id'];
                    }       
                }else{
                    echo 'No se configuraron todas las variables';
                }
            }else{
                echo 'Debe llamarse con el metodo POST';
            }
        }
        
    }
?>