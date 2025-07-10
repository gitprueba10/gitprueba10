<?php
include("conexion.php");
include("funciones.php");

if(isset($_POST["id_usuario"])){
    $imagen = obtener_nombre_imagen($_POST["id_usuario"]);
    if($imagen != ''){
        unlink("img/" . $imagen);
    }
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = :id_usuario");
    $resultado = $stmt->execute([
        ':id_usuario' => $_POST["id_usuario"]
    ]);
    if($resultado){
        echo 'Usuario borrado';
    } else {
        echo 'Error al borrar usuario';
    }
}
