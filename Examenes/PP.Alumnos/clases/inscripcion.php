<?php
    class Inscripcion{
        public $nombre;
        public $apellido;
        public $mail;
        public $materia;
        public $codigo;

        public function __construct( $nombre, $apellido, $mail, $materia, $codigo ){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->mail = $mail;
            $this->materia = $materia;
            $this->codigo = $codigo;
        }

        public function toCsv(){
            return $this->nombre. ';' .$this->apellido. ';' .$this->mail. ';' .$this->materia. ';' .$this->codigo. PHP_EOL;
        }

        public function toString(){
            return 'Nombre: ' . $this->nombre. ', Apellido: ' .$this->apellido. ', Email: ' .$this->mail. ', Materia: ' .$this->materia. ',             Codigo: ' .$this->codigo. PHP_EOL;
        }

        public static function mostrarInscripcion( $inscripcion ){
            echo $inscripcion->toString();
        }

        //4-(2pts.) caso: inscribirAlumno(get): Se recibe nombre, apellido, mail del alumno, materia y código de la materia y se guarda en el archivo inscripciones.txt restando un cupo a la materia en el archivo materias.txt. Si no hay cupo o la materia no existe informar cada caso particular.
        public static function inscribirAlumno(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['nombre'], $_GET['email'], $_GET['materia'], $_GET['codigo'], $_GET['apellido']) &&
                    !empty($_GET['nombre']) && !empty($_GET['email']) && 
                    !empty($_GET['materia']) && !empty($_GET['codigo']) && !empty($_GET['apellido'])){
                    if( Alumno::existeEmail($_GET['email'])){
                        if( Materia::existeCodigo($_GET['codigo'])){
                            if( Inscripcion::validarCupoMateria($_GET['codigo'])){
                                $inscripcion = new Inscripcion( $_GET['nombre'],
                                                        $_GET['apellido'],
                                                        $_GET['email'],
                                                        $_GET['materia'],
                                                        $_GET['codigo']);
                                Archivo::guardarUno( './archivos/inscripciones.txt', $inscripcion );
                                echo 'Inscripcion correcta';
                            }
                        }else{
                            echo 'No existe materia con ese codigo';
                        }
                    }else{
                        echo 'No existe alumno con ese email';
                    }
                }else{
                    echo 'Debe configurar todas las variables';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }

        public static function validarCupoMateria( $codigo ){
            $materias = Archivo::retornarMaterias();
            $retorno = false;
            foreach( $materias as $materia ){
                if( strcasecmp($materia->codigo, $codigo) == 0 && $materia->cupo > 0 ){
                    $materia->cupo--;
                    $retorno = true;
                }  
            }
            if( $retorno )
                Archivo::guardarTodos( './archivos/materias.txt', $materias );
            return $retorno;
        }

        //5-(1pt.) caso: inscripciones(get): Se devuelve un tabla con todos los alumnos inscriptos a todas las materias.
        public static function inscripciones(){
            if( Archivo::existePeticionGET() ){
                echo 'INSCRIPCIONES' . PHP_EOL . PHP_EOL;
                array_map( 'Inscripcion::mostrarInscripcion', Archivo::retornarInscripciones() );
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
        }
        
        //6-(2pts.) caso: inscripciones(get): Puede recibir el parámetro materia o apellido y filtra la tabla de acuerdo al parámetropasado.
        public static function inscripcionesParametro(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['materia']) || isset($_GET['apellido'])){
                    $apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
                    $materia = isset($_GET['materia']) ? $_GET['materia'] : '';
                    $filtradosPorApellido = array_filter( Archivo::retornarInscripciones(), function( $insc ) use( $apellido ){
                        return strcasecmp($insc->apellido, $apellido) == 0; 
                    });
                    $filtradosPorMateria = array_filter( Archivo::retornarInscripciones(), function( $insc ) use( $materia ){
                        return strcasecmp($insc->materia, $materia) == 0; 
                    });
                    echo 'FILTRADOS POR APELLIDO' . PHP_EOL . PHP_EOL;
                    array_map( 'Inscripcion::mostrarInscripcion', $filtradosPorApellido );
                    echo PHP_EOL. 'FILTRADOS POR MATERIA' . PHP_EOL . PHP_EOL;
                    array_map( 'Inscripcion::mostrarInscripcion', $filtradosPorMateria );
                }else{
                    echo 'Debe ingresar un parametro de busqueda';
                }
            }else{
                echo 'Se debe llamar con el metodo GET';
            }
            
        }
    }
?>