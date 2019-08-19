<?php
    require_once './clases/archivo.php';
    require_once './clases/alumno.php';
    require_once './clases/materia.php'; 

    $caso = '';
    
    if( isset($_POST['caso']) )
        $caso = $_POST['caso'];
    else if( isset($_GET['caso']) )
        $caso = $_GET['caso'];

    switch( $caso ){
        case 'cargarAlumno':
            Alumno::cargarAlumno();
            break;
        case 'consultarAlumno':
            Alumno::consultarAlumno();
            break;
        case 'cargarMateria':
            Materia::cargarMateria();
            break;
        case 'inscribirAlumno':
            Materia::inscribirAlumno();
            break;
        // case 'cargarAlumno':
        //     Alumno::cargarAlumno();
        //     break;
        // case 'inscripciones':
        //     Alumno::inscripciones();
        //     break;
        // case 'inscripciones':
        //     Alumno::inscripciones();
        //     break;
        default:
            echo 'Debe ingresar un caso valido';

    }
?>