<?php
    class Proveedor{
        public $id;
        public $nombre;
        public $email;
        public $foto;

        public function __construct( $id, $nombre, $email, $foto ){
            $this->id = $id;
            $this->nombre = $nombre;
            $this->email = $email;
            $this->foto = $foto;
        }

        public function toCsv(){
            return $this->id. ';' .$this->nombre. ';' .$this->email. ';' .$this->foto. PHP_EOL;
        }

        public function toString(){
            return 'ID: ' .$this->id. ' NOMBRE: ' .$this->nombre. ' EMAIL: ' .$this->email. ' FOTO: ' .$this->foto. PHP_EOL;
        }
        
        /////////////////////////////////////////////////////////////////
        // CARGAR PROVEEDOR
        /////////////////////////////////////////////////////////////////

        public static function cargarProveedor(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['id']) && !empty($_POST['id']) && 
                    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['email']) && !empty($_POST['email']) &&
                    isset($_FILES['foto']) ){
                    if( !Proveedor::existeID( $_POST['id']) ){
                        // Acciones sobre foto
                        $origen = $_FILES["foto"]["tmp_name"];
                        $nombreOriginal = $_FILES["foto"]["name"];
                        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                        $destinoFoto = "./img/".$_POST['id'].".".$ext;
                        move_uploaded_file($origen, $destinoFoto);

                        $proveedor = new Proveedor( $_POST['id'], $_POST['nombre'], $_POST['email'], $destinoFoto );
                        Archivo::guardarUno( './archivos/proveedores.txt', $proveedor );
                        
                        Proveedor::mostrarProveedor( $proveedor );
                    }else{
                        echo 'Ya existe proveedor con el id: ' . $_POST['id'];
                    }
                }else{
                    echo 'Debe ingresar datos del proveedor';
                }
            }else{
                echo 'Se debe llamar con el metodo POST';
            }
        }

        public static function existeID( $id ){
            $proveedores = Archivo::leerProveedores();
            foreach( $proveedores as $prov ){
                if( strcasecmp($prov->id, $id) == 0 )
                    return true;
            }
            return false;
        }

        /////////////////////////////////////////////////////////////////
        // CONSULTAR PROVEEDOR
        /////////////////////////////////////////////////////////////////

        public static function consultarProveedor(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['nombre']) && !empty($_GET['nombre']) ){
                    $nombre = $_GET['nombre'];
                    $consultados = array_filter( Archivo::leerProveedores(), function( $prov ) use ( $nombre ){
                        return strcasecmp( $prov->nombre, $nombre ) == 0;
                    });
                    echo 'PROVEEDORES CON EL NOMBRE: ' . $nombre . PHP_EOL . PHP_EOL;
                    array_map( 'Proveedor::mostrarProveedor', $consultados );
                }else{
                    echo 'Debe ingresar un nombre para realizar la consulta';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }

        public static function mostrarProveedor( $prov ){
            echo $prov->toString();
        }

        /////////////////////////////////////////////////////////////////
        // PROVEEDORES
        /////////////////////////////////////////////////////////////////

        public static function proveedores(){
            if( Archivo::existePeticionGET() ){
                echo 'PROVEEDORES' . PHP_EOL . PHP_EOL;
                array_map( 'Proveedor::mostrarProveedor', Archivo::leerProveedores() );
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }

        /////////////////////////////////////////////////////////////////
        // MODIFICAR PROVEEDORE
        /////////////////////////////////////////////////////////////////

        public static function modificarProveedor(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['id']) && !empty($_POST['id']) && 
                    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['email']) && !empty($_POST['email']) &&
                    isset($_FILES['foto']) ){
                    if( Proveedor::existeID( $_POST['id']) ){
                        $proveedores = Archivo::leerProveedores();
                        foreach( $proveedores as $item ){
                            if( strcasecmp($item->id, $_POST['id']) == 0){
                                $item->nombre = $_POST['nombre'];
                                $item->email = $_POST['email'];
                                
                                $origen = $_FILES["foto"]["tmp_name"];
                                $nombreOriginal = $_FILES["foto"]["name"];
                                $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                                $destinoFoto = $item->foto;
                                if(file_exists($destinoFoto)){
                                    copy($destinoFoto, "./backUpFotos/".$_POST['id']."_".date("m.d.y").".".$ext);
                                }
                                move_uploaded_file($origen, $destinoFoto);
                                echo 'Se modifico el proveedor: ' . $item->toString();
                                break;
                            }
                        }
                        Archivo::guardarTodos( './archivos/proveedores.txt', $proveedores );
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
        // FOTOS BACK
        /////////////////////////////////////////////////////////////////
        
        public static function fotosBack(){
            if( Archivo::existePeticionGET() ){
                $carpeta = './backUpFotos';
                $fotos = array();
                if(is_dir($carpeta)){
                    if($dir = opendir($carpeta)){
                        while(($archivo = readdir($dir)) !== false){
                            if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess'){
                                array_push( $fotos, $archivo );
                            }
                        }
                        closedir($dir);
                    }
                }
                echo 'FOTOS EN BACKUP' . PHP_EOL . PHP_EOL;
                array_map( 'Proveedor::mostrarBackUp', $fotos );
            }else {
                echo 'Se debe llamar con el metodo GET';
            }
        }

        public static function mostrarBackUp( $foto ){
            $foto = explode('_', $foto);
            $id = $foto[0];
            $fecha = explode('.', $foto[1]);
            $prov = Proveedor::buscarPorId( $id );
            echo 'NOMBRE: ' .$prov->nombre. ', FECHA: ' .$fecha[1]. '/' .$fecha[0]. '/' .$fecha[2]. PHP_EOL ;
        }

        public static function buscarPorId( $id ){
            $provedores = Archivo::leerProveedores();
            foreach( $provedores as $prov){
                if( strcasecmp($prov->id, $id) == 0)
                    return $prov;
            }
        }
      
    }
?>