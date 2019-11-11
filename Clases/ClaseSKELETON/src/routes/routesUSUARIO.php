<?php

    use Slim\App;
    use App\Models\ORM\usuario;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use \Firebase\JWT\JWT as JWT;

    include_once __DIR__ . '/../../src/app/modelORM/usuario.php';

    return function (App $app) {
        $container = $app->getContainer();
    
        $app->group('/Usuario', function () {   
            
            $this->get('/token', function ($request, $response, $args) {
                $key = 'Seba1705';
                if( isset($request->getHeader('token')[0]) && !empty($request->getHeader('token')[0])){
                    $token = $request->getHeader('token')[0];
                    try {
                        $token = $request->getHeader('token')[0];
                        $decoded = JWT::decode($token, $key, array('HS256'));
                        return $response->withJson($decoded->data, 200);
                    }catch(Exception $e){
                        return $response->withJson($e->getMessage(), 200);
                    }
                }else
                    return $response->withJson('Debe ingresar el parametro token', 200);
            });

            // Listar todos
            $this->get('/', function ($request, $response, $args) {
                $user = new usuario;
                $datos = $user::all();
                $newResponse = $response->withJson($datos, 200);  
                return $newResponse;                
            });

            // Alta
            $this->post('/', function ($request, $response, $args) {
                $key = 'Seba1705';
                $time = time();
                $datos = $request->getParsedBody();
            
                if( isset($datos['email']) && !empty($datos['email']) && 
                    isset($datos['foto']) && !empty($datos['foto']) && 
                    isset($datos['tipo']) && !empty($datos['tipo']) && 
                    isset($datos['clave']) && !empty($datos['clave'])){

                    // Encripto clave
                    $clave = crypt($datos['clave'], 'st');
                    // Usuario para cargar en base
                    $user = new usuario;
                    // Cargo datos
                    $user->email = $datos['email'];
                    $user->clave = $clave;
                    $user->foto = $datos['foto'];
                    $user->tipo = 1;
                    // Guardo
                    $token = array(
                        'iat' => $time, // Tiempo que inició el token
                        'exp' => $time + (60*2), // Tiempo que expirará el token (+1 hora)
                        'data' => [ // información del usuario
                            'email  ' => $datos['email'],
                            'foto' => $datos['foto'],
                            'tipo' => $datos['tipo']
                        ]
                    );

                    $user->save();
                    $jwt = JWT::encode($token, $key);
                    return $response->withJson($jwt, 200);
                }else
                    return $response->withJson('Debe ingresar email, contraseña y tipo', 200);
            });

            // Eliminar uno
            $this->delete('/', function ($request, $response, $args) {
                $id = $request->getParsedBody()['id'];
                //echo  $id;
                $user = usuario::destroy($id); // Retorna 1 si està ok - 0 si falla
                if($user == 1)
                    $newResponse = $response->withJson('Usuario eliminado', 200);
                else    
                    $newResponse = $response->withJson('No existe usuario con ese ID', 200);

                return $newResponse;
            });

            $this->get('/login', function ($request, $response, $args){
                $res = hash_equals('stbu3CrK.J7XE', 'stbu3CrK.J7XE');
                var_dump($res);
            });

        });
    }
?>