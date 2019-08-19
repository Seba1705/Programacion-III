<?php
    //6.-(2 pts) AltaHelado.php: Recibe por POST el sabor, el precio y la foto del helado. Los datos delos helados se guardan en "./heladosArchivo/helados.txt". Guardar la foto en un subdirectorio "./heladosImagen/" con el nombre del helado punto hora minutos y segundos (Ejemplo: frutilla.102236.jpg).

    require_once './helado.php';

    if( isset($_POST['sabor']) && !empty($_POST['sabor']) &&
        isset($_POST['precio']) && !empty($_POST['precio']) ){

    }else{
        echo 'Debe ingresar sabor y precio';
    }
?>