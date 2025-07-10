<?php

include("conexion.php");
include("funciones.php");

if (isset($_POST["operacion"])) {
    $imagen = '';
    if (isset($_FILES["imagen_usuario"]) && $_FILES["imagen_usuario"]["name"] != '') {
        $imagen = subir_imagen();
    }
    $stmt = $conexion->prepare("INSERT INTO usuarios(nombre,apellidos,imagen,telefono,email) VALUES(:nombre, :apellidos, :imagen, :telefono, :email)");
    $resultado = $stmt->execute(
        array(
            ':nombre'     => $_POST["nombre"],
            ':apellidos'  => $_POST["apellidos"],
            ':telefono'   => $_POST["telefono"],
            ':email'      => $_POST["email"],
            ':imagen'     => $imagen
        )
    );
    if (!empty($resultado)){
        echo 'Registro creado';
    } else {
        echo 'Error al crear el registro';
    }
}