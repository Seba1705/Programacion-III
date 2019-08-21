<?php
    class Inscripcion{
        public $nombre;
        public $apellido;
        public $mail;
        public $materia;
        public $codigo;

        public function __construct( $nombre, $apellido, $mail, $materia, $codigo ){
            $this->nombre;
            $this->apellido;
            $this->mail = $mail;
            $this->materia = $materia;
            $this->codigo = $codigo;
        }

        //4-(2pts.) caso: inscribirAlumno(get):Se recibe nombre, apellido, mail del alumno, materia y código de la materia y se guarda en el archivo inscripciones.txt restando un cupo a la materia en el archivo materias.txt. Si no hay cupo o la materia no existe informar cada caso particular.
        public static function inscribirAlumno(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['nombre'], $_GET['email'], $_GET['materia'], $_GET['codigo']) &&
                    !empty($_GET['nombre']) && !empty($_GET['email']) && !empty($_GET['materia']) && !empty($_GET['codigo'])){
                    echo 'entre';
                }else{
                    echo 'Debe configurar todas las variables';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }
    }
?>