<?php
    require_once './clases/Alien.php';
    require_once './clases/Archivo.php';

    $alien = new Alien('Marte', 'seba@gmail.com', '1234');

    var_dump($alien);
    
    // $alien->GuardarEnArchivo();

    $aliens = Alien::TraerTodos();

    var_dump($aliens);
    


?>