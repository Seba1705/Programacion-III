<?php
    class Alumno{
        public $nombre;
        public $apellido;
        public $email;
        public $foto;

        public function __construct( $nombre, $apellido, $email, $foto ){
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->foto = $foto;
        }
    
    
        //1-(2pt.) caso: cargarAlumno (post): Se debenguardar lossiguientes datos: nombre, apellido, email y foto. Losdatos se guardan en el archivo de texto alumnos.txt, tomando el emailcomo identificador.
        public static function cargarAlumno(){
            
        }
    }
?>