<?php
    require_once './clases/persona.php';
    require_once './clases/alumno.php';

    $juan = new Alumno( 'Juan', 'Perez');
    
    $juan->saludar();
?>