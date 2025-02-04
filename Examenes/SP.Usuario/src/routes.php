<?php
    use Slim\App;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use App\Models\usuario;
    use App\Models\materia;

    return function (App $app) {
        $container = $app->getContainer();

        // Rutas JWT
        $routes = require __DIR__ . '/../src/routes/routesJWT.php';
        $routes($app);
        
        // Rutas Usuario
        $routes = require __DIR__ . '/../src/routes/routesUSUARIO.php';
        $routes($app);

        $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
            // Sample log message
            $container->get('logger')->info("Slim-Skeleton '/' route");
            //$container->get('logger')->addCritical('Hey, a critical log entry!');
            return $container->get('renderer')->render($response, 'index.phtml', $args);
        });
    };

?>