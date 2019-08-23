<?php
    require_once './clases/Usuario.php';


    $user = new Usuario('seba@gmail.com', '3425');
    
    $user->guardarEnArchivo();  
?>