<?php
    include_once './clases/vehiculo.php';
    include_once './clases/servicio.php';
    include_once './clases/turno.php';

    $caso = '';

    if(isset($_GET['caso'])){
        $caso = $_GET['caso'];
    }
    else if(isset($_POST['caso'])){
        $caso = $_POST['caso'];
    }

    switch($caso){
        case 'cargarVehiculo':
            Vehiculo::cargarVehiculo();
            break;
        case 'consultarVehiculo':
            Vehiculo::consultarVehiculo();
            break;
        case 'cargarTipoServicio':
            Servicio::cargarTipoServicio();
            break;
        case 'sacarTurno':
            Turno::sacarTurno();
            break;
        case 'turnos':
            Turno::turnos();
            break;
        case 'inscripciones':
            Turno::inscripciones();
            break;
        default:
            echo "Debe ingresar un caso válido($caso).";
            break;
    }

?>
