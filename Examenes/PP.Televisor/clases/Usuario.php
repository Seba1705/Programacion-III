<?php

    class Usuario{
        private $email;
        private $clave;

        public function __construct( $email, $clave ){
            $this->email = $email;
            $this->clave = $clave;
        }

        // Retornará los datos de la instancia (en una cadena con formato JSON).
        public function toJSON(){
            return '{"email":"'.$this->email.'","clave":"'.$this->clave.'"}';
        }

        // Agregará al usuario en ./archivos/usuarios.json. Retornará un JSON que contendrá: éxito(bool) y mensaje(string)indicando lo acontecido.
        public function guardarEnArchivo(){
            $file = './archivos/usuarios.json';
            file_put_contents($file, $this->toJSON());
        }

        // Retornará un array de objetos de tipo Usuario.
        public static function traerTodos(){

        }

        // Retornará true, si el usuarioestá registrado(invocar a TraerTodos), caso contrario retornará false
        public static function verificarExistencia( $usuario ){

        }
    }

?>