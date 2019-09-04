<?php
    
    $path = './archivos/datos.txt';
    
    $retorno = array(); //Lo va a devolver con las entidades leidas

    $archivo = fopen( $path, 'r' );

    do{
        $dato = trim(fgets($archivo));
        if( $dato != '')
            array_push( $retorno, json_decode($dato) );
    }while( !feof($archivo) );

    fclose($archivo); 

    var_dump($retorno);

?>