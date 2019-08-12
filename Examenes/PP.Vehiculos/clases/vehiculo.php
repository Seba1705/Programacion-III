<?php
    class Vehiculo{

        public $marca;
        public $modelo;
        public $patente;
        public $precio;

        function __construct($marca, $modelo, $patente, $precio){
            $this->marca = $marca;
            $this->modelo = $modelo;
            $this->patente = $patente;
            $this->precio = $precio;
        }

        public function toCSV(){
            $sep = ";";
            return $this->marca . $sep . $this->modelo . $sep . $this->patente . $sep . $this->precio . PHP_EOL;
        }

        public function toString(){
            return  'Marca: ' . $this->marca . ' Modelo: '.$this->modelo . ' Patente: ' . $this->patente . ' Precio: ' . $this->precio . PHP_EOL;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CARGAR VEHICULO
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function cargarVehiculo(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //VERIFICAMOS QUE ESTEN TODAS LAS VARIABLES
                if( isset($_POST['marca']) && !empty($_POST['marca']) &&
                    isset($_POST['modelo']) && !empty($_POST['modelo']) &&
                    isset($_POST['patente']) && !empty($_POST['patente']) && 
                    isset($_POST['precio']) && !empty($_POST['precio'])){ 
                    if(Vehiculo::existePatente($_POST['patente']))  {
                        echo 'Ya existe vehiculo con esa patente';
                    }
                    else{
                        $vehiculo = new Vehiculo($_POST['marca'], $_POST['modelo'], $_POST['patente'], $_POST['precio']);
                        Vehiculo::guardarVehiculoEnArchivo($vehiculo);
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

        public static function guardarVehiculoEnArchivo($vehiculo){
            $rutaArchivo = './archivos/vehiculos.txt';
            $archivo = fopen($rutaArchivo, 'a+');
            fwrite($archivo, $vehiculo->toCSV());
            fclose($archivo);
            echo 'Vehiculo guardado!';
        }

        public static function leerArchivoDeVehiculos(){
            $rutaArchivo = './archivos/vehiculos.txt';
            $retorno = array(); //Lo va a devolver con las entidades leidas
            $archivo = fopen($rutaArchivo, 'r');
            do{
                $vehiculo = trim(fgets($archivo));
                if ($vehiculo != ""){
                    $vehiculo = explode(';', $vehiculo);
                    array_push($retorno, new Vehiculo($vehiculo[0], $vehiculo[1],$vehiculo[2], $vehiculo[3]));
                }
            }while(!feof($archivo));
            fclose($archivo); 
            return $retorno;   
        }

        public static function existePatente($patente){
            $listaDeVehiculos = Vehiculo::leerArchivoDeVehiculos();
            foreach($listaDeVehiculos as $vehiculo){
                if(strcasecmp(($vehiculo->patente), $patente) == 0){
                    return true;
                }
            }
            return false;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // CONSULTAR VEHICULO
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function consultarVehiculo(){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                if( isset($_GET['parametro']) && !empty($_GET['parametro']) ){
                    $parametro = $_GET['parametro'];
                    $vehiculos = Vehiculo::leerArchivoDeVehiculos();
                    $filtrados = [];
                    foreach($vehiculos as $vehiculo){
                        if(Vehiculo::existeParametroEnVehiculo($vehiculo, $parametro)){
                            array_push($filtrados, $vehiculo);
                        }
                    }
                    if(sizeof($filtrados) > 0){
                        foreach($filtrados as $vehiculo){
                            echo $vehiculo->toString();
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
        
        public static function existeParametroEnVehiculo($vehiculo, $parametro){
            if( strcasecmp($vehiculo->marca, $parametro) == 0 || 
                strcasecmp($vehiculo->modelo, $parametro) == 0 || 
                strcasecmp($vehiculo->patente, $parametro) == 0){
                return true;    
            }
            return false;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // MODIFICAR VEHICULO
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function modificarVehiculo(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if( isset($_POST['patente']) && 
                    !empty($_POST['patente']) && 
                    Vehiculo::existePatente($_POST['patente'])){  
                    
                    if( isset($_POST['marca']) && !empty($_POST['marca']) &&
                        isset($_POST['modelo']) && !empty($_POST['modelo']) &&
                        isset($_POST['precio']) && !empty($_POST['precio']) && 
                        isset($_FILES['foto'])){
                        
                        $vehiculos = Vehiculo::leerArchivoDeVehiculos();
                        foreach( $vehiculos as $vehiculo ){
                            if(strcasecmp($vehiculo->patente, $_POST['patente']) == 0){
            
                                //ACCIONES SOBRE FOTO, CAMBIO DE NOMBRE Y HUBICACION
                                $origen = $_FILES["foto"]["tmp_name"];
                                $nombreOriginal = $_FILES["foto"]["name"];
                                $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                                $destinoFoto = "./img/".$_POST['patente'].".".$ext;
                                if(file_exists($destinoFoto)){
                                    copy($destinoFoto,"./backUpFotos/".$vehiculo->patente."_".date("Ymd").".".$ext);
                                }
                                move_uploaded_file($origen, $destinoFoto);

                                // MODIFICAR DATOS
                                $vehiculo->marca = $_POST['marca'];
                                $vehiculo->modelo = $_POST['modelo'];
                                $vehiculo->precio = $_POST['precio'];
                                
                                Vehiculo::guardarListaDeVehiculos( $vehiculos );
                                break;
                            }
                        }
                        // GUARDAR ARCHIVO
                    }
                    else{
                        echo "No se configuraron todas las variables.";
                    }
                }
                else{
                    echo "Ingrese una patente valida.";
                }
            }
            else{
                echo "ERROR: Se debe llamar con metodo POST.";
            } 
        }

        public static function guardarListaDeVehiculos($listaDeVehiculos){
            $rutaArchivo = './archivos/vehiculos.txt';
            $archivo = fopen($rutaArchivo, 'w');
            foreach($listaDeVehiculos as $item){
                fwrite($archivo, $item->toCSV());
            }
            fclose($archivo);
            echo 'Vehiculo modificado';
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // VEHICULOS
        ////////////////////////////////////////////////////////////////////////////////////////////////////////

        public static function vehiculos(){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $vehiculos = Vehiculo::leerArchivoDeVehiculos();
                foreach($vehiculos as $vehiculo){
                    echo $vehiculo->toString();
                }
            }
            else{
                echo "ERROR: Se debe llamar con metodo GET.";
            }
        }
    
    }

?>