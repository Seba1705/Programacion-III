<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once './vendor/autoload.php';
    require_once './clases/pizza.php';
    require_once './clases/archivo.php';
    require_once './clases/venta.php';
    require_once './clases/log.php';

    /*3- (1 pt.) A partir de este punto, se debe guardar en un archivo info.log la información de cada petición recibidapor la API (ruta, metodo, hora).*/
    function guardarLog($metodo, $ruta){
        $hora = date("h:i:s");
        $log = new Log($ruta, $metodo, $hora);
        Log::Alta($log);
    }

    // Faltan entidades
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);

    /*1- (2 pts.) Ruta: pizzas (POST): se ingresa precio, Tipo (“molde” o “piedra”), cantidad( de unidades),sabor(muzza;jamón; especial), precio y dos imágenes (guardarlas en la carpeta images/pizzas y cambiarles el nombre para que sea único). Se guardan los datos en en el archivo de texto Pizza.xxx, tomando un id autoincremental como identificador, la combinación tipo - sabor debe ser única.*/
    $app->post('/pizzas', function(Request $request, Response $response, array $args){
        guardarLog($request->getMethod(), $request->getUri()->getPath());

        $arrDatos = $request->getParsedBody();
        //var_dump($arrDatos);
        $arrImg = $request->getUploadedFiles();
        //var_dump($arrImg);
        if(isset($arrDatos['precio'], $arrDatos['tipo'], $arrDatos['cantidad'], $arrDatos['sabor'])){
            if(!empty($arrDatos['precio']) && !empty($arrDatos['tipo']) && !empty($arrDatos['sabor']) && !empty($arrDatos['cantidad'])){
                if($arrImg['imagen'] != null && $arrImg['foto'] != null){
                    $tipo = $arrDatos['tipo']; $sabor = $arrDatos['sabor']; 
                    $precio = $arrDatos['precio']; $cantidad = $arrDatos['cantidad'];
                    if(Pizza::validarTipo($tipo)){
                        if(Pizza::validarSabor($sabor)){
                            if(!Pizza::validarCombinacion($tipo, $sabor)){
                                $id = Pizza::generarNuevoId();
                                // Imagen
                                $origen = $arrImg['imagen']->file;
                                $nombreImagen = $arrImg['imagen']->getClientFilename();
                                $ext = pathinfo($nombreImagen, PATHINFO_EXTENSION);
                                $destinoImagen = "./images/pizzas/".$id."-imagen.".$ext;
                                move_uploaded_file($origen, $destinoImagen);
                                //Foto
                                $origen2 = $arrImg['foto']->file;
                                $nombreFoto = $arrImg['foto']->getClientFilename();
                                $ext2 = pathinfo($nombreFoto, PATHINFO_EXTENSION);
                                $destinoFoto = "./images/pizzas/".$id."-foto.".$ext2;
                                move_uploaded_file($origen2, $destinoFoto);

                                $pizza = new Pizza($id, $precio, $tipo, $cantidad, $sabor, $destinoImagen, $destinoFoto);
                                Pizza::alta($pizza);
                                echo '{"mensaje":"Se guardo correctamente"}';
                            }else   
                                echo '{"mensaje":"La combinación ya existe"}';  
                        }else
                            echo '{"mensaje":"Ingrese un sabor válido"}';   
                    }else
                        echo '{"mensaje":"Ingrese un tipo válido"}';
                }else
                    echo '{"mensaje":"Debe ingresar 2 imagenes"}';
            }else   
                echo '{"mensaje":"No puede haber campos vacios"}';
        }else
            echo '{"mensaje":"Faltan datos"}';
    });

    /*2- (2 pts.) ​Ruta: pizzas​: (GET): Recibe Sabor y Tipo, si coincide con algún registro del archivo ​Pizza.xxx, ​retornar la
    cantidad de producto disponible, de lo contrario informar si no existe el tipo o el sabor. La consulta debe ser ​case insensitive​*/
    $app->get('/pizzas', function(Request $request, Response $response, array $args){
        guardarLog($request->getMethod(), $request->getUri()->getPath());

        $arrDatos = $request->getQueryParams();
        if(isset($arrDatos['tipo'], $arrDatos['sabor']) && !empty($arrDatos['tipo']) && !empty($arrDatos['sabor'])){
            if(Pizza::validarTipo($arrDatos['tipo'])){
                if(Pizza::validarSabor($arrDatos['sabor'])){
                    Pizza::mostrarDisponible($arrDatos['tipo'], $arrDatos['sabor']);
                }else
                    echo '{"mensaje":"Ingrese un sabor valido"}'; 
            }else
                echo '{"mensaje":"Ingrese un tipo valido"}';
        }else
            echo '{"mensaje":"Ingrese tipo y sabor"}';
    });

    /*4-(2 pts.) ​Ruta: ventas ​(POST). Recibe el email del usuario y el sabor,tipo y cantidad ,si el item existe en ​Pizza.xxx, y hay stock​​ guardar en el archivo de texto ​Venta.xxx​ todos los datos , más el precio de la venta, un id autoincremental  y descontar la cantidad vendida. Si no cumple las condiciones para realizar la venta, informar elmotivo.*/
    $app->post('/ventas', function(Request $request, Response $response, array $args){
        guardarLog($request->getMethod(), $request->getUri()->getPath());

        $datos = $request->getParsedBody();
        //var_dump($datos);
        if(isset($datos['email'], $datos['tipo'], $datos['sabor'], $datos['cantidad'])){
            $email = $datos['email']; $tipo = $datos['tipo']; $sabor = $datos['sabor']; $cantidad = $datos['cantidad'];
            if(!empty($email) && !empty($tipo) && !empty($sabor) && !empty($cantidad)){
                if(Pizza::validarTipo($tipo)){
                    if(Pizza::validarSabor($sabor)){
                        if(Pizza::validarCombinacion($tipo, $sabor)){
                            $pizzas = Pizza::retornarPizzas();
                            foreach($pizzas as $item){
                                if(strcasecmp($item->sabor, $sabor) == 0 && strcasecmp($item->tipo, $tipo) == 0){
                                    if($item->cantidad > 0 && $item->cantidad > $cantidad){
                                        $item->cantidad--;
                                        $id = Venta::generarNuevoId();
                                        $precio = $item->precio * $cantidad;
                                        $venta = new Venta($id, $email, $tipo, $cantidad, $sabor, $precio);
                                        Venta::alta($venta);
                                        echo '{"mensaje":"Se realizo la venta"}';
                                    }else   
                                        echo '{"mensaje":"No hay la cantidad necesaria"}';
                                }   
                            }
                            archivo::guardarTodos('./archivos/pizzas.txt', $pizzas);
                            
                        }else
                            echo '{"mensaje":"No existe la combinacion"}';
                    }else
                        echo '{"mensaje":"Ingrese un sabor valido"}';
                }else
                    echo '{"mensaje":"Ingrese un tipo valido"}';
                
            }else
                echo '{"mensaje":"No puede haber campos vacíos"}';
        }else
            echo '{"mensaje":"Complete todos los campos requeridos"}';
    });

    /*5- (2 pts.) ​Ruta: pizzas ​(PUT). Se reciben los datos a modificar, incluidas las imágenes. En el caso de haberimágenes, se deben mover las imágenes viejas a la carpeta images/backup.*/
    $app->put('/pizzas', function(Request $request, Response $response, array $args){
        // guardarLog($request->getMethod(), $request->getUri()->getPath());

        $datos = $request->getParsedBody();
        $img = $request->getUploadedFiles();
      
        var_dump($img);
        
        // if(isset($datos['precio'], $datos['tipo'], $datos['sabor'], $datos['cantidad'], $datos['id'])){
        //     $email = $datos['email']; $tipo = $datos['tipo']; $sabor = $datos['sabor']; $cantidad = $datos['cantidad']; $id = $datos['id'];
        //     if(!empty($email) && !empty($tipo) && !empty($sabor) && !empty($cantidad) && !empty($id)){
        //         if($img['imagen'] != null && $img['foto'] != null){
        //             if(Pizza::validarTipo($tipo)){
        //                 if(Pizza::validarSabor($sabor)){
        //                     // Pizza::modificar($datos, $img);
        //                     echo 'entre';
        //                 }else
        //                     echo '{"mensaje":"Ingrese un sabor valido"}';
        //             }else
        //                 echo '{"mensaje":"Ingrese un tipo valido"}';
        //         }else
        //             echo '{"mensaje":"Debe ingresar 2 imagenes"}';
        //     }else
        //         echo '{"mensaje":"No puede haber campos vacíos"}';
        // }else
        //     echo '{"mensaje":"Complete todos los campos requeridos"}';
    });

    $app->run();
?>