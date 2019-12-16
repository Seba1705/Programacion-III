<?php
    namespace App\Models\ORM;
    use App\Models\ORM\usuario;
    use App\Models\IApiControler;
    use App\Models\AutentificadorJWT;


    include_once __DIR__ . '/usuario.php';
    include_once __DIR__ . '../../modelAPI/IApiControler.php';
    include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';


    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;


    class usuarioControler implements IApiControler 
    {
        public function Beinvenida($request, $response, $args) {
            $response->getBody()->write("GET => Bienvenido!!! ,a UTN FRA SlimFramework");
        
            return $response;
        }
        
        public function TraerTodos($request, $response, $args) {
            //return usuario::all()->toJson();
            $todosLosusuarios = usuario::all();
            $newResponse = $response->withJson($todosLosusuarios, 200);  
            return $newResponse;
        }

        public function TraerUno($request, $response, $args) {
            if( isset($request->getHeader('token')[0]) && !empty($request->getHeader('token')[0])){
                $token = $request->getHeader('token')[0];
                try {
                    $datos = AutentificadorJWT::ObtenerData($token);
                    var_dump($datos);
                }catch(Exception $e){
                    return $response->withJson($e->getMessage(), 200);
                }
            }else
                return $response->withJson('Debe ingresar el parametro token', 200);
        }
    
        // 2-(3 pts.) ​usuarios​ (POST): se recibe email, clave, legajo(1 entre 1 y 1000) y dos imágenes (guardarlas en la carpetaimages/usuarios y cambiarles el nombre para que sea  único). No se podrá guardar la clave en texto plano. Todos lasdatos deberán ser validados antes de guardarlos en la BD.
        public function CargarUno($request, $response, $args) {
            $datos = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();
            if( isset($datos['email'], $datos['clave'], $datos['legajo'], $archivos['foto'], $archivos['imagen'])  && 
                !empty($datos['email']) && !empty($datos['legajo']) && !empty($datos['clave']) && 
                $datos['legajo'] > 0 && $datos['legajo'] < 1001 && !usuario::where('legajo','=',$datos['legajo'])->exists()){
                
                $usuario = new usuario;

                $usuario->email = strtoupper($datos["email"]);
                $usuario->legajo = strtoupper($datos["legajo"]);
                $usuario->clave = AutentificadorJWT::EncriptarClave(strtoupper($datos['clave']));

                //Foto
                $tmpName = $archivos["foto"]->getClientFilename();
                $extension = pathinfo($tmpName, PATHINFO_EXTENSION);
                $foto = "./images/usuarios/" . $usuario->legajo . "-img-" . '.' . $extension;
                $archivos["foto"]->moveTo($foto);
                $usuario->foto =  $foto;

                //Imagen
                $tmpName = $archivos["imagen"]->getClientFilename();
                $extension = pathinfo($tmpName, PATHINFO_EXTENSION);
                $imagen = "./images/usuarios/" . $usuario->legajo . "-foto-" . '.' . $extension;
                $archivos["imagen"]->moveTo($imagen);
                $usuario->imagen =  $imagen;

                try{
                    $usuario->save();
                }catch(Exception $e){
                    echo $e->getMessage();
                }
                
                echo $response->withJson('Usuario cargado', 200);
                        
            }else   
                echo $response->withJson("Verifique los datos ingresados", 200);  

        }
        
        public function BorrarUno($request, $response, $args) {
            //complete el codigo
            $newResponse = $response->withJson("sin completar", 200);  
            return $newResponse;
        }
        
        public function ModificarUno($request, $response, $args) {
            //complete el codigo
            $newResponse = $response->withJson("sin completar", 200);  
                return 	$newResponse;
        }

        // 3- (2 pts.) ​login​: (POST): Recibe clave, email y legajo, si estos datos existen en la BD​,  ​retornar un JWT, de lo contrario informar lo ocurrido. La consulta debe ser ​case insensitive​.
        public function Login($request, $response, $args) {
            $datos = $request->getParsedBody();

            if( isset($datos['email'], $datos['clave'], $datos['legajo'])  && 
                !empty($datos['email']) && !empty($datos['legajo']) && !empty($datos['clave']) && $datos['legajo'] > 0 && $datos['legajo'] < 1001){
                
                $user = usuario::find($datos['legajo']);

                if($user != NULL){
                    if($user->email == $datos['email']){
                        if(hash_equals($user->clave, AutentificadorJWT::EncriptarClave(strtoupper($datos['clave'])))){
                            $datos = array(
                                'email' => strtoupper($datos['email']),
                                'legajo' => strtoupper($datos['legajo'])
                            );

                            $jwt = AutentificadorJWT::CrearToken($datos);
                            echo $jwt;
                        }else
                            echo $response->withJson("La clave ingresada es incorrecta", 200);         
                    }else   
                        echo $response->withJson("El email no coincide con el registrado en la BD", 200);         
                }else
                    echo $response->withJson("El legajo ingresado no existe", 200);         
            }else   
                echo $response->withJson("Verifique los datos ingresados", 200);  
        }
    }



?>