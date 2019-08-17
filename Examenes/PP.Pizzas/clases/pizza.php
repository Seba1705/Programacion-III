<?php
    class Pizza{

        public $id;
        public $precio;
        public $tipo;
        public $cantidad;
        public $sabor;
        public $imagen;
        

        public function __construct( $id, $precio, $tipo, $sabor, $cantidad, $imagen ){
            $this->id = $id;
            $this->precio = $precio;
            $this->tipo = $tipo;
            $this->sabor = $sabor;
            $this->cantidad = $cantidad;
            $this->imagen = $imagen;
        }

        public function toCsv(){
            return $this->id. ';' .$this->precio. ';' .$this->tipo. ';' .$this->sabor. ';' .$this->cantidad. ';' .$this->imagen.PHP_EOL;
        }

        ///////////////////////////////////////////////////////////////////////////////
        // PIZZA CARGA
        ///////////////////////////////////////////////////////////////////////////////

        public static function pizzaCarga(){
            if(Archivo::existePeticionPOST()){
                if( isset($_POST['precio']) && !empty($_POST['precio']) &&
                    isset($_POST['tipo']) && !empty($_POST['tipo']) &&
                    isset($_POST['sabor']) && !empty($_POST['sabor']) &&
                    isset($_POST['cantidad']) && !empty($_POST['cantidad']) &&
                    isset($_FILES['imagen']) ){
                    if( Archivo::validarSabor($_POST['sabor']) && Archivo::validarTipo($_POST['tipo']) ){
                        if( Archivo::validarConbinacion($_POST['tipo'], $_POST['sabor']) ){
                            $id = Archivo::generarID();

                            $origen = $_FILES["imagen"]["tmp_name"];
                            $nombreOriginal = $_FILES["imagen"]["name"];
                            $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                            $destinoFoto = "./img/".$id.".".$ext;
                            move_uploaded_file($origen, $destinoFoto);

                            $pizza = new Pizza( $id, 
                                                $_POST['precio'], 
                                                $_POST['tipo'], 
                                                $_POST['sabor'],
                                                $_POST['cantidad'],
                                                $destinoFoto ); 
                            Archivo::guardarUno( './archivos/pizzas.txt', $pizza );
                            echo 'Pizza cargada';
                        }else{
                            echo 'La combinacion ya existe!';
                        }
                    }else{
                        echo 'Ingrese Tipo y Sabor validos';
                    }
                }else{
                    echo 'No se configuraron todas las variables';
                }
            }else{
                echo 'Se debe llamar con el metodo POST';
            }
        }

        ///////////////////////////////////////////////////////////////////////////////
        // PIZZA CONSULTAR
        ///////////////////////////////////////////////////////////////////////////////

        public static function pizzaConsultar(){
            if( Archivo::existePeticionGET() ){
                if( isset($_GET['sabor']) && !empty($_GET['sabor']) && Archivo::validarSabor($_GET['sabor']) &&
                    isset($_GET['tipo']) && !empty($_GET['tipo']) && Archivo::validarTipo($_GET['tipo']) ){
                    if( Archivo::validarConbinacion($_GET['tipo'], $_GET['sabor']) ){
                        //
                    }else{
                        echo 'Si hay';
                    }   
                }else{
                    echo 'Debe ingresar sabor y tipo validos';
                }
            }else{  
                echo 'De llamarse con el metodo GET';
            }
        }
    }
?>