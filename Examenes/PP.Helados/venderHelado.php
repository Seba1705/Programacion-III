<?php
    // 5.-(2 pts) VenderHelado.php: Recibe por GET el sabor del helado y la cantidad. Si el sabor existe, retorna el precio total a pagar (con IVA incluido). Si no se encuentra el sabor, retorna un mensaje informando lo acontecido.
    
    require_once './helado.php';
    
    if( isset($_GET['sabor']) && !empty($_GET['sabor']) &&
        isset($_GET['cantidad']) && !empty($_GET['cantidad']) ){
        
        $helados = Helado::retornarArrayDeHelados();
        $seleccionado = null;
        foreach( $helados as $helado ){
            if( strcasecmp($helado->getSabor(), $_GET['sabor']) == 0 ){
                $seleccionado = $helado;
            }
        }
        if( $seleccionado == null ){
            echo 'No se encontro el sabor';
        }else{
            echo 'Total a pagar: ' . $seleccionado->precioMasIva() * number_format($_GET['cantidad']);   
        }
      
    }else{
        echo 'Debe ingresar sabor y cantidad';
    }
?>