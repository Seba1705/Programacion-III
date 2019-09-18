<?php
    // Alien.php. Crear, en ./clases, la clase Alien con atributos privados (planeta, email y clave), constructor(que inicialice los atributos) un método de instancia ToJSON(), que retornará los datos de la instancia (en una cadena con formato JSON). Agregar:Método de instancia GuardarEnArchivo(), que agregará al alien en ./archivos/alien.json. Retornará un JSON que contendrá: éxito(bool) y mensaje(string)indicando lo acontecido. Método de clase TraerTodos(), que retornará un array de objetos de tipo Alien. Método de clase VerificarExistencia($alien), que recorrerá el array (invocar a TraerTodos) y retornará un JSON que contendrá: existe(bool) y mensaje(string).Si el alienestá registrado(email y clave), retornará true y el mensaje indicará cuantos aliens están registrados con el mismo planeta del alien recibido por parámetro. Caso contrario,retornará false, y el/los nombres del/los planetas más populares(mayor cantidad de apariciones).
    class Alien{
        private $planeta;
        private $email;
        private $clave;

        public function __construct( $planeta, $email, $clave ){
            $this->planeta = $planeta;
            $this->email = $email;
            $this->clave = $clave;
        }
        
        public function ToJson(){
            return '{"planeta":"'.$this->planeta.'","email":"'.$this->email.'","clave":"'.$this->clave.'"}';
        }

        public function GuardarEnArchivo(){
            if(Archivo::guardar('./archivos/alien.json', $this))
                echo '{"exito":true,"mensaje":"Se guardo en archivo correctamente"}';
            else
                echo '{"exito":false,"mensaje":"No se pudo guardar el archivo"}';
        }

        public static function TraerTodos(){
            $datos = Archivo::leer('./archivos/alien.json');
            $aliens = array();

            foreach ($datos as $key => $value) {
                array_push($aliens, new Alien($value->planeta, $value->email, $value->clave));
            }

           return $aliens;
        }
        
        public static function existeAlienEnArchivo( $email ){
            $aliens = Alien::TraerTodos();
            foreach( $aliens as $alien ){
                if( strcasecmp($alien->email, $email) == 0 )
                    return true;
            }
            return false;
        } 

        public static function mostrarAlien( $alien ){
            echo $alien->ToJson() . PHP_EOL;
        }

    }

?>