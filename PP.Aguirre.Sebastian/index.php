<?php
     use \Psr\Http\Message\ServerRequestInterface as Request;
     use \Psr\Http\Message\ResponseInterface as Response;

     require_once './vendor/autoload.php';
     require_once './clases/pizza.php';
     require_once './clases/ventas.php';
     require_once './clases/archivo.php';
     // Faltan entidades

     $config['displayErrorDetails'] = true;
     $config['addContentLengthHeader'] = false;
     
     $app = new \Slim\App(["settings" => $config]);
     
     $app->group('/examen', function(){
        // GET
        $this->get('/pizzas', function($request, $response, $args){
            $datos = $request->getQueryParams();
            if(isset($datos['tipo'], $datos['sabor']) && !empty($datos['tipo']) && !empty($datos['sabor'])){
                if(Pizza::validarTipo($datos['tipo'])){
                    if(Pizza::validarSabor($datos['sabor'])){
                        Pizza::mostrarDisponible($datos['tipo'], $datos['sabor']);
                    }else
                        echo '{"mensaje":"Ingrese un sabor valido"}'; 
                }else
                    echo '{"mensaje":"Ingrese un tipo valido"}';
            }else
                echo '{"mensaje":"Ingrese tipo y sabor"}';
        });

        // POST
        $this->post('/pizzas', function($request, $response, $args){
            $datos = $request->getParsedBody();
            //var_dump($datos);
            $img = $request->getUploadedFiles();
            
            if(isset($datos['precio'], $datos['tipo'], $datos['sabor'], $datos['cantidad'])){
                if(!empty($datos['precio']) && !empty($datos['tipo']) && !empty($datos['sabor']) && !empty($datos['cantidad'])){
                    if($img['imagen'] != null && $img['foto'] != null){
                        if(Pizza::validarTipo($datos['tipo'])){
                            if(Pizza::validarSabor($datos['sabor'])){
                                if(!Pizza::validarCombinacion($datos['tipo'], $datos['sabor'])){
                                    $id = Pizza::generarNuevoId();
                                    // Imagen
                                    $origen = $img['imagen']->file;
                                    $nombreImagen = $img['imagen']->getClientFilename();
                                    $ext = pathinfo($nombreImagen, PATHINFO_EXTENSION);
                                    $destinoImagen = "./images/pizzas/".$id."-imagen.".$ext;
                                    move_uploaded_file($origen, $destinoImagen);
                                    //Foto
                                    $origen2 = $img['foto']->file;
                                    $nombreFoto = $img['foto']->getClientFilename();
                                    $ext2 = pathinfo($nombreFoto, PATHINFO_EXTENSION);
                                    $destinoFoto = "./images/pizzas/".$id."-foto.".$ext2;
                                    move_uploaded_file($origen2, $destinoFoto);
            
                                    $pizza = new Pizza($id, $datos['precio'], $datos['tipo'], $datos['cantidad'], $datos['sabor'], $destinoImagen, $destinoFoto);
                                    Pizza::alta($pizza);
                                    echo '{"mensaje":"Se guardo correctamente"}';
                                }else
                                    echo '{"mensaje":"Ya existe la combinacion"}';
                            }else
                                echo '{"mensaje":"Ingrese un sabor valido"}';
                        }else
                            echo '{"mensaje":"Ingrese un tipo valido"}';
                    }else
                        echo '{"mensaje":"Debe ingresar 2 imagenes"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacíos"}';
            }else
                echo '{"mensaje":"Complete todos los campos requeridos"}';
        });

        $this->post('/pizzasMod', function($request, $response, $args){
            $datos = $request->getParsedBody();
            $img = $request->getUploadedFiles();

            if(isset($datos['precio'], $datos['tipo'], $datos['sabor'], $datos['cantidad'], $datos['id'])){
                if( !empty($datos['precio']) && !empty($datos['tipo']) && 
                    !empty($datos['sabor']) && !empty($datos['cantidad']) && !empty($datos['id'])){
                    if($img['imagen'] != null && $img['foto'] != null){
                        if(Pizza::validarTipo($datos['tipo'])){
                            if(Pizza::validarSabor($datos['sabor'])){
                                
                                    Pizza::modificar($datos, $img);
                                
                            }else
                                echo '{"mensaje":"Ingrese un sabor valido"}';
                        }else
                            echo '{"mensaje":"Ingrese un tipo valido"}';
                    }else
                        echo '{"mensaje":"Debe ingresar 2 imagenes"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacíos"}';
            }else
                echo '{"mensaje":"Complete todos los campos requeridos"}';
        });
        
        $this->post('/ventas', function($request, $response, $args){
            $datos = $request->getParsedBody();
            //var_dump($datos);
            if(isset($datos['email'], $datos['tipo'], $datos['sabor'], $datos['cantidad'])){
                if(!empty($datos['email']) && !empty($datos['tipo']) && !empty($datos['sabor']) && !empty($datos['cantidad'])){
                    if(Pizza::validarTipo($datos['tipo'])){
                        if(Pizza::validarSabor($datos['sabor'])){
                            if(Pizza::validarCombinacion($datos['tipo'], $datos['sabor'])){
                                $pizzas = Pizza::retornarPizzas();
                                foreach($pizzas as $item){
                                    if(strcasecmp($item->sabor, $datos['sabor']) == 0 && strcasecmp($item->tipo, $datos['tipo']) == 0){
                                        if($item->cantidad > 0 && $item->cantidad > $datos['cantidad']){
                                            $item->cantidad--;
                                            $id = Venta::generarNuevoId();
                                            $precio = $item->precio * $datos['cantidad'];
                                            $venta = new Venta($id, $datos['email'], $datos['tipo'], $datos['cantidad'], $datos['sabor'], $precio);
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

    });

    $app->run();
?>