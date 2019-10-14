<?php
    class Log{
        public $ruta;
        public $ip;
        public $hora;

        public function __construct($ruta, $ip, $hora){
            $this->ruta = $ruta;
            $this->ip = $ip;
            $this->hora = $hora;
        }

        public function toJSON(){
            return json_encode($this);
        }
        
        public static function alta($objeto){
            Archivo::guardarUno('./archivos/info.log', $objeto);
        }
    }
?>