<?php
    session_start();

    require_once '../Clase01/clases/persona.php';
    require_once '../Clase01/clases/alumno.php';
    require_once '../Clase02/clases/alumnoDAO.php';
    
    // $_REQUEST GET Y POST

    $caso = '';
    
    if( isset($_POST['caso']) )
        $caso = $_POST['caso'];
    else if( isset($_GET['caso']) )
        $caso = $_GET['caso'];

    switch( $caso ){
        case 'cargarAlumno':
            Alumno::cargarAlumno();
            break;
        case 'mostrarAlumnos':
            Alumno::mostrarAlumnos();
            break;
        default:
            echo '{"mensaje":"Debe ingresar un caso valido"}';
    }

?>