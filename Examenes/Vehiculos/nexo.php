<?php
    include_once './clases/vehiculo.php';

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
            
        default:
            echo "Debe ingresar un caso válido($caso).";
            break;
    }

?>