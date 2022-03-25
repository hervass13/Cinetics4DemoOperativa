<?php
include "./bbddConex.php";

$idVideoAntic = null;

if(isset($_GET['eliminar']) && $_GET['eliminar'] == true && isset($_GET['idVideoElimina'])){

    session_start();
    $idUsuari = $_SESSION["idUser"];

    //idVideo a no repetir dos veces en el random
    $videoEliminar = $_GET['idVideoElimina'];

    //pasarle la operacion que es
    eliminarVideoUsu($videoEliminar, $idUsuari);

    header("Location: ../homeUserLogineado2.php"); //Et retorna al Login
}
?>