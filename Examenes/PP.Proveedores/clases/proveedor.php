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
        
        public static function cargarProveedor(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['id']) && !empty($_POST['id']) && 
                    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['email']) && !empty($_POST['email']) &&
                    isset($_FILES['foto']) ){
                    
                    echo 'Proveedor cargado';
                }else{
                    echo 'Debe ingresar datos del proveedor';
                }
            }else{
                echo 'Se debe llamar con el metodo POST';
            }
        }

        

    }
?>