<?php
    #Parte 1 -Ejercicios Simples 
    
    //Aplicación Nº 1 (Mostrar variables) Realizar un programa que guarde su nombre en $nombrey su apellido en $apellido. Luego mostrar el contenido de las variables con el siguiente formato: Pérez, Juan. Utilizar el operador de concatenación.
    echo 'Aplicacion 1' . PHP_EOL . PHP_EOL;
    $nombre = 'Sebastian';
    $apellido = 'Aguirre';
    echo $apellido . ', ' . $nombre . PHP_EOL;
    
    //Aplicación Nº 2(Sumar dos números) Hacer un programa en PHP que sume el contenido de dos variables $x= -3 y $y= 15. Mostrar el resultado final. 
    echo PHP_EOL . 'Aplicacion 2' . PHP_EOL . PHP_EOL;
    $x = -3; $y = 15; $resultado = $x + $y;
    echo $resultado . PHP_EOL;

    //Aplicación Nº 3(Sumar dos números II) Partiendo del ejercicio anterior, modificar la salida por pantalla para que se visualice el valor de lavariable $x, el valor de la variable $y y el resultado finalen líneas distintas (recordar que el salto de línea en HTML es la etiqueta <br/>). 
    echo PHP_EOL . 'Aplicacion 3' . PHP_EOL . PHP_EOL;
    echo $x . ' + ' . $y . ' = ' . $resultado . PHP_EOL;
    
    //Aplicación Nº 4(Sumar números) Confeccionar un programa que sume todos los números enteros desde 1 mientras la suma no supere a 1000. Mostrar los números sumados y al finalizar el proceso indicar cuantos números se sumaron. 
    echo PHP_EOL . 'Aplicacion 4' . PHP_EOL . PHP_EOL;
    $suma = 0; $contador = 0; $i = 1;
    while( true ){
        $suma += $i; $contador ++; $i++;
        if( $suma > 1000 ){
            $suma -= $i; $contador--;
            break;
        }   
    };
    echo 'Suma: ' . $suma . ' Contador: ' . $contador . PHP_EOL;
    
    //Aplicación Nº 5 (Obtener el valor del medio) Dadas tres variables numéricas de tipo entero $a, $by $c, realizar una aplicación que muestre el contenido de aquella variable que contenga el valor que se encuentre en el medio de las tres variables. De no existir dicho valor, mostrar un mensaje que indique lo sucedido. Ejemplo 1: $a = 6; $b = 9; $c = 8; => se muestra 8. Ejemplo 2: $a = 5; $b = 1; $c = 5; => se muestra un mensaje “No hay valor del medio”
    
    //Aplicación Nº 6 (Calculadora) Escribir un programa que use la variable $operadorque puedaalmacenar los símbolos matemáticos: ‘+’, ‘-’, ‘/’ y ‘*’; y definir dos variables enteras $op1y $op2. De acuerdo al símbolo que tenga la variable $operador, deberá realizarse la operación indicada y mostrarse el resultado por pantalla.
    
    //Aplicación Nº 7(Mostrar fecha y estación) Obtenga la fecha actual del servidor (función date) y luego imprímala dentro de la página con distintos formatos (seleccione losformatosque más le guste). Además indicar que estación del año es. Utilizar una estructura selectiva múltiple.
    
    //Aplicación Nº 8(Números en letras) Realizar un programa que en base al valor numérico de la variable $num, pueda mostrarse por pantalla, el nombre del número que tenga dentro escrito con palabras, para los números entre el 20 y el 60. 

?>