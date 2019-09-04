<?php
    $datos = $_POST;
    
    $path = './archivos/datos.txt';
    
    $file = fopen( $path, 'a' );

    fwrite( $file, json_encode($datos) . PHP_EOL);

    fclose( $file );

    echo '{"mensaje":"Dato guardado"}';
?>