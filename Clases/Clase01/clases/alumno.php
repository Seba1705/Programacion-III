<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    require_once 'persona.php';


    class Alumno extends Persona{
        public $legajo;
        public $foto;

        function __construct( $nombre, $apellido, $legajo, $foto ){
            parent::__construct( $nombre, $apellido );
            $this->legajo = $legajo;
            $this->foto = $foto;
        }

        function toJSON(){
            return json_encode($this);            
        }

        public static function cargarAlumno(){
            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
                if( isset($_POST['nombre'], $_POST['apellido'], $_POST['legajo']) && 
                    !empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['legajo'])){
                    if(!Alumno::existeAlumno( $_POST['legajo'])){

                        $origen = $_FILES["foto"]["tmp_name"];
                        $nombreOriginal = $_FILES["foto"]["name"];
                        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION); 
                        $destinoFoto = '../Clase01/img/' . $_POST['legajo'] . '.' . $ext; 
                        move_uploaded_file($origen, $destinoFoto);
                        $alumno = new Alumno( $_POST['nombre'], $_POST['apellido'], $_POST['legajo'], $destinoFoto );
                        AlumnoDAO::guardarUnoEnArchivo( '../Clase01/archivos/alumnos.json', $alumno );
                        echo '{"mensaje":"Alumno cargado"}';

                    }else{
                        echo '{"mensaje":"Ya existe alumno con ese legajo"}';
                    }
                }else{
                    echo '{"mensaje":"Se deben configurar todas las variables"}';
                }
            }else{
                echo '{"mensaje":"Se debe llamar con el metodo POST"}';
            }
        }

        public static function mostrarAlumnos(){
            if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
                $alumnos = Alumno::retornarAlumnos();
                foreach ($alumnos as $value) {
                    echo $value->toJSON() . PHP_EOL;
                }
            }else{
                echo '{"mensaje":"Se debe llamar con el metodo GET"}';
            }
        }

        public static function mostrarImagenes(){
            if( $_SERVER['REQUEST_METHOD'] == 'GET' ){
                $alumnos = Alumno::retornarAlumnos();
                foreach ($alumnos as $value) {
                    echo '<img src="' . $value->foto . '"/><br><br>';
                }
            }else{
                echo '{"mensaje":"Se debe llamar con el metodo GET"}';
            }
        }

        public static function retornarAlumnos(){
            $datos = AlumnoDAO::leerTodos('../Clase01/archivos/alumnos.json');
            $alumnos = array();
            foreach ($datos as $key => $value) {
                array_push($alumnos, new Alumno($value->nombre, $value->apellido, $value->legajo, $value->foto));
            }
            return $alumnos;
        }
        
        /*public static function manejarArchivo(){
            $origen = $_FILES["imagen"]["tmp_name"];
            $nombreOriginal = $_FILES["imagen"]["name"];
            $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            //$destinoFoto = './img/' . rand(10,100) . '.' . $ext;
            //$destinoFoto = './img/' . date("Ymd") . time() . '.' . $ext; 
            $destinoFoto = './img/' . date("Y") . time() . '.' . $ext; 
            move_uploaded_file($origen, $destinoFoto);
        }*/

        public static function existeAlumno( $legajo ){
            $alumnos = Alumno::retornarAlumnos();
            foreach( $alumnos as $alumno ){
                if( strcasecmp($alumno->legajo, $legajo) == 0)
                    return true;
            }
            return false;
        }

        public static function modificarAlumno(){
            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
                if( isset($_POST['nombre'], $_POST['apellido'], $_POST['legajo'], $_FILES['foto']) && 
                    !empty($_POST['nombre']) && $_POST['legajo'] && $_POST['apellido']){
                    if(Alumno::existeAlumno( $_POST['legajo'])){  
                        $alumnos = Alumno::retornarAlumnos();
                        foreach( $alumnos as $alumno ){
                            if( strcasecmp($alumno->legajo, $_POST['legajo']) == 0){

                                $alumno->nombre = $_POST['nombre'];
                                $alumno->apellido = $_POST['apellido'];

                                $origen = $_FILES["foto"]["tmp_name"];
                                $nombreOriginal = $_FILES["foto"]["name"];
                                $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION); 
                                $destinoFoto = $alumno->foto;
                                if( file_exists($destinoFoto) ){
                                    copy( $destinoFoto, './backUp/' . $alumno->legajo . '_' . date('Ymd') . '.' . $ext );
                                }
                                move_uploaded_file($origen, $destinoFoto);
                            }
                        }
                        AlumnoDAO::guardarTodos('./archivos/alumnos.json', $alumnos ); 
                        echo '{"mensaje":"Alumno modificado"}';
                    }else{
                        echo '{"mensaje":"No existe alumno con ese legajo"}';
                    }
                }else{
                    echo '{"mensaje":"Se deben configurar todas las variables"}';
                }
            }else{
                echo '{"mensaje":"Se debe llamar con el metodo POST"}';
            }
        }
    }
?>