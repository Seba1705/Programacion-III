<?php
     use \Psr\Http\Message\ServerRequestInterface as Request;
     use \Psr\Http\Message\ResponseInterface as Response;

     require_once './vendor/autoload.php';
     require_once './clases/vehiculo.php';
     require_once './clases/servicio.php';
     require_once './clases/validar.php';
     require_once './clases/archivo.php';

     $config['displayErrorDetails'] = true;
     $config['addContentLengthHeader'] = false;
     
     $app = new \Slim\App(["settings" => $config]);
     
     $app->group('/vehiculo', function(){

        /*1-(2pt.) cargarVehiculo(post): Se deben guardar los siguientes datos: marca, modelo, patentey precio. Losdatos se guardan en el archivo detexto vehiculos.txt, tomando la patentecomo identificador(la patente no puede estar repetida).*/
        $this->post('/cargarVehiculo', function($request, $response, $args){
            $datos = $request->getParsedBody();
            // Validar que no haya campos nulos
            if(isset($datos['marca'], $datos['modelo'], $datos['patente'], $datos['precio'])){
                //Validar que no esten vacios
                if(!empty($datos['marca']) && !empty($datos['modelo']) && !empty($datos['patente']) && !empty($datos['precio'])){
                    //Validar patente
                    if(!Validar::validarPatente($datos['patente'])){
                        $vehiculo = new Vehiculo($datos['marca'], $datos['modelo'], $datos['patente'], $datos['precio']);
                        Vehiculo::agregar($vehiculo);
                    }else
                        echo '{"mensaje":"Ya existe vehiculo con esa patente"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacios"}';
            }else
                echo '{"mensaje":"Falta completar datos"}';
        });

        $this->get('/consultarVehiculo', function ($request, $response, $args) {
            return $response->withJson(Vehiculo::retornarVehiculos());          
        });
        
        $this->post('/cargarTipoServicio', function($request, $response, $args){
            $datos = $request->getParsedBody();
            if(isset($datos['id'], $datos['tipo'], $datos['precio'], $datos['demora'])){
                if(!empty($datos['id']) && !empty($datos['tipo']) && !empty($datos['precio']) && !empty($datos['demora'])){
                    if(!Validar::validarId($datos['id'])){
                        if(Validar::validarTipo($datos['tipo'])){
                            $servicio = new Vehiculo($datos['id'], $datos['tipo'], $datos['precio'], $datos['precio']);
                            Servicio::agregar($servicio);
                        }else
                            echo '{"mensaje":"Ingrese tipo valido"}';
                    }else
                        echo '{"mensaje":"Ya existe servicio con ese id"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacios"}';
            }else
                echo '{"mensaje":"Falta completar datos"}';
        });
        
        $this->post('/sacarTurno', function($request, $response, $args){
            $datos = $request->getParsedBody();
            if(isset($datos['patente'], $datos['fecha'])){
                if(!empty($datos['patente']) && !empty($datos['fecha'])){
                    if(Validar::validarPatente($datos['patente'])){
                        if(Validar::validarFecha($datos['fecha'])){
                            // $turno = new Turno()
                        }else
                            echo '{"mensaje":"Ingrese una fecha valida"}';
                    }else
                        echo '{"mensaje":"No existe vehiculo con esa patente"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacios"}';
            }else
                echo '{"mensaje":"Falta completar datos"}';
        });

        $this->get('/turnos', function ($request, $response, $args) {
            return $response->withJson(Servicio::mostrar());          
        });

        /*6-(2pts.) inscripciones(get): Puede recibir el tipo de servicio o la fecha y filtra la tabla de acuerdo al parámetro pasado.*/
        $this->get('/inscripciones', function ($request, $response, $args) {
            $datos = $request->getQueryParams();
            if(isset($datos['tipo'])){
                Turno::filtrarPorTipo($datos['tipo']);
            }else if(isset($datos['fecha'])){

            }else
                echo '{"mensaje":"Debe ingresar un parametro de busqueda"}';          
        });

        $this->post('/modificarVehiculo', function($request, $response, $args){
            $datos = $request->getParsedBody();
            $img = $request->getUploadedFiles();
            
            // Validar que no haya campos nulos
            if(isset($datos['marca'], $datos['modelo'], $datos['patente'], $datos['precio'])){
                //Validar que no esten vacios
                if(!empty($datos['marca']) && !empty($datos['modelo']) && !empty($datos['patente']) && !empty($datos['precio'])){
                    //Validar patente
                    if(Validar::validarPatente($datos['patente'])){
                        if($img != null){
                            Vehiculo::modificar($datos, $img['foto']);
                            echo '{"mensaje":"Vehiculo modificado"}';
                        }else
                            echo '{"mensaje":"Debe ingresar una imagen"}';
                    }else
                        echo '{"mensaje":"No existe vehiculo con esa patente"}';
                }else
                    echo '{"mensaje":"No puede haber campos vacios"}';
            }else
                echo '{"mensaje":"Falta completar datos"}';
        });

        $this->get('/vehiculos', function($request, $response, $args){
            return Vehiculo::vehiculos();     
        });
        
    });

    $app->run();
?>