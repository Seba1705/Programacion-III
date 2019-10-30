<?php

    use Slim\App;
    use Slim\Http\Request;
    use Slim\Http\Response;

    return function (App $app) {
        $container = $app->getContainer();
    
        $app->group('/Alumno', function () {   
             
            $this->get('/', function ($request, $response, $args) {
                $datos = $request->getParams();
                var_dump($datos);
            });

            $this->post('/', function ($request, $response, $args) {
                $datos = $request->getParsedBody();
                var_dump($datos);
            });

        });
    }
?>