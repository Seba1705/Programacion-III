<?php
    class Materia{
        public $materia;
        public $codigo;
        public $cupo;
        public $aula;

        public function __construct( $materia, $codigo, $cupo, $aula ){
            $this->materia = $materia;
            $this->codigo = $codigo;
            $this->cupo = $cupo;
            $this->aula = $aula;
        }

        public function toCsv(){
            return $this->materia. ';' .$this->codigo. ';' .$this->cupo. ';' .$this->aula. PHP_EOL; 
        }

        //3-(1pts.) caso: cargarMateria(post):Se recibe el nombre dela materia,códigode materia,el cupo de alumnos y el aula donde se dicta y se guardan los datos en el archivo materias.txt, tomando como identificador elcódigode la materia.
        public static function cargarMateria(){
            if( Archivo::existePeticionPOST() ){
                if( isset($_POST['materia']) && !empty($_POST['materia']) &&
                    isset($_POST['codigo']) && !empty($_POST['codigo']) &&
                    isset($_POST['cupo']) && !empty($_POST['cupo']) &&
                    isset($_POST['aula']) && !empty($_POST['aula']) ){
                    if( !Materia::existeCodigo($_POST['codigo']) ){
                        $materia = new Materia( $_POST['materia'], $_POST['codigo'], $_POST['cupo'], $_POST['aula'] );
                        Archivo::guardarUno( './archivos/materias.txt', $materia );
                        echo 'Materia cargada';
                    }else{
                        echo 'Ya existe materia con ese codigo';
                    }
                }else{
                    echo 'Debe configurar todas las variables';
                }
            }else{ 
                echo 'Se debe llamar con el metodo POST';
            }
        }

        public static function existeCodigo( $codigo ){
            $materias = Archivo::retornarMaterias();
            foreach( $materias as $materia ){
                if( strcasecmp($materia->codigo, $codigo) == 0 )
                    return true;
            }
            return false;
        }

        //4-(2pts.) caso: inscribirAlumno(get):Se recibe nombre, apellido, mail del alumno, materia y código de la materia y se guarda en el archivo inscripciones.txt restando un cupo a la materia en el archivo materias.txt. Si no hay cupo o la materia no existe informar cada caso particular.
        public static function inscribirAlumno(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['nombre']) && !empty($_GET['nombre']) &&
                    isset($_GET['apelido']) && !empty($_GET['apelido']) &&
                    isset($_GET['email']) && !empty($_GET['email']) &&
                    isset($_GET['materia']) && !empty($_GET['materia']) &&
                    isset($_GET['codigo']) && !empty($_GET['codigo'])){
                    
                }else{
                    echo 'Debe configurar todas las variables';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }
    }
?>