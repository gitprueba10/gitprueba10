<?php
include("conexion.php");

if(isset($_POST["id_usuario"])){
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = :id_usuario LIMIT 1");
    $stmt->execute([
        ':id_usuario' => $_POST["id_usuario"]
    ]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($resultado);
}
