<?php
    require_once './clases/persona.php';
    require_once './clases/alumno.php';
    require_once '../Clase02/clases/alumnoDAO.php';

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
        case 'modificarAlumno':
            Alumno::modificarAlumno();
            break;
        case 'mostrarImagenes':
            Alumno::mostrarImagenes();
            break;
        default:
            echo '{"mensaje":"Debe ingresar un caso valido"}';
    }
?>