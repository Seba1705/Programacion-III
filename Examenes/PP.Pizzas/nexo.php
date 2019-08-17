<?php
    require_once './clases/archivo.php';
    require_once './clases/pizza.php';

    $caso = '';

    if( isset($_POST['caso']) )
        $caso = $_POST['caso'];
    else if( isset($_GET['caso']) )
        $caso = $_GET['caso'];

    switch( $caso ){
        case 'pizzaCarga':
            Pizza::pizzaCarga();
            break;
        case 'pizzaConsultar':
            Pizza::pizzaConsultar();
            break;
        default:
            echo 'Ingrese un caso valido';
            break;
    }

?>