<?php

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "byarde";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "Belén";
$usuario->apellido = "Yarde Buller";
$usuario->correo = "belenyardebuller@gmail.com";
$usuario->insertar();

?>