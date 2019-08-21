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

       

    }
?>