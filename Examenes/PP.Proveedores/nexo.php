<?php
    require_once './clases/archivo.php';
    require_once './clases/proveedor.php';
    require_once './clases/pedido.php';

    $caso = '';

    if( isset($_POST['caso']) ) 
        $caso = $_POST['caso'];
    else if( isset($_GET['caso']) ) 
        $caso = $_GET['caso'];

    switch( $caso ){
        case 'cargarProveedor':
            Proveedor::cargarProveedor();
            break;
    }
?>