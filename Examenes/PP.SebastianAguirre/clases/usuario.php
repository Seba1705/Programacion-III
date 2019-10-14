<?php
    class Usuario{
        public $legajo;
        public $email;
        public $nombre;
        public $clave;
        public $imagen;
        public $foto;

        public function __construct($legajo, $email, $nombre, $clave, $imagen, $foto){
            $this->legajo = $legajo;
            $this->email = $email;
            $this->nombre = $nombre;
            $this->clave = $clave;
            $this->foto = $foto;
            $this->imagen = $imagen;
        }

        public function toJSON(){
            return json_encode($this);
        }

        public static function alta($objeto){
            Archivo::guardarUno('./archivos/usuarios.txt', $objeto);
        }
     
        public static function retornarUsuarios(){
            $datos = Archivo::leerArchivo('./archivos/usuarios.txt');
            $usuarios = array();
            foreach ($datos as $key => $value) {
                $usuario = new Usuario($value->legajo, $value->email, $value->nombre, $value->clave, $value->imagen, $value->foto);
                array_push($usuarios, $usuario);
            }
            return $usuarios;
        }

        public static function validarLegajo($legajo){
            $usuarios = Usuario::retornarUsuarios();
            foreach($usuarios as $item){
                if(strcasecmp($item->legajo, $legajo) == 0)
                    return true;
            }
            return false;
        }
        
        public static function modificar($datos, $img){
            $lista = Usuario::retornarUsuarios();
            foreach( $lista as $item ){
                if(strcasecmp($item->legajo, $datos['legajo']) == 0){
                    // Imagen
                    $origen = $img['imagen']->file;
                    $nombreImagen = $img['imagen']->getClientFilename();
                    $ext = pathinfo($nombreImagen, PATHINFO_EXTENSION);
                    $destinoImagen = $item->imagen;
                    if(file_exists($destinoImagen)){
                        copy($destinoImagen,"./backUp/".$item->legajo."_imagen".date("Ymd").".".$ext);
                    }
                    move_uploaded_file($origen, $destinoImagen);
                    //Foto
                    $origen2 = $img['foto']->file;
                    $nombreFoto = $img['foto']->getClientFilename();
                    $ext2 = pathinfo($nombreFoto, PATHINFO_EXTENSION);
                    $destinoFoto = $item->foto;
                    if(file_exists($destinoFoto)){
                        copy($destinoFoto,"./backUp/".$item->legajo."_foto".date("Ymd").".".$ext2);
                    }
                    move_uploaded_file($origen2, $destinoFoto);
                    // MODIFICAR DATOS
                    $item->nombre = $datos['nombre'];
                    $item->email = $datos['email'];
                    $item->clave = $datos['clave'];
                    
                    break;
                }
            }
            Archivo::guardarTodos('./archivos/usuarios.txt', $lista);
            echo '{"mensaje":"Se modifico correctamente"}';
        }

        public static function mostrarUno($usuario){
            echo  '{"legajo":"' .$usuario->legajo. '",
                    "email":"' .$usuario->email. '",
                    "nombre":"' .$usuario->nombre. '",
                    "foto":"' .$usuario->foto. '",
                    "imagen":"' .$usuario->imagen. '"}';
        }
    }
?>