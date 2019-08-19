<?php
    // 2.-(0,5 pt) ClienteCarga.php: (no tiene lógica, sólo llamadas a métodos.) Recibe por GET los siguientesdatos: nombre, correo y clave,crea el objeto de tipo CLIENTE y lo guarda en el archivo “./clientes/clientesActuales.txt” (en un renglón distinto)

    require_once './cliente.php';

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if( isset($_GET['nombre']) && !empty($_GET['nombre']) &&
        isset($_GET['correo']) && !empty($_GET['correo']) &&
        isset($_GET['clave']) && !empty($_GET['clave']) ){
        
        $cliente = new Cliente( $_GET['nombre'], $_GET['correo'], $_GET['clave'] );
        Cliente::guardarEnArchivo( './clientes/clientesActuales.txt', $cliente );

        echo 'Se guardo el cliente: ' . $cliente->toString();
        }else{
            echo 'Debe ingresar los datos del cliente';
        }
    }

?>          