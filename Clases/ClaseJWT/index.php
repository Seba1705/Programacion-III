<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use \Firebase\JWT\JWT as JWT;

    require_once './vendor/autoload.php';

    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;

    $app = new \Slim\App(["settings" => $config]);

    $app->group('/login', function () {

        $this->get('/', function ($request, $response, $args) {
            $key = 'Seba1705';
            if( isset($request->getHeader('token')[0]) && !empty($request->getHeader('token')[0])){
                $token = $request->getHeader('token')[0];
                try {
                    $token = $request->getHeader('token')[0];
                    $decoded = JWT::decode($token, $key, array('HS256'));
                    return $response->withJson($decoded, 200);
                }catch(Exception $e){
                    return $response->withJson($e->getMessage(), 200);
                }
            }else
                return $response->withJson('Debe ingresar el parametro token', 200);
        });

        $this->post('/', function ($request, $response) {
            $key = 'Seba1705';
            $datos = $request->getParsedBody();
            if( isset($datos['user']) && !empty($datos['user']) && isset($datos['pass']) && !empty($datos['pass'])){
                $token = array(
                    "user" => $datos['user']
                );
                $jwt = JWT::encode($token, $key);
                return $response->withJson($jwt, 200);
            }else
                return $response->withJson('Debe ingresar usuario y contraseña', 200);
        });

    });

    $app->run();

?>