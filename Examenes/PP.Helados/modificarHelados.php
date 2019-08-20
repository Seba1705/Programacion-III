<?php
    //9.-(2 pts) ModificarHelados.php: Recibe el sabor, precio y la foto de un Helado, si existe (buscar por sabor), se guardan los nuevos datos en el archivo de texto. La foto anterior se elimina del subdirectorio "./heladosImagen/" y se reemplaza por la nueva (con el nuevo nombre).Si no se encuentra el helado a ser modificado, informar por medio de un mensaje.       

    require_once './helado.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if( isset($_POST['sabor']) && !empty($_POST['sabor']) &&
            isset($_POST['precio']) && !empty($_POST['precio']) && 
            isset($_FILES['foto']) ){
            $helados = Helado::retornarHeladosDeArchivo();
            $flag = true;
            foreach( $helados as $helado ){
                if( strcasecmp($helado->getSabor(), $_POST['sabor']) == 0 ){
                    
                    $helado->setSabor( $_POST['sabor'] );
                    $helado->setPrecio( $_POST['precio'] );
                    unlink($helado->getFoto());

                    $origen = $_FILES["foto"]["tmp_name"];
                    $nombreOriginal = $_FILES["foto"]["name"];
                    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                    $destinoFoto = "./heladosImagen/".$_POST['sabor'].".".date("His").".".$ext;
                    move_uploaded_file($origen, $destinoFoto);
                    $helado->setFoto($destinoFoto);
                    echo 'Helado modificado';
                    $flag = false;
                    Helado::guardarListaDeHelados($helados);
                    break;
                }
            }
            if( $flag )
                echo 'No existe el sabor ' . $_POST['sabor'];
        }else{
            echo 'Debe ingresar sabor, precio y foto';
        }
    }
?>