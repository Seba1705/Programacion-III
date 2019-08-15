<?php
    require_once './clases/usuario.php';
    require_once './clases/archivo.php';
    require_once './clases/validar.php';
    require_once './clases/producto.php';

    $caso = '';

    if( isset($_POST['caso']) ) {
        $caso = $_POST['caso'];
    } else if ( isset($_GET['caso']) ) {
        $caso = $_GET['caso'];
    }

    switch( $caso ){
        case 'crearUsuario':
            Usuario::crearUsuario();
            break;
        case 'login':
            Usuario::login();
            break;
        case 'listarUsuarios':
            Usuario::listarUsuarios();
            break;
        case 'cargarProducto':
            Producto::cargarProducto();
            break;
        case 'listarProducto':
            Producto::listarProducto();
            break;
        case 'listarProductosConParametros':
            Producto::listarProductosConParametros();
            break;
        case 'modificarProducto':
            Producto::modificarProducto();
            break;
        default:
            echo "Debe ingresar un caso válido($caso).";
            break;
        }

?>


