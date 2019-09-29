<?php
    class Vehiculo{
       
        public $marca;
        public $modelo;
        public $patente;
        public $precio;
        public $foto;

        function __construct($marca, $modelo, $patente, $precio){
            $this->marca = $marca;
            $this->modelo = $modelo;
            $this->patente = $patente;
            $this->precio = $precio;
        }
        
        function toJSON(){
            return json_encode($this);            
        }

        public function setFoto($foto){
            $this->foto = $foto;
        }

        public static function agregar($objeto){
            Archivo::guardarUno('./archivos/vehiculos.txt', $objeto);
        }

        public static function retornarVehiculos(){
            $datos = Archivo::leerTodos('./archivos/vehiculos.txt');
            $vehiculos = array();
            foreach ($datos as $key => $value) {
                $vehiculoAux = new Vehiculo($value->marca, $value->modelo, $value->patente, $value->precio);
                $vehiculoAux->setFoto($value->foto);
                array_push($vehiculos, $vehiculoAux);
            }
            return $vehiculos;
        }

        public static function modificar($datos, $img){
            // var_dump($datos);
            // var_dump($img);
            $vehiculos = Vehiculo::retornarVehiculos();
            foreach( $vehiculos as $vehiculo ){
                if(strcasecmp($vehiculo->patente, $datos['patente']) == 0){

                    //ACCIONES SOBRE FOTO, CAMBIO DE NOMBRE Y HUBICACION
                    $origen = $img->file;
                    $nombreOriginal = $img->getClientFilename();
                    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                    $destinoFoto = "./img/".$datos['patente'].".".$ext;
                    if(file_exists($destinoFoto)){
                        copy($destinoFoto,"./backUpFotos/".$vehiculo->patente."_".date("Ymd").".".$ext);
                    }
                    move_uploaded_file($origen, $destinoFoto);
                    // MODIFICAR DATOS
                    $vehiculo->setFoto($destinoFoto);
                    $vehiculo->marca = $datos['marca'];
                    $vehiculo->modelo = $datos['modelo'];
                    $vehiculo->precio = $datos['precio'];

                    break;
                }
            }
            Archivo::guardarTodos('./archivos/vehiculos.txt', $vehiculos);
        }

        public static function eliminar(){

        }

        

        


    }
?>