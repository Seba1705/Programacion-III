<?php
    class Proveedor{
        private $id;
        private $nombre;
        private $email;
        private $foto;

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
                    if( Proveedor::existeID( $_POST['id']) ){
                        //Acciones sobre foto
                        $origen = $_FILES["foto"]["tmp_name"];
                        $nombreOriginal = $_FILES["foto"]["name"];
                        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                        $destinoFoto = "./img/".$_POST['id'].".".$ext;
                        move_uploaded_file($origen, $destinoFoto);

                        $proveedor = new Proveedor( $_POST['id'], $_POST['nombre'], $_POST['email'], $destinoFoto );
                        Archivo::guardarUno( './archivos/proveedores.txt', $proveedor );
                        
                        echo 'Se cargo el proveedor: ' . $proveedor->toString();
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

    }
?>