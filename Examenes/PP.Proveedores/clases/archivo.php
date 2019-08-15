<?php
    class Archivo{
        public static function existePeticionPOST(){
            return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
        }
        public static function existePeticionGET(){
            return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
        }
    }
?>