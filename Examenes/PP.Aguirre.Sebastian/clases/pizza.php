<?php
    class Pizza{
        public $id;
        public $precio;
        public $tipo;
        public $cantidad;
        public $sabor;
        public $imagen;
        public $foto;

        public function __construct($id, $precio, $tipo, $cantidad, $sabor, $imagen, $foto){
            $this->id = $id;
            $this->precio = $precio;
            $this->tipo = $tipo;
            $this->cantidad = $cantidad;
            $this->sabor = $sabor;
            $this->foto = $foto;
            $this->imagen = $imagen;
        }

        public function toJSON(){
            return json_encode($this);
        }

        public static function alta($objeto){
            Archivo::guardarUno('./archivos/pizzas.txt', $objeto);
        }

        public static function validarSabor($sabor){
            return (strcasecmp($sabor, 'muzza') == 0 || strcasecmp($sabor, 'jamon') == 0 || strcasecmp($sabor, 'especial') == 0) ? true : false;
        }

        public static function validarTipo($tipo){
            return (strcasecmp($tipo, 'molde') == 0 || strcasecmp($tipo, 'piedra') == 0) ? true : false;
        }

        public static function validarCombinacion($tipo, $sabor){
            $pizzas = Pizza::retornarPizzas();
            foreach($pizzas as $item){
                if(strcasecmp($item->sabor, $sabor) == 0 && strcasecmp($item->tipo, $tipo) == 0)
                    return true;
            }
            return false;
        }

        public static function retornarPizzas(){
            $datos = Archivo::leerArchivo('./archivos/pizzas.txt');
            $pizzas = array();
            foreach ($datos as $key => $value) {
                $pizza = new Pizza($value->id, $value->precio, $value->tipo, $value->cantidad, $value->sabor, $value->imagen, $value->foto);
                array_push($pizzas, $pizza);
            }
            return $pizzas;
        }

        public static function mostrarDisponible($tipo, $sabor){
            $datos = Pizza::retornarPizzas();
            $saborCount = 0; $tipoCount = 0;
            foreach($datos as $item){
                if(strcasecmp($item->sabor, $sabor) == 0)
                    $saborCount += $item->cantidad;
                else if(strcasecmp($item->sabor, $sabor) == 0)
                    $tipoCount += $item->cantidad;
            }
            echo 'Hay ' .$saborCount. ' del sabor: ' .$sabor .PHP_EOL;
            echo 'Hay ' .$tipoCount. ' del tipo: ' .$tipo .PHP_EOL;
        }


        public static function mostrarLista($lista){
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
                                    background:#252932;
                                    color: #fff;
                                }

                                th, td{
                                    text-align: center;
                                    padding: 10px;
                                }
                            
                                tr:nth-child(even){
                                    background-color: #f2f2f2;
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
        
            foreach($lista as $item){
                $datos .= "<tr>
                            <td>" .$item->marca. "</td>
                            <td>" .$item->modelo. "</td>
                            <td>" .$item->patente. "</td>
                            <td>" .$item->precio. "</td>
                            <td><img src='." .$item->foto. "'/></td>
                        </tr>";
            }   

            $datos .= "             </tbody>
                                </table>
                            </body>
                        </html>";

            echo $datos;
        }

        public static function generarNuevoId(){
            $rutaArchivo = './archivos/id.txt';      
            $archivo = fopen($rutaArchivo, 'r');
            $ultimoId = fgets($archivo);
            fclose($archivo);
            $nuevoId = number_format($ultimoId) + 1;
            $nuevoArchivo = fopen($rutaArchivo, 'w');
            fwrite($nuevoArchivo, $nuevoId);
            return $nuevoId;
        }

        public static function existePizza($id){
            $pizzas = Pizza::retornarPizzas();
            foreach($pizzas as $item){
                if(strcasecmp($item->id, $id) == 0)
                    return true;
            }
            return false; 
        }

        public static function modificar($datos, $img){
            $lista = Pizza::retornarPizzas();
            foreach( $lista as $item ){
                if(strcasecmp($item->id, $datos['id']) == 0){
                    // Imagen
                    $origen = $img['imagen']->file;
                    $nombreImagen = $img['imagen']->getClientFilename();
                    $ext = pathinfo($nombreImagen, PATHINFO_EXTENSION);
                    $destinoImagen = $item->imagen;
                        copy($destinoImagen,"./backUp/".$item->imagen."_".date("Ymd").".".$ext);
                    }
                    move_uploaded_file($origen, $destinoImagen);
                    //Foto
                    $origen2 = $img['foto']->file;
                    $nombreFoto = $img['foto']->getClientFilename();
                    $ext2 = pathinfo($nombreFoto, PATHINFO_EXTENSION);
                    $destinoFoto = $item->foto;
                    if(file_exists($destinoFoto)){
                        copy($destinoFoto,"./backUp/".$item->foto."_".date("Ymd").".".$ext2);
                    }
                    move_uploaded_file($origen2, $destinoFoto);
                    // MODIFICAR DATOS
                    $item->tipo = $datos['tipo'];
                    $item->sabor = $datos['sabor'];
                    $item->cantidad = $datos['cantidad'];
                    echo '{"mensaje":"Ingrese un sabor valido"}';
                    break;
                }
            }
            
            //Archivo::guardarTodos('./archivos/pizzas.php', $lista);
        }
        
    }
?>
