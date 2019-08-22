<?php
    require_once './clases/archivo.php';
    require_once './clases/alumno.php';
    require_once './clases/materia.php'; 
    require_once './clases/inscripcion.php';

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
            Inscripcion::inscribirAlumno();
            break;
        case 'modificarAlumno':
            Alumno::modificarAlumno();
            break;
        case 'inscripciones':
            Inscripcion::inscripciones();
            break;
        case 'inscripcionesParametro':
            Inscripcion::inscripcionesParametro();
            break;
        case 'alumnos':
             Alumno::alumnos();
             break;
        default:
            echo 'Debe ingresar un caso valido';

    }
?>