<?php
    //7.-(2 pts) GrillaHelados.php:Muestra una tabla con todos los helados. Mostrando el sabor, precio e imagen correspondiente.Crear un nuevo método estático en la clase HELADO, que retorne un array de objetos de tipo Helado (recuperados del archivo de texto).

    require_once './helado.php';

    $helados = Helado::retornarHeladosDeArchivo();

    foreach( $helados as $helado ){
        echo $helado->toString();
    }
?>