<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once './vendor/autoload.php';
    require_once './clases/archivo.php';
    //require_once './clases/usuario.php';
    //require_once './clases/log.php';

    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);

    function guardarLog($ip, $ruta){
        $hora = date("h:i:s");
        $log = new Log($ruta, $ip, $hora);
        Log::Alta($log);
    }

    $app->post('/alta', function(Request $request, Response $response, array $args){
        //guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());
        $arrDatos = $request->getParsedBody();
        //var_dump($arrDatos);
        $arrImg = $request->getUploadedFiles();
        //var_dump($arrImg);
    });

    $app->post('/modificar', function(Request $request, Response $response, array $args){
        //guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());
        $arrDatos = $request->getParsedBody();
        //var_dump($arrDatos);
        $arrImg = $request->getUploadedFiles();
        //var_dump($arrImg);
      
    });

    $app->get('/mostrar', function(Request $request, Response $response, array $args){
        //guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());
        $arrDatos = $request->getQueryParams();
    });

    $app->run();
?>