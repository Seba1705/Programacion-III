<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once './vendor/autoload.php';
    require_once './clases/archivo.php';
    require_once './clases/usuario.php';
    require_once './clases/log.php';

    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);

    /*3-2-A partir de este punto se debe guardar en una archivo info.log todas las peticiones que se hagan a la aplicación(caso, hora e ip).*/
    function guardarLog($ip, $ruta){
        $hora = date("h:i:s");
        $log = new Log($ruta, $ip, $hora);
        Log::Alta($log);
    }

    /*1-3-caso: cargarUsuario(post): Se deben guardar los siguientes datos: legajo, email, nombre, clave y dos fotos. Los datos se guardan en el archivo usuarios.xxx y las fotos en la carpeta img/fotos tomando el legajo como identificador(el legajo no puede estar repetido).*/
    $app->post('/cargarUsuario', function(Request $request, Response $response, array $args){
        guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());
        $arrDatos = $request->getParsedBody();
        //var_dump($arrDatos);
        $arrImg = $request->getUploadedFiles();
        //var_dump($arrImg);
        if(isset($arrDatos['legajo'], $arrDatos['nombre'], $arrDatos['clave'], $arrDatos['email'])){
            if(!empty($arrDatos['legajo']) && !empty($arrDatos['nombre']) && !empty($arrDatos['email']) && !empty($arrDatos['clave'])){
                if($arrImg['imagen'] != null && $arrImg['foto'] != null){
                    $nombre = $arrDatos['nombre']; $email = $arrDatos['email']; 
                    $legajo = $arrDatos['legajo']; $clave = $arrDatos['clave'];
                    if(!Usuario::validarLegajo($legajo)){
                        // Imagen
                        $origen = $arrImg['imagen']->file;
                        $nombreImagen = $arrImg['imagen']->getClientFilename();
                        $ext = pathinfo($nombreImagen, PATHINFO_EXTENSION);
                        $destinoImagen = "./img/".$legajo."-imagen.".$ext;
                        move_uploaded_file($origen, $destinoImagen);
                        //Foto
                        $origen2 = $arrImg['foto']->file;
                        $nombreFoto = $arrImg['foto']->getClientFilename();
                        $ext2 = pathinfo($nombreFoto, PATHINFO_EXTENSION);
                        $destinoFoto = "./img/".$legajo."-foto.".$ext2;
                        move_uploaded_file($origen2, $destinoFoto);

                        $usuario = new Usuario($legajo, $email, $nombre, $clave, $destinoImagen, $destinoFoto);
                        //var_dump($usuario);

                        Usuario::alta($usuario);
                        echo '{"mensaje":"Se guardo correctamente"}';
                    }else
                        echo '{"mensaje":"El legajo ya existe"}';
                }else
                    echo '{"mensaje":"Debe ingresar 2 imagenes"}';
            }else   
                echo '{"mensaje":"No puede haber campos vacios"}';
        }else
            echo '{"mensaje":"Faltan datos"}';
    });

    /*2-2-caso: login(get):Se ingresa legajo y clave, si ambos coinciden se devolverántodos los datos del usuario, si no, se informara que es lo que fallo. La búsqueda tiene que ser case insensitive.*/
    $app->post('/login', function(Request $request, Response $response, array $args){
        guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());
        
        $arrDatos = $request->getParsedBody();
        //var_dump($arrDatos);
        if(isset($arrDatos['legajo'], $arrDatos['clave'])){
            if(!empty($arrDatos['legajo']) && !empty($arrDatos['clave'])){
                $legajo = $arrDatos['legajo']; $clave = $arrDatos['clave'];
                if(Usuario::validarLegajo($legajo)){
                    $usuarios = Usuario::retornarUsuarios();
                    foreach($usuarios as $item){
                        if(strcasecmp($item->legajo, $legajo)){
                            if(strcasecmp($item->clave, $clave) == 0)
                                echo $item->toJSON();
                            else
                                echo '{"mensaje":"La clave es incorrecta"}';
                        }   
                    }
                }else
                    echo '{"mensaje":"El legajo no existe"}';
            }else   
                echo '{"mensaje":"No puede haber campos vacios"}';
        }else
            echo '{"mensaje":"Faltan datos"}';
    });

    /*4-(3pts.) caso: modificarUsuario(post): Se reciben todos los datos del usuario para modificarlos. En el caso que se carguen fotos nuevas, las fotos viejas se moverána la carpeta img/backup*/
    $app->post('/modificarUsuario', function(Request $request, Response $response, array $args){
        guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());

        $arrDatos = $request->getParsedBody();
        //var_dump($arrDatos);
        $arrImg = $request->getUploadedFiles();
        //var_dump($arrImg);
        if(isset($arrDatos['legajo'], $arrDatos['nombre'], $arrDatos['clave'], $arrDatos['email'])){
            if(!empty($arrDatos['legajo']) && !empty($arrDatos['nombre']) && !empty($arrDatos['email']) && !empty($arrDatos['clave'])){
                if($arrImg['imagen'] != null && $arrImg['foto'] != null){
                    $nombre = $arrDatos['nombre']; $email = $arrDatos['email']; 
                    $legajo = $arrDatos['legajo']; $clave = $arrDatos['clave'];
                    if(Usuario::validarLegajo($legajo)){
                        Usuario::modificar($arrDatos, $arrImg);
                    }else
                        echo '{"mensaje":"El legajo no existe"}';
                }else
                    echo '{"mensaje":"Debe ingresar 2 imagenes"}';
            }else   
                echo '{"mensaje":"No puede haber campos vacios"}';
        }else
            echo '{"mensaje":"Faltan datos"}';
    });

    /*5-(1pts.) caso: verUsuarios(get): Se mostrara una lista de todos los usuarios con sus datos excepto la clave.*/
    $app->get('/verUsuarios', function(Request $request, Response $response, array $args){
        guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());

        $usuarios = Usuario::retornarUsuarios();
        array_map('Usuario::mostrarUno', $usuarios);
    });

    /*6-(1pt.) caso: verUsuario(get): Se recibe un legajo y se devuelven todos los datos de dicho usuario.*/
    $app->get('/verUsuario', function(Request $request, Response $response, array $args){
        guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());

        $arrDatos = $request->getQueryParams();
        if(isset($arrDatos['legajo']) && !empty($arrDatos['legajo'])){
            $legajo = $arrDatos['legajo'];
            if(Usuario::validarLegajo($legajo)){
                $usuarios = Usuario::retornarUsuarios();
                foreach($usuarios as $item){
                    if(strcasecmp($item->legajo, $legajo) == 0)
                        Usuario::mostrarUno($item);
                }
            }else
                echo '{"mensaje":"El legajo no existe"}';
        }else
            echo '{"mensaje":"Ingrese legajo"}';
    });

    /*7-(2pts.) caso: logs (get): Se recibe una fecha y se devuelven todos los registros con fecha mayor a la indicada*/
    $app->get('/logs', function(Request $request, Response $response, array $args){
        guardarLog($request->getServerParam('REMOTE_ADDR'), $request->getUri()->getPath());

        $arrDatos = $request->getQueryParams();
        if(isset($arrDatos['fecha']) && !empty($arrDatos['fecha'])){
            $fecha = $arrDatos['fecha'];
            
        }else
            echo '{"mensaje":"Ingrese fecha"}';
    });

    $app->run();
?>