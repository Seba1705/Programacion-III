<?php
    // 3.-(1 pt) ValidarCliente.php: Recibe por POST el correo y clave, si coincide con algún registro de los cargados en el archivo de texto, retorna el mensaje "Cliente Logeado" (con el nombre del mismo), caso contrario,retorna "Cliente inexistente".

    require_once './cliente.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if( isset($_POST['correo']) && !empty($_POST['correo']) &&
        isset($_POST['clave']) && !empty($_POST['clave']) ){
            
            $correo = $_POST['correo'];
            $clave = $_POST['clave'];
            $nombre = '';
        
            $archivo = fopen( './clientes/clientesActuales.txt', 'r' );
            do{
                $cliente = trim( fgets($archivo) );
                if( $cliente != '' ){
                    $cliente = explode( ';', $cliente );
                    if( $cliente[1] == $correo && $cliente[2] == $clave){
                        $nombre = $cliente[0];
                    }
                }
            }while( !feof($archivo) );
            fclose( $archivo );
            if($nombre != ''){
                echo 'Cliente loguado: ' . $nombre;
            }else{
                echo 'Cliente inexistente';
            }
        }else{
            echo 'Debe ingresar correo y clave para loguarse';
        }
    }

?>