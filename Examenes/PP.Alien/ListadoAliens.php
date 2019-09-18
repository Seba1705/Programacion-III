<?php
    require_once './clases/Alien.php';
    require_once './clases/Archivo.php';

    if( $_SERVER['REQUEST_METHOD'] == 'GET') {

        $aliens = Alien::TraerTodos();
        array_map( 'Alien::mostrarAlien', $aliens );
        
    }else{
        echo '{"mensaje":"Debe llamarse con el metodo GET"}';
    }

?>