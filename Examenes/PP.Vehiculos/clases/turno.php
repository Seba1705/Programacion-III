<?php
    class Turno{
        public $fecha;
        public $patente;
        public $marca;
        public $precio;
        public $tipo;
        
        function __construct($fecha, $patente, $marca, $precio, $tipo){
            $this->marca = $marca;
            $this->tipo = $tipo;
            $this->patente = $patente;
            $this->precio = $precio;
            $this->fecha = $fecha;
        }

        public function toJSON(){
            return json_encode($this);
        }

        /*4- (2pts.) caso: sacarTurno (get): Se recibe patente, precio y fecha (día) y se debe guardar en el archivo
        turnos.txt, fecha, patente, modelo, precio y tipo de servicio. Si no hay cupo o la materia no existe informar cada
        caso particular.*/
        public static function sacarTurno(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if( isset($_POST['marca']) && !empty($_POST['marca']) &&
                    isset($_POST['tipo']) && !empty($_POST['tipo']) &&
                    isset($_POST['patente']) && !empty($_POST['patente']) && 
                    isset($_POST['fecha']) && !empty($_POST['fecha']) &&
                    isset($_POST['precio']) && !empty($_POST['precio'])){ 
                    
                    if(Turno::validarFecha($_POST['fecha'])){
                        if(Servicio::validarTipoServicio($_POST['tipo'])){
                            $turno = new Turno($_POST['fecha'], $_POST['patente'], $_POST['marca'], $_POST['precio'],$_POST['tipo']);
                            Archivo::guardarUno('./archivos/turnos.txt', $turno);
                            echo 'Turno guardado';
                        }else{
                            echo 'Ingrese un tipo de servicio valido';
                        }
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

        public static function retornarTurnos(){
            $datos = Archivo::leerArchivo('./archivos/turnos.txt');
            $turnos = array();
            foreach ($datos as $key => $value) {
                $item = new Turno($value->fecha, $value->patente, $value->marca, $value->precio, $value->tipo);
                array_push($turnos, $item);
            }
            return $turnos;
        }

        /*5- (1pt.) caso: turnos(get): Se devuelve un tabla con todos los servicios.*/
        public static function turnos(){
            echo 'Servicios' .PHP_EOL.PHP_EOL;

            array_map('Servicio::mostrarServicio', Servicio::retornarServicios());
        }

        public static function mostrarTurno($turno){
            echo $turno->toJson() . PHP_EOL;
        }
    }

?>