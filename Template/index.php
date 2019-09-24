<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once './vendor/autoload.php';
    require_once './clases/alumno.php';
    require_once './clases/persona.php';
    require_once './clases/alumnoDAO.php';
    require_once './clases/archivo.php';

    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;

    $app = new \Slim\App(["settings" => $config]);

    $app->group('/alumno', function(){
        $this->get('/', function ($request, $response, $args) {
            $new = $response->withJson(Alumno::mostrar());
            return $new;            
        });

        $this->post('/agregar', function ($request, $response, $args) {
            $datos = $request->getParsedBody();
            $alumno = new Alumno($datos['nombre'], )
        });
    });
    $app->run();


?>