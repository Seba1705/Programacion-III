<?php
    require_once './clases/Alien.php';
    require_once './clases/Archivo.php';

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        if( isset($_POST['planeta'], $_POST['email'], $_POST['clave']) && 
            !empty($_POST['planeta']) && !empty($_POST['email']) && !empty($_POST['clave']) ){
            if( !Alien::existeAlienEnArchivo( $_POST['email']) ){
                
                $alien = new Alien($_POST['planeta'], $_POST['email'], $_POST['clave'] );
                $alien->GuardarEnArchivo();
                
            }else{
                echo '{"mensaje":"Ya existe alien con ese email"}';
            }
        }else{
            echo '{"mensaje":"No se configuraron todas las variables"}';
        }
    }else{
        echo '{"mensaje":"Debe ingresar con metodo POST"}';
    }

?>