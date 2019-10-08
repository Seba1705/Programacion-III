<?php
     class Turno{
        public $fecha;
        public $patente;
        public $marca;
        public $precio;
        public $tipo;
        
        function __construct($marca, $tipo, $patente, $precio, $fecha){
            $this->marca = $marca;
            $this->tipo = $tipo;
            $this->patente = $patente;
            $this->precio = $precio;
            $this->fecha = $fecha;
        }

        public static function filtrarPorTipo($tipo){
            $lista = Vehiculo::retornarVehiculos();
            foreach($lista as $vehiculo){
                if(strcasecmp($vehiculo->tipo, $tipo) == 0)
                    echo $vehiculo->toJSON();
            }
        }
    }
?>