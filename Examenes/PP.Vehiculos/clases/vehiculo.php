<?php
    class Vehiculo{

        public $marca;
        public $patente;
        public $kms;
        public $foto;

        function __construct($marca, $patente, $kms){
            $this->marca = $marca;
            $this->patente = $patente;
            $this->kms = $kms;
        }

        public function toJson(){
            return json_encode($this);
        }

        public function agregarFoto($foto){
            $this->foto = $foto;
        }

        /*1- (2 pt.) caso: cargarVehiculo (post): Se deben guardar los siguientes datos: marca, patente y kms. Los datos se
        guardan en el archivo de texto vehiculos.xxx, tomando la patente como identificador(la patente no puede estar
        repetida).*/
        public static function cargarVehiculo(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //VERIFICAMOS QUE ESTEN TODAS LAS VARIABLES
                if( isset($_POST['marca']) && !empty($_POST['marca']) &&
                    isset($_POST['patente']) && !empty($_POST['patente']) && 
                    isset($_POST['kms']) && !empty($_POST['kms'])){ 
                    if(Vehiculo::existePatente($_POST['patente']))  {
                        echo 'Ya existe vehiculo con esa patente';
                    }
                    else{
                        $vehiculo = new Vehiculo($_POST['marca'], $_POST['patente'], $_POST['kms']);
                        Archivo::guardarUno('./archivos/vehiculos.txt', $vehiculo);
                        echo "Vehiculo cargado.";
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

        public static function retornarVehiculos(){
            $datos = Archivo::leerArchivo('./archivos/vehiculos.txt');
            $vehiculos = array();
            foreach ($datos as $key => $value) {
                $item = new Vehiculo($value->marca, $value->patente, $value->kms);
                $item->agregarFoto($value->foto);
                array_push($vehiculos, $item);
            }
            return $vehiculos;
        }

        public static function existePatente($patente){
            $listaDeVehiculos = Vehiculo::retornarVehiculos();
            foreach($listaDeVehiculos as $vehiculo){
                if(strcasecmp(($vehiculo->patente), $patente) == 0){
                    return true;
                }
            }
            return false;
        }

        /*2- (2pt.) caso: consultarVehiculo (get): Se ingresa marca o patente, si coincide con algún registro del archivo se
        retorna las ocurrencias, si no coincide se debe retornar “No existe xxx” (xxx es lo que se buscó) La búsqueda
        tiene que ser case insensitive.*/

        public static function consultarVehiculo(){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                //VERIFICAMOS QUE ESTEN TODAS LAS VARIABLES
                if( (isset($_GET['marca']) && !empty($_GET['marca'])) || (isset($_GET['patente']) && !empty($_GET['patente']))) { 
                    if(isset($_GET['marca'])){
                        $marca = $_GET['marca'];
                        $consultados = array_filter(Vehiculo::retornarVehiculos(), function( $objeto ) use ( $marca ){
                            return strcasecmp( $objeto->marca, $marca ) == 0;
                        });
                        echo PHP_EOL . 'Filtrados por Marca: ' . $marca . PHP_EOL . PHP_EOL;
                        array_map('Vehiculo::mostrarVehiculo', $consultados);
                    }
                    if(isset($_GET['patente'])){
                        $patente = $_GET['patente'];
                        $consultados = array_filter(Vehiculo::retornarVehiculos(), function( $objeto ) use ( $patente ){
                            return strcasecmp( $objeto->patente, $patente ) == 0;
                        });
                        echo PHP_EOL . 'Filtrados por patente: ' . $patente . PHP_EOL . PHP_EOL;
                        array_map('Vehiculo::mostrarVehiculo', $consultados);
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

        public static function mostrarVehiculo($vehiculo){
            echo $vehiculo->toJson() . PHP_EOL;
        }

        /*7- (2 pts.) caso: modificarVehiculo(post): Debe poder modificar todos los datos del vehículo menos la patente y
        se debe cargar una imagen, si ya existía una guardar la foto antigua en la carpeta /backUpFotos , el nombre será
        patente y la fecha.*/
        public static function modificarVehiculo(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if( isset($_POST['patente']) && !empty($_POST['patente']) && Vehiculo::existePatente($_POST['patente'])){  
                    if( isset($_POST['marca']) && !empty($_POST['marca']) && isset($_FILES['foto'])){
                        $vehiculos = Vehiculo::retornarVehiculos();
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
                              
                                $vehiculo->agregarFoto($destinoFoto);
                                $vehiculo->marca = $_POST['marca'];
                                $vehiculo->modelo = $_POST['kms'];
                                
                                break;
                            }
                        }
                        Archivo::guardarTodos('archivos/vehiculos.txt', $vehiculos);
                        echo 'Vehiculo modificado';
    
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

        /*8- (2 pts.) caso: vehiculos(get): Mostrar una tabla con todos los datos de los vehículos, incluida la foto*/
    
        public static function vehiculos(){
            $datos = "<!DOCTYPE html>
                        <html lang='en'>
                        <head>
                            <title>Vehiculos</title>
                        </head>
                        <style>
                            table{
                                width: 100%;
                                border-collapse: collapse; /*sin bordes entre los elementos internos*/
                            }
                        
                            thead{
                                font-size: 18px;
                                font-weight: bold;
                            }
                            th, td{
                                text-align: center;
                                padding: 10px;
                            }
                        
                            tr:nth-child(even){
                                background-color: #f2f2f2;
                            }
                        
                            th{
                                background:#252932;
                                color: #fff;
                                font: bold;
                            }
                        
                            img{
                                height: 80px;
                                width: 80px;
                                border-radius: 100%;
                            }
                        </style>
                        <body>
                            <table>
                                <thead>
                                    <tr>
                                        <td>Marca</td>
                                        <td>Modelo</td>
                                        <td>Patente</td>
                                        <td>Precio</td>
                                        <td>Foto</td>
                                    </tr>
                                </thead>
                                <tbody>";
            $vehiculos = Vehiculo::retornarVehiculos();
            foreach($vehiculos as $item){
            
                $datos .= "<tr>
                                <td>" .$item->marca. "</td>
                                <td>" .$item->kms. "</td>
                                <td>" .$item->patente. "</td>
                                <td><img src='" .$item->foto. "'/></td>
                        </tr>";
            }   
            $datos .= " </tbody>
                    </table>
                </body>
                </html>";
            echo $datos;
        }

    }
?> 