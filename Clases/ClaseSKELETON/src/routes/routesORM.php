<?php

    use Slim\App;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use App\Models\ORM\cd;
    use App\Models\ORM\cdApi;

    include_once __DIR__ . '/../../src/app/modelORM/cd.php';
    include_once __DIR__ . '/../../src/app/modelORM/cdControler.php';

    return function (App $app) {
    
        $container = $app->getContainer();

        $app->group('/cdORM', function () {   
            $this->get('/', function ($request, $response, $args) {
                //Buscar por titel
                $cd = cd::destroy(7);
                

                $newResponse = $response->withJson($cd, 200);  
                return $newResponse;
            });


        });

        $app->group('/cdORM2', function () {   
            $this->get('/',cdApi::class . ':traerTodos');
        });

};