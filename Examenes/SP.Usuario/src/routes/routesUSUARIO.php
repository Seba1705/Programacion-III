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
            
            /*1. (POST) usuario. ​Recibe email, clave y tipo (alumno, profesor, admin) y asigna un número de legajo y guarda el usuario en la base de datos.*/
            $this->post('/usuario', function ($request, $response, $args) {
                $datos = $request->getParsedBody();
            
                if( isset($datos['email']) && !empty($datos['email']) && 
                    isset($datos['nombre']) && !empty($datos['nombre']) && 
                    isset($datos['tipo']) && !empty($datos['tipo']) && 
                    isset($datos['clave']) && !empty($datos['clave'])){

                    if(strcasecmp($datos['tipo'], 'alumno') == 0 || strcasecmp($datos['tipo'], 'admin') == 0 || strcasecmp($datos['tipo'], 'profesor') == 0){
                        // Encripto clave
                        $clave = crypt($datos['clave'], 'st');
                        // Usuario para cargar en base
                        $user = new usuario;
                        // Cargo datos
                        $user->email = $datos['email'];
                        $user->clave = $clave;
                        $user->nombre = $datos['nombre'];
                        $user->tipo = $datos['tipo'];;
                        // Guardo
                        $user->save();
                   
                        return $response->withJson($user, 200);
                    }else
                        return $response->withJson('El tipo es incorrecto', 200);
                }else
                    return $response->withJson('Debe ingresar email, contraseña, nombre y tipo', 200);
            });

            /*2. (POST) login: ​Recibe legajo y nombre y si son correctos devuelve un JWT, de lo contrario informar lo sucedido.*/
            $this->post('/login', function ($request, $response, $args){
                $datos = $request->getParsedBody();
                if( isset($datos['clave']) && !empty($datos['clave']) && 
                    isset($datos['nombre']) && !empty($datos['nombre'])){
                    // Datos enviados por parametros
                    $nombre = $datos['nombre'];
                    $clave = $datos['clave'];                    
                    // Busco usuario por nombre
                    $user = usuario::where('nombre', $nombre);
                    // Obtengo clave guardada en la base
                    $claveBase = $user->get()[0]->clave;
                    // Hago la comparacion
                        
                }else
                    return $response->withJson('Debe ingresar nombre y clave', 200);
            });


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

         
        });
    }
?>