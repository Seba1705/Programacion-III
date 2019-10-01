<?php
    class Entidad{
        public $id;
        public $nombre;

        public function __construct(){

        }

        public function toJKson(){
            return json_encode($this);
        }

        public static function alta($objeto){
            Archivo::guardarUno('', $objeto);
        }

        public static function baja(){
            
        }

        public static function modicacion($datos, $img){
            // $lista = ; Lista de entidades
            foreach( $lista as $$item ){
                if(strcasecmp($item->id, $datos['']) == 0){
                    //ACCIONES SOBRE FOTO, CAMBIO DE NOMBRE Y HUBICACION
                    $origen = $img->file;
                    $nombreOriginal = $img->getClientFilename();
                    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                    $destinoFoto = "./img/".$datos[''].".".$ext;
                    if(file_exists($destinoFoto)){
                        copy($destinoFoto,"./backUpFotos/".$item->id."_".date("Ymd").".".$ext);
                    }
                    move_uploaded_file($origen, $destinoFoto);
                    // MODIFICAR DATOS
                    // $item->algo = $datos['algo'];
                    break;
                }
            }
            Archivo::guardarTodos('', $lista);
        }

        public static function mostrar($objeto){
            return $objeto->toJKson();
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

    }
?>
