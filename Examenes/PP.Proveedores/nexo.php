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
        case 'consultarProveedor':
            Proveedor::consultarProveedor();
            break;
        case 'proveedores':
            Proveedor::proveedores();
            break;
        case 'hacerPedido':
            Pedido::hacerPedido();
            break;
        case 'listarPedidos':
            Pedido::listarPedidos();
            break;
        case 'listarPedidoProveedor':
            Pedido::listarPedidoProveedor();
            break;
        case 'modificarProveedor':
            Proveedor::modificarProveedor();
            break;
        case 'fotosBack':
            Proveedor::fotosBack();
            break;
        default:
            echo 'Debe ingresar un caso valido';
            break;

    }
?>