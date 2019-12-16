<?php

    use Slim\App;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use App\Models\ORM\cd;
    use App\Models\ORM\cdApi;
    use App\Models\ORM\usuario;
    use App\Models\ORM\usuarioControler;


    include_once __DIR__ . '/../../src/app/modelORM/cd.php';
    include_once __DIR__ . '/../../src/app/modelORM/cdControler.php';

    include_once __DIR__ . '/../../src/app/modelORM/usuario.php';
    include_once __DIR__ . '/../../src/app/modelORM/usuarioControler.php';

    return function (App $app) {
        $container = $app->getContainer();

        $app->group('/cdORM', function () {   
            
            $this->get('/', function ($request, $response, $args) {
                //return cd::all()->toJson();
                $todosLosCds=cd::all();
                $newResponse = $response->withJson($todosLosCds, 200);  
                return $newResponse;
            });
        });


        $app->group('/cdORM2', function () {   

            $this->get('/', cdApi::class . ':traerTodos');
      
        });

        $app->group('/user', function () {   

            $this->post('/users', usuarioControler::class . ':CargarUno');

            $this->post('/login', usuarioControler::class . ':Login');

            $this->post('/TraerUno', usuarioControler::class . ':TraerUno');


            
        });

    };
?>