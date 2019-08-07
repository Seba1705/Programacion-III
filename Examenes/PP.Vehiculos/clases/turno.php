<?php
    class Turno{
        private $fecha;
        private $patente;
        private $marca;
        private $precio;
        private $tipo;
        
        function __construct($marca, $tipo, $patente, $precio, $fecha){
            $this->marca = $marca;
            $this->tipo = $tipo;
            $this->patente = $patente;
            $this->precio = $precio;
            $this->fecha = $fecha;
        }

        public function toCSV(){
            $sep = ";";
            return $this->marca . $sep . $this->tipo . $sep . $this->patente . $sep . $this->precio . $sep . $this->fecha . PHP_EOL;
        }

        public function toString(){
            return  'Marca: ' . $this->marca . ' tipo: '.$this->tipo . ' Patente: ' . $this->patente . ' Precio: ' . $this->precio . ' Fecha ' . $this->fecha .PHP_EOL;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // SACAR TURNO
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function sacarTurno(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if( isset($_POST['marca']) && !empty($_POST['marca']) &&
                    isset($_POST['tipo']) && !empty($_POST['tipo']) &&
                    isset($_POST['patente']) && !empty($_POST['patente']) && 
                    isset($_POST['fecha']) && !empty($_POST['fecha']) &&
                    isset($_POST['precio']) && !empty($_POST['precio'])){ 
                    
                    if(Turno::validarFecha($_POST['fecha'])){
                        $turno = new Turno($_POST['marca'], $_POST['tipo'], $_POST['patente'], $_POST['precio'],$_POST['fecha']);
                        Turno::guardarTurnoEnArchivo($turno);
                    }
                    else{
                        echo 'Ingrese una dia del 1 al 30';
                    }
                }
                else{
                    echo "No se configuraron todas las variables.";
                }
            }
            else{
                echo "ERROR: Se debe llamar con metodo POST.";
            }
        }

        public static function validarFecha($fecha){
            $fecha = number_format($fecha);
            if($fecha < 1 || $fecha > 30 ){
                return false;
            }
            return true;
        }

        public static function guardarTurnoEnArchivo($turno){
            $rutaArchivo = './archivos/turnos.txt';
            $archivo = fopen($rutaArchivo, 'a+');
            fwrite($archivo, $turno->toCSV());
            fclose($archivo);
            echo 'turno guardado!';
        }

        public static function leerArchivoDeTurnos(){
            $rutaArchivo = './archivos/turnos.txt';
            $retorno = array(); //Lo va a devolver con las entidades leidas
            $archivo = fopen($rutaArchivo, 'r');
            do{
                $turno = trim(fgets($archivo));
                if ($turno != ""){
                    $turno = explode(';', $turno);
                    array_push($retorno, new turno($turno[0], $turno[1],$turno[2], $turno[3], $turno[4]));
                }
            }while(!feof($archivo));
            fclose($archivo); 
            return $retorno;   
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // TURNOS
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function turnos(){
            $turnos = Turno::leerArchivoDeTurnos();
            foreach($turnos as $turno){
                echo $turno->toString();
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // INSCRIPCIONES
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function inscripciones(){
            $vehiculos = Vehiculo::leerArchivoDeVehiculos();
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                if( isset($_GET['parametro']) && !empty($_GET['parametro']) ){
                    $parametro = $_GET['parametro'];
                    $turnos = Turno::leerArchivoDeTurnos();
                    $filtrados = [];
                    foreach($turnos as $turno){
                        if(Turno::existeParametroEnTurno($turno, $parametro)){
                            array_push($filtrados, $turno);
                        }
                    }
                    if(sizeof($filtrados) > 0){
                        foreach($filtrados as $turno){
                            echo $turno->toString();
                        }
                    }
                    else{
                        echo 'No existe ' . $parametro;
                    }
                }
                else{
                    echo "Debe ingresar un parametro de busqueda.";
                }
            }
            else{
                echo "ERROR: Se debe llamar con metodo GET.";
            }
        }
        
        public static function existeParametroEnTurno($turno, $parametro){
            if( strcasecmp($turno->tipo, $parametro) == 0 ||  
                strcasecmp($turno->fecha, $parametro) == 0){
                return true;    
            }
            return false;
        }

    }
?>