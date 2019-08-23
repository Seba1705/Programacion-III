<?php
    #Parte 2 -Ejercicios con Arrays
    
    //Aplicación Nº 9(Carga aleatoria) Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número(utilizar la función rand). Mediante una estructura condicional, determinar si el promedio de los números son mayores,menores o iguales que 6. Mostrar un mensaje por pantalla informando el resultado.
    echo 'Aplicacion 9' . PHP_EOL . PHP_EOL;
    $numeros = array( rand(1, 100), rand(1, 100), rand(1, 100), rand(1, 100), rand(1, 100) );
    var_dump( $numeros );
    
    //Aplicación Nº 10(Mostrar impares)Generar una aplicación que permita cargar los primeros 10 números impares en un Array. Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números utilizando las estructuras whiley foreach.
    
    //Aplicación Nº 11(Carga aleatoria) Imprima los valores del vector asociativo siguiente usando la estructura de control foreach: $v[1]=90; $v[30]=7; $v['e']=99; $v['hola']= 'mundo';
    echo PHP_EOL . 'Aplicacion 10' . PHP_EOL . PHP_EOL;
    $v[1] = 90; $v[30] = 7; $v['e'] = 99; $v['hola'] = 'mundo';
    foreach( $v as $item => $value ){
        echo 'Clave: ' . $item . ', Valor: ' . $value . PHP_EOL;
    }
    
    //Aplicación Nº 12(Arrays asociativos) Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que contenga como elementos: ‘color’, ‘marca’, ‘trazo’y ‘precio’. Crear, cargar y mostrar tres lapiceras.
    echo PHP_EOL . 'Aplicacion 11' . PHP_EOL . PHP_EOL;
    $lapicera = array('color' => 'rojo', 'marca' => 'bic', 'trazo' => 'fino', 'precio' => 10);    
    $lapicera2 = array('color' => 'verde', 'marca' => 'paper', 'trazo' => 'fino', 'precio' => 15);
    $lapicera3 = array('color' => 'negro', 'marca' => 'faber', 'trazo' => 'fino', 'precio' => 12);
    var_dump($lapicera);
    var_dump($lapicera2);
    var_dump($lapicera3);

    //Aplicación Nº 13 (Arrays asociativos II) Cargar los tres arrays con los siguientes valores y luego ‘juntarlos’ en uno. Luego mostrarlo por pantalla.“Perro”, “Gato”, “Ratón”, “Araña”, “Mosca”“1986”, “1996”, “2015”, “78”, “86”“php”, “mysql”, “html5”, “typescript”, “ajax”Para cargar los arrays utilizar la función array_push. Para juntarlos, utilizar la función array_merge.
    echo PHP_EOL . 'Aplicacion 12' . PHP_EOL . PHP_EOL;
    $arr1 = array();
    array_push($arr1, '1986', '1996', '2015', '78', '86');
    $arr2 = array();
    array_push($arr2, 'Perro', 'Gato', 'Raton', 'Araña', 'Mosca');
    $arr3 = array();
    array_push($arr3, 'php', 'mysql', 'html5', 'typescript', 'ajax');
    $arr4 = array_merge($arr2, $arr1, $arr3);
    var_dump($arr4);

    //Aplicación Nº 14(Arrays de Arrays) Realizar las líneas de código necesarias para generar un Array asociativo y otro indexadoque contengan como elementostres Arrays del punto anteriorcada uno. Crear, cargar y mostrar los Arrays de Arrays.
    echo PHP_EOL . 'Aplicacion 13' . PHP_EOL . PHP_EOL;
    $arrAsociativo = array('arr1' => $arr1, 'arr2' => $arr2, 'arr3' => $arr3);
    $arrIndexado = array($arr1, $arr2, $arr3);
    var_dump($arrAsociativo);
    var_dump($arrIndexado);
?>