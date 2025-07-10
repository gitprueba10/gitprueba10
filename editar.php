<?php
include("conexion.php");
include("funciones.php");

if(isset($_POST["id_usuario"])) {
    $imagen = '';
    if(isset($_FILES["imagen_usuario"]) && $_FILES["imagen_usuario"]["name"] != '') {
        $imagen = subir_imagen();
        // Borra la imagen anterior si existe
        $imagen_anterior = obtener_nombre_imagen($_POST["id_usuario"]);
        if($imagen_anterior != '' && file_exists("img/" . $imagen_anterior)) {
            unlink("img/" . $imagen_anterior);
        }
    } else {
        $imagen = obtener_nombre_imagen($_POST["id_usuario"]);
    }

    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, telefono = :telefono, email = :email, imagen = :imagen WHERE id = :id_usuario");
    $resultado = $stmt->execute([
        ':nombre'    => $_POST["nombre"],
        ':apellidos' => $_POST["apellidos"],
        ':telefono'  => $_POST["telefono"],
        ':email'     => $_POST["email"],
        ':imagen'    => $imagen,
        ':id_usuario'=> $_POST["id_usuario"]
    ]);
    if($resultado){
        echo 'Registro actualizado';
    } else {
        echo 'Error al actualizar';
    }
} 