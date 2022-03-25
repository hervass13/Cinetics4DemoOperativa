<?php
include "./bbddConex.php";

$idVideoAntic = null;

if((isset($_GET['save']) && $_GET['save'] == true && isset($_GET['idVideoAntic'])) && ((isset($_GET['guardado'])))){

    session_start();
    $idUsuari = $_SESSION["idUser"];

    //idVideo a no repetir dos veces en el random
    $idVideoAntic = $_GET['idVideoAntic'];

    if(isset($_GET['guardado']) && $_GET['guardado'] == true){
        $operacio = "si";
    }
    //pasarle la operacion que es
    usuarioHaGuardadoVideo($idVideoAntic, $idUsuari, $operacio);
    

        $row = getVideoQueYaEstaba($idVideoAntic);
        //porque es un vector se hace esto
        $row = serialize($row);
        $row = urlencode($row);
        header("Location: ../home.php?saved=true&videoSaved=$row"); //Et retorna al Login
}
?>