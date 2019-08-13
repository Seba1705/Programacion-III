<?php
    class Usuario {
        private $nombre;
        private $clave;

        public function __construct( $nombre, $clave ){
            $this->nombre = $nombre;
            $this->clave = $clave; 
        }

        public function toCsv(){
            $sep = ';';
            return $this->nombre . $sep . $this->clave . PHP_EOL;
        }

        public function toString(){
            return 'Nombre: ' . $this->nombre . ', Clave: ' . $this->clave;
        }

        /////////////////////////////////////////////////////////////////
        // CREAR USUARIO
        /////////////////////////////////////////////////////////////////

        public static function crearUsuario(){
            if( Validaciones::existePeticionPOST() ){
                if( isset($_POST['nombre']) && 
                    !empty($_POST['nombre']) && 
                    isset($_POST['clave']) && 
                    !empty($_POST['clave']) ){
                    if( !Usuario::existeNombre($_POST['nombre']) ){
                        $usuario = new Usuario( $_POST['nombre'], $_POST['clave'] );
                        Archivo::guardarUno( './archivos/usuario.txt', $usuario );
                        echo 'Se creo el usuario: ' . $usuario->toString();
                    }else{
                        echo 'Ya existe un usuario con ese nombre';
                    }
                }else{
                    echo 'Debe ingresar nombre y clave';
                }
            }else{
                echo 'Debe ser llamado con el metodo POST';
            }
        }

        public static function existeNombre( $nombre ){
            $listaDeUsuarios = Archivo::leerUsuarios();
            foreach( $listaDeUsuarios as $usuario ){
                if( strcasecmp($usuario->nombre, $nombre) == 0 )
                    return true;
            } 
            return false;
        }

        /////////////////////////////////////////////////////////////////
        // LOGIN
        /////////////////////////////////////////////////////////////////

        public static function login(){
            if( Validaciones::existePeticionPOST() ){
                if( isset($_POST['nombre']) && 
                    !empty($_POST['nombre']) && 
                    isset($_POST['clave']) && 
                    !empty($_POST['clave']) ){
                    if( Usuario::existeNombre($_POST['nombre']) ){
                        $usuario = Usuario::buscarPorNombre($_POST['nombre']);
                        if( strcasecmp( $usuario->clave, $_POST['clave']) == 0){
                            echo 'true';
                        }else{
                            echo 'La clave es incorrecta';
                        }
                    }else{
                        echo 'No existe un usuario con ese nombre';
                    }
                    
                }else{
                    echo 'Debe ingresar nombre y clave';
                }
            }else{
                echo 'Debe ser llamado con el metodo POST';
            }
        }

        public static function buscarPorNombre( $nombre ){
            $listaDeUsuarios = Archivo::leerUsuarios();
            foreach( $listaDeUsuarios as $usuario ){
                if( strcasecmp($usuario->nombre, $nombre) == 0 )
                    return $usuario;
            } 
            return false;
        }

        /////////////////////////////////////////////////////////////////
        // LISTAR USUARIOS
        /////////////////////////////////////////////////////////////////

        public static function listarUsuarios(){
            if( Validaciones::existePeticionGET()){
                if( isset($_GET['nombre']) && !empty($_GET['nombre']) ){
                    if( Usuario::existeNombre( $_GET['nombre']) ){
                        $buscado = Usuario::buscarPorNombre( $_GET['nombre'] );
                        echo $buscado->toString();
                    }else{
                        echo 'No existe usuario con el nombre ' . $_GET['nombre'];
                    }
                }else{
                    echo 'Debe ingresar un nombre';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }
    }
?>