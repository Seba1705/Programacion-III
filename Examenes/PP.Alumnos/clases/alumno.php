<?php
    class Alumno{
        public $nombre;
        public $apellido;
        public $email;
        public $foto;

        public function __construct( $nombre, $apellido, $email, $foto ){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->foto = $foto;
        }
    
        public function toCsv(){
            return $this->nombre. ';' .$this->apellido. ';' .$this->email. ';' . $this->foto. PHP_EOL;
        }
    
        public function toString(){
            return 'Nombre : ' .$this->nombre. ' Apellido: ' .$this->apellido. ' Email: ' .$this->email. ' Foto: ' .$this->foto. PHP_EOL;
        }

        public static function mostrarAlumno( $alumno ){
            echo $alumno->toString();
        }

        public static function buscarPorEmail( $email ){
            $alumnos = Archivo::retornarAlumnos();
            foreach( $alumnos as $alumno ){
                if( strcasecmp($alumno->email, $email) == 0 )
                    return $alumno;
            }
            return null;
        }

        //1-(2pt.) caso: cargarAlumno (post): Se debenguardar los siguientes datos: nombre, apellido, email y foto. Losdatos se guardan en el archivo de texto alumnos.txt, tomando el emailcomo identificador.
        public static function cargarAlumno(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['apellido']) && !empty($_POST['apellido']) &&
                    isset($_POST['email']) && !empty($_POST['email']) &&
                    isset($_FILES['foto']) ){
                    if( !Alumno::existeEmail( $_POST['email']) ){
                        $origen = $_FILES["foto"]["tmp_name"];
                        $nombreOriginal = $_FILES["foto"]["name"];
                        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                        $destinoFoto = "./img/".$_POST['email'].".".$ext;

                        $alumno = new Alumno( $_POST['nombre'], $_POST['apellido'], $_POST['email'], $destinoFoto );
                        move_uploaded_file($origen, $destinoFoto);
                        Archivo::guardarUno( './archivos/alumnos.txt', $alumno );

                        echo 'Alumno cargado';
                    }else{
                        echo 'Ya existe un alumno con ese email';
                    }
                }else{
                    echo 'Debe configurar todas las variables';
                }
            }else{
                echo 'Se debe llamar con el metodo POST';
            }
        }

        public static function existeEmail( $email ){
            $alumnos = Archivo::retornarAlumnos();
            foreach( $alumnos as $alumno ){
                if( strcasecmp($alumno->email, $email) == 0 )
                    return true;
            }
            return false;
        }

        //2-(2pt.) caso: consultarAlumno (get):Se ingresa apellido, si coincide con algún registro del archivo alumno.txt se retorna todoslos alumnos con dicho apellido, si no coincide se debe retornar “No existe alumno con apellido xxx”(xxx es el apellido que se busco)La búsquedatiene que ser case insensitive.
        public static function consultarAlumno(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['apellido']) && !empty($_GET['apellido']) ){
                    $apellido = $_GET['apellido'];
                    $alumnos = array_filter( Archivo::retornarAlumnos(), function( $alumno ) use ( $apellido ){
                        return strcasecmp($alumno->apellido, $apellido) == 0;
                    });
                    echo 'Alumnos con el apellido ' . $apellido . PHP_EOL . PHP_EOL;
                    if( count($alumnos) > 0){
                        array_map( 'Alumno::mostrarAlumno', $alumnos );
                    }else{
                        echo 'No hay alumnos con el apellido ' . $apellido;
                    }
                }else{
                    echo 'Debe ingresar un apellido para realizar la consulta';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }

        //7-(2 pts.) caso: modificarAlumno(post): Debe poder modificar todos los datos del alumno menos el email y guardar la foto antigua en la carpeta/backUpFotos, el nombre seráel apellido y la fecha.
        public static function modificarAlumno(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['apellido']) && !empty($_POST['apellido']) &&
                    isset($_POST['email']) && !empty($_POST['email']) &&
                    isset($_FILES['foto']) ){
                    if( Alumno::existeEmail( $_POST['email']) ){
                        $alumnos = Archivo::retornarAlumnos();
                        foreach( $alumnos as $item ){
                            if( strcasecmp($item->email, $_POST['email']) == 0){
                                $item->nombre = $_POST['nombre'];
                                $item->apellido = $_POST['apellido'];

                                $origen = $_FILES["foto"]["tmp_name"];
                                $nombreOriginal = $_FILES["foto"]["name"];
                                $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                                $destinoFoto = "./img/".$item->email.".".$ext;
                                
                                if(file_exists($destinoFoto)){
                                    copy($destinoFoto, "./backUpFotos/".$item->apellido."_".date("Ymd").".".$ext);
                                }
                                move_uploaded_file($origen, $destinoFoto);
                                echo 'Se modifico el alumno: ' . $item->toString();
                                break;
                            }
                        }
                        Archivo::guardarTodos( './archivos/alumnos.txt', $alumnos );
                    }else{
                        echo 'No existe un alumno con ese email';
                    }
                }else{
                    echo 'Debe configurar todas las variables';
                }
            }else{  
                echo 'Se debe llamar con el metodo POST';
            }
        }

        //8-(2 pts.) caso:alumnos(get): Mostrar una tabla con todos los datos de los alumnos, incluida la foto.
        public static function alumnos(){
            if( Archivo::existePeticionGET() ){
                echo 'Alumnos' . PHP_EOL . PHP_EOL;
                array_map( 'Alumno::mostrarAlumno', Archivo::retornarAlumnos());
            }else{
                echo 'Debe llamarse con el metodo GET';
            }
        }
    }
?>