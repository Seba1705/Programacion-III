<?php
    class Servicio{
        public $id;
        public $nombre;
        public $tipo;
        public $precio;
        public $demora;

        function __construct($id, $nombre, $tipo, $precio, $demora){
            $this->id = $id;
            $this->nombre = $nombre;
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->demora = $demora;
        }

        public function toJson(){
            return json_encode($this);
        }

        /*3- (1 pts.) caso: cargarTipoServicio(post): Se recibe el nombre del servicio a realizar: id, tipo(de los 10.000km,
        20.000km, 50.000km), precio y demora, y se guardara en el archivo tiposServicio.xxx.*/
        public static function cargarTipoServicio(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //VERIFICAMOS QUE ESTEN TODAS LAS VARIABLES
                if( isset($_POST['nombre']) && !empty($_POST['nombre']) &&
                    isset($_POST['tipo']) && !empty($_POST['tipo']) &&
                    isset($_POST['precio']) && !empty($_POST['precio']) &&
                    isset($_POST['demora']) && !empty($_POST['demora'])){ 
                    if(Servicio::validarTipoServicio($_POST['tipo'])){
                        $id = Servicio::generarNuevoId();
                        $servicio = new Servicio($id, $_POST['nombre'], $_POST['tipo'], $_POST['precio'], $_POST['demora']);
                        Archivo::guardarUno('./archivos/tiposServicio.txt', $servicio);
                        echo "Servicio cargado";
                    }
                    else{
                        echo "Ingrese un tipo de servicio valido.";
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

        public static function generarNuevoId(){
            $rutaArchivo = './archivos/id.txt';      
            $archivo = fopen($rutaArchivo, 'r');
            $ultimoId = fgets($archivo);
            fclose($archivo);
            $nuevoId = number_format($ultimoId) + 1;
            $nuevoArchivo = fopen($rutaArchivo, 'w');
            fwrite($nuevoArchivo, $nuevoId);
            
            return $nuevoId;
        }

        public static function validarTipoServicio($tipo){
            if($tipo == 10000 || $tipo == 50000 || $tipo == 20000){
                return true;
            }
            return false;
        }

        public static function retornarServicios(){
            $datos = Archivo::leerArchivo('./archivos/tiposServicio.txt');
            $servicios = array();
            foreach ($datos as $key => $value) {
                $item = new Servicio($value->id, $value->nombre, $value->tipo, $value->precio, $value->demora);
                array_push($servicios, $item);
            }
            return $servicios;
        }

        /*6- (2pts.) caso: servicio(get): Puede recibir el tipo de servicio o la fecha y filtra la tabla de acuerdo al parámetro pasado.*/
        public static function servicio(){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                //VERIFICAMOS QUE ESTEN TODAS LAS VARIABLES
                if( (isset($_GET['tipo']) && !empty($_GET['tipo'])) || (isset($_GET['fecha']) && !empty($_GET['fecha']))) { 
                    if(isset($_GET['tipo'])){
                        if(Servicio::validarTipoServicio($_GET['tipo'])){
                            $tipo = $_GET['tipo'];
                            $consultados = array_filter(Turno::retornarTurnos(), function( $objeto ) use ( $tipo ){
                                return strcasecmp( $objeto->tipo, $tipo ) == 0;
                            });
                            echo PHP_EOL . 'Filtrados por Tipo: ' . $tipo . PHP_EOL . PHP_EOL;
                            if(count($consultados) == 0) 
                                echo 'No hay turnos' .PHP_EOL;
                            array_map('Turno::mostrarTurno', $consultados);
                        }else{
                            echo 'Ingrese un tipo valido';
                        }
                    }
                    if(isset($_GET['fecha'])){
                        if(Turno::validarFecha($_GET['fecha'])){
                            $fecha = $_GET['fecha'];
                            $consultados = array_filter(Turno::retornarTurnos(), function( $objeto ) use ( $fecha ){
                                return strcasecmp( $objeto->fecha, $fecha ) == 0;
                            });
                            echo PHP_EOL . 'Filtrados por Fecha: ' . $fecha . PHP_EOL . PHP_EOL;
                            if(count($consultados) == 0) 
                                echo 'No hay turnos' .PHP_EOL;
                            array_map('Turno::mostrarTurno', $consultados);
                        }else{
                            echo 'ingrese fecha valida';
                        }
                    }
                }
                else{
                    echo "Debe ingresar patente o marca.";
                }
            }
            else{
                echo "ERROR: Se debe llamar con metodo GET.";
            }
        }

        public static function mostrarServicio($servicio){
            echo $servicio->toJson() . PHP_EOL;
        }

    }
?>