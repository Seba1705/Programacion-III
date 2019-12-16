<?php

    use Slim\App;
    use App\Models\ORM\usuario;
    use App\Models\ORM\materia;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use \Firebase\JWT\JWT as JWT;
    use App\Models\AutentificadorJWT;


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
                    $clave = crypt($datos['clave'], 'st');                    
                    // Busco usuario por nombre
                    $user = usuario::where('nombre', $nombre)->first();
                    // Si es NULL el nombre no existe
                    if ($user != NULL){
                        // Clave del usuario que se quiere loguear
                        $claveReal = $user->clave;
                        // Verifico que las claves coincidan
                        $res = hash_equals($clave, $claveReal);
                        if( $res ){
                            $data = array(
                                'nombre' => $user->nombre,
                                'legajo' => $user->legajo,
                                'email' => $user->email,
                                'tipo' => $user->tipo
                            );
                            $jwt = AutentificadorJWT::crearToken($data);
                            return $response->withJson($jwt, 200);
                        }else   
                            return $response->withJson('La clave es incorrecta', 200);
                    }else
                        return $response->withJson('El nombre de usuario no existe', 200);
                }else
                    return $response->withJson('Debe ingresar nombre y clave', 200);
            });

            /*3.(POST) materia: ​(Solo para admin). Recibe nombre, cuatrimestre y cupos.*/
            $this->post('/materias', function($request, $response, $args){
                $datos = $request->getParsedBody();
            
                if( isset($datos['nombre']) && !empty($datos['nombre']) && 
                    isset($datos['cuatrimestre']) && !empty($datos['cuatrimestre']) && 
                    isset($datos['cupos']) && !empty($datos['cupos'])){            
                    
                    $materia = new materia;
                    $materia->nombre = $datos['nombre'];           
                    $materia->cuatrimestre = $datos['cuatrimestre'];
                    $materia->cupos = $datos['cupos'];
                    
                    try{
                        $materia->save();
                        return $response->withJson('Materia cargada', 200);
                    }catch(Exception $e){
                        return $response->withJson($e->getMessage(), 200);
                    }
                }else
                    return $response->withJson('Debe ingresar nombre, cupo y cuatrimestre', 200);

            });
        });
    }
?>