<?php
     use \Psr\Http\Message\ServerRequestInterface as Request;
     use \Psr\Http\Message\ResponseInterface as Response;

     require_once './vendor/autoload.php';
     require_once './clases/entidad.php';
     require_once './clases/archivo.php';
     // Faltan entidades

     $config['displayErrorDetails'] = true;
     $config['addContentLengthHeader'] = false;
     
     $app = new \Slim\App(["settings" => $config]);
     
     $app->group('/examen', function(){
        // GET
        $this->get('/', function($request, $response, $args){
            // return $response->withJson(Entidad::mostrar());
        });

        $this->get('/mostrar', function($request, $response, $args){
            // Metodo mostar de la entidad
        });

        // POST
        $this->post('/alta', function($request, $response, $args){
            $datos = $request->getParsedBody();
            //var_dump($datos);
            $img = $request->getUploadedFiles();
            //var_dump($img);
            if(isset($datos[''])){
                if(!empty($datos[''])){
                    if($img != null){
                        // $objeto = new ...
                        // Entidad::alta($objeto);
                    }else
                        echo '{"mensaje":"Debe ingresar una imagen"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacíos"}';
            }else
                echo '{"mensaje":"Complete todos los campos requeridos"}';
        });

        $this->post('/modificar', function($request, $response, $args){
            $datos = $request->getParsedBody();
            //var_dump($datos);
            $img = $request->getUploadedFiles();
            //var_dump($img);
            if(isset($datos[''])){
                if(!empty($datos[''])){
                    if($img != null){
                        // Validar valor unico
                        // Modificar entidad
                    }else
                        echo '{"mensaje":"Debe ingresar una imagen"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacíos"}';
            }else
                echo '{"mensaje":"Complete todos los campos requeridos"}';
        });
        
    });

    $app->run();
?>