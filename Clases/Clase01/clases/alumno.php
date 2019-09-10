<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    class Alumno extends Persona{
        public $legajo;

        function __construct( $nombre, $apellido, $legajo ){
            parent::__construct( $nombre, $apellido );
            $this->legajo = $legajo;
        }

        function toJSON(){
            return json_encode($this);            
        }

        public static function cargarAlumno(){
            if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
                if( isset($_POST['nombre'], $_POST['apellido'], $_POST['legajo']) && 
                    !empty($_POST['nombre']) && $_POST['legajo'] && $_POST['apellido']){
                    
                        $alumno = new Alumno( $_POST['nombre'], $_POST['apellido'], $_POST['legajo'] );
                    AlumnoDAO::guardarUnoEnArchivo( './archivos/alumnos.json', $alumno );
                    echo '{"mensaje":"Alumno cargado"}';
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

        public static function retornarAlumnos(){
            $datos = AlumnoDAO::leerTodos('./archivos/alumnos.json');
            $alumnos = array();
            foreach ($datos as $key => $value) {
                array_push($alumnos, new Alumno($value->nombre, $value->apellido, $value->legajo));
            }
            return $alumnos;
        }
        
        public static function manejarArchivo(){
            $origen = $_FILES["imagen"]["tmp_name"];
            $nombreOriginal = $_FILES["imagen"]["name"];
            $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            //$destinoFoto = './img/' . rand(10,100) . '.' . $ext;
            //$destinoFoto = './img/' . date("Ymd") . time() . '.' . $ext; 
            $destinoFoto = './img/' . date("Y") . time() . '.' . $ext; 
            move_uploaded_file($origen, $destinoFoto);
        }




    }
?>