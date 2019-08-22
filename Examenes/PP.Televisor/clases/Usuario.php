<?php
    //Usuario.php. Crear, en ./clases, la clase Usuario con atributos privados (email y clave), constructor(que inicialice los atributos), un método de instancia ToJSON(), que retornará los datos de la instancia (en una cadena con formato JSON). Agregar:Método de instancia GuardarEnArchivo(), que agregará al usuario en ./archivos/usuarios.json.Retornará un JSON que contendrá: éxito(bool) y mensaje(string)indicando lo acontecido. Método de clase TraerTodos(), que retornará un array de objetos de tipo Usuario.Método de clase VerificarExistencia($usuario), retornará true, si el usuarioestá registrado(invocar a TraerTodos), caso contrario retornará false
    class Usuario{
        private $email;
        private $clave;

        public function __construct( $email, $clave ){
            $this->email = $email;
            $this->clave = $clave;
        }

        public function toJson(){
            return json_encode($this);
        }

        public function guardarEnArchivo(){

        }
    }
?>