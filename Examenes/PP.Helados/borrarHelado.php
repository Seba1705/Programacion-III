<?php
    //8.-(2 pts) BorrarHelados.php:Si recibe un sabor por GET, retorna si el helado está en el archivo o no. Si lo recibe por POST, con el parámetro “queDeboHacer” igual a "borrar", se borra el helado del archivo y se mueve la foto al subdirectorio “./heladosBorrados/”, con el nombre formado por el sabor punto 'borrado' punto hora minutos y segundos del borrado (Ejemplo: frutilla.borrado.105905.jpg).

    require_once './helado.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if( isset($_POST['queDeboHacer']) && strcasecmp($_POST['queDeboHacer'], 'borrar') == 0){
            echo 'ok';
        }else{
            echo 'Ingrese una accion valida';
        }
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if( isset($_GET['sabor']) && !empty(['sabor']) ){
            $sabor = $_GET['sabor'];
            $helados = Helado::retornarHeladosDeArchivo();
            $flag = false;
            
            foreach( $helados as $item ){
                if( strcasecmp($item->getSabor(), $sabor) == 0 )
                    $flag = true;
            }
    
            echo $flag ? 'Hay helado de ' . $sabor : 'No hay de ese sabor';
        }else{
            echo 'Ingrese sabor';
        }
    }

?>