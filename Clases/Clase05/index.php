<?php
    
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    require_once './vendor/autoload.php';
    require_once '../Clase01/clases/alumno.php';
    require_once '../Clase01/clases/persona.php';
    require_once '../Clase02/clases/alumnoDAO.php';


    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;

    $app = new \Slim\App(["settings" => $config]);

    $app->get('/', function (Request $request, Response $response) {    
        $response->getBody()->write('Mensaje por GET');
        return $response;
    }); 

    $app->post('/', function(Request $request, Response $response){
        $response->getBody()->write('Mensaje por POST');
        return $response;  
    });

    $app->group('/alumno', function(){
        $this->get('/', function ($request, $response, $args) {
            $new = $response->withJson(Alumno::mostrarAlumnos());
            return $new;            
        });
        $this->post('/cargar', function ($request, $response, $args) {
            $datos = $request->getParsedBody();
            // var_dump($datos);
            // $alumno = new Alumno();
        });
    });

    $app->run();

   
?>