<?php
    class Validar{

        public static function validarPatente($patente){
            $vehiculos = Vehiculo::retornarVehiculos();
            foreach($vehiculos as $item){
                if(strcasecmp($item->patente, $patente) == 0)
                    return true;
            }
            return false;
        }

        public static function validarId($id){
            $servicios = Servicio::retornarVehiculos();
            foreach($servicios as $item){
                if(strcasecmp($item->id, $id) == 0)
                    return true;
            }
            return false;
        }

        public static function validarTipo($tipo){
            if($tipo == 10000 || $tipo == 20000 || $tipo == 50000)   
                return true;
            return false;
        }

        public static function retornarVehiculo($patente){
            $vehiculos = Vehiculo::retornarVehiculos();
            foreach($vehiculos as $item){
                if(strcasecmp($item->patente, $patente) == 0)
                    return $item;
            }
            return false;
        }

        public static function validarFecha($fecha){
            if($fecha < 0 || $fecha > 31)   
                return false;
            return true;
        }

    }
?>