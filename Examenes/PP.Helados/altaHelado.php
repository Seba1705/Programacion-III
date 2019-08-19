<?php
    //6.-(2 pts) AltaHelado.php: Recibe por POST el sabor, el precio y la foto del helado. Los datos delos helados se guardan en "./heladosArchivo/helados.txt". Guardar la foto en un subdirectorio "./heladosImagen/" con el nombre del helado punto hora minutos y segundos (Ejemplo: frutilla.102236.jpg).

    require_once './helado.php';

    function guardarHelado( $helado ){
        $ruta = './heladosArchivo/helados.txt';
        $archivo = fopen( $ruta, 'a+' );
        fwrite( $archivo, $helado->toCsv() );
        fclose( $archivo );
    }

    if( isset($_POST['sabor']) && !empty($_POST['sabor']) &&
        isset($_POST['precio']) && !empty($_POST['precio']) &&
        isset($_FILES['foto']) ){
        
        $origen = $_FILES["foto"]["tmp_name"];
        $nombreOriginal = $_FILES["foto"]["name"];
        $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $destinoFoto = "./heladosImagen/".$_POST['sabor'].".".date("His").".".$ext;
        
        $helado = new Helado( $_POST['sabor'], $_POST['precio']);
        $helado->setFoto( $destinoFoto );
        guardarHelado( $helado );
        move_uploaded_file($origen, $destinoFoto);
        echo 'Helado guardado';
    }else{
        echo 'Debe ingresar sabor, precio y foto';
    }
?>