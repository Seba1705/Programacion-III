<?php
    class Servicio{
        public $id;
        public $tipo;
        public $precio;
        public $demora;

        function __construct($id, $tipo, $precio, $demora){
            $this->id = $id;
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->demora = $demora;
        }

        public function toCSV(){
            $sep = ";";
            return $this->id . $sep . $this->tipo . $sep . $this->precio . $sep . $this->demora . PHP_EOL;
        }

        public function toString(){
            return  'id: ' . $this->id . ' tipo: '.$this->tipo . ' precio: ' . $this->precio . ' demora: ' . $this->demora . PHP_EOL;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CARGAR TIPO SERVICIO
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function cargarTipoServicio(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //VERIFICAMOS QUE ESTEN TODAS LAS VARIABLES
                if( isset($_POST['tipo']) && !empty($_POST['tipo']) &&
                    isset($_POST['precio']) && !empty($_POST['precio']) &&
                    isset($_POST['demora']) && !empty($_POST['demora'])){ 
                    if(Servicio::validarTipoServicio($_POST['tipo'])){
                        $id = Servicio::generarNuevoId();
                        $servicio = new Servicio($id, $_POST['tipo'],$_POST['precio'],$_POST['demora']);
                        Servicio::guardarServicioEnArchivo($servicio);
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

        public static function guardarServicioEnArchivo($servicio){
            $rutaArchivo = './archivos/servicios.txt';
            $archivo = fopen($rutaArchivo, 'a+');
            fwrite($archivo, $servicio->toCSV());
            fclose($archivo);
            echo 'Servicio guardado!';
        }

        public static function leerArchivoDeServicios(){
            $rutaArchivo = './archivos/servicios.txt';
            $retorno = array(); //Lo va a devolver con las entidades leidas
            $archivo = fopen($rutaArchivo, 'r');
            do{
                $servicio = trim(fgets($archivo));
                if ($servicio != ""){
                    $servicio = explode(';', $servicio);
                    array_push($retorno, new Servicio($servicio[0], $servicio[1],$servicio[2], $servicio[3]));
                }
            }while(!feof($archivo));
            fclose($archivo); 
            return $retorno;   
        }

    }
?>