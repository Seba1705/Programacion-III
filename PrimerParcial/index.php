<?php
     use \Psr\Http\Message\ServerRequestInterface as Request;
     use \Psr\Http\Message\ResponseInterface as Response;

     require_once './vendor/autoload.php';
     require_once './clases/validar.php';
     require_once './clases/archivo.php';
     // Faltan entidades

     $config['displayErrorDetails'] = true;
     $config['addContentLengthHeader'] = false;
     
     $app = new \Slim\App(["settings" => $config]);
     
     $app->group('/examen', function(){
        // GET
        $this->get('/', function($request, $response, $args){

        });

        $this->get('/mostrar', function($request, $response, $args){

        });

        $this->post('/alta', function($request, $response, $args){
            
        });

        $this->post('/baja', function($request, $response, $args){

        });

        $this->post('/modificar', function($request, $response, $args){

        });
        
    });

    $app->run();
?>