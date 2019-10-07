<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once './vendor/autoload.php';
    require_once './clases/pizza.php';
    require_once './clases/archivo.php';

    // Faltan entidades
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);

    $app->group('/examen', function(){
        /*1- (2 pts.) Ruta: pizzas (POST): se ingresa precio, Tipo (“molde” o “piedra”), cantidad( de unidades),sabor(muzza;jamón; especial), precio y dos imágenes (guardarlas en la carpeta images/pizzas y cambiarles el nombre para que sea único). Se guardan los datos en en el archivo de texto Pizza.xxx, tomando un id autoincremental como identificador, la combinación tipo - sabor debe ser única.*/
        $this->post('/pizzas', function($request, $response, $args){
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
        $this->get('/pizzas', function($request, $response, $args){
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

        
    });

    $app->run();
?>