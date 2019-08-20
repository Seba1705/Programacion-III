<?php
    //8.-(2 pts) BorrarHelados.php:Si recibe un sabor por GET, retorna si el helado está en el archivo o no. Si lo recibe por POST, con el parámetro “queDeboHacer” igual a "borrar", se borra el helado del archivo y se mueve la foto al subdirectorio “./heladosBorrados/”, con el nombre formado por el sabor punto 'borrado' punto hora minutos y segundos del borrado (Ejemplo: frutilla.borrado.105905.jpg).

    require_once './helado.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if( isset($_POST['queDeboHacer']) && strcasecmp($_POST['queDeboHacer'], 'borrar') == 0){
            if( isset($_POST['sabor']) && !empty($_POST['sabor'])){
                $helados = Helado::retornarHeladosDeArchivo();
                $flag = true;
                for( $i=0, $a=count($helados) ; $i<$a; $i++ ){
                    if( strcasecmp($helados[$i]->getSabor(), $_POST['sabor']) == 0 ){
                        $origen = $helados[$i]->getFoto();
                        $imagen = explode('.', $origen);
                        $ext = $imagen[3];
                        $destinoFoto = "./heladosBorrados/".$_POST['sabor'].".borrado.".date("His").".".$ext;
                        if(copy($origen, $destinoFoto)){
                            unlink($origen);
                        }
                        
                        $flag = false;
                        unset( $helados[$i] );
                        Helado::guardarListaDeHelados($helados);
                        echo 'Helado modificado';
                        break;
                    }
                }
                if( $flag ) echo 'No existe el sabor';
            }else{
                echo 'Ingrese el sabor';
            }
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