<?php
include "./bbddConex.php";

$idVideoAntic = null;

if((isset($_GET['random']) && $_GET['random'] == true && isset($_GET['idVideoAntic'])) && 
((isset($_GET['like'])) || (isset($_GET['dislike'])))){

    session_start();
    $idUsuari = $_SESSION["idUser"];

    //idVideo a no repetir dos veces en el random
    $idVideoAntic = $_GET['idVideoAntic'];

    if(isset($_GET['like']) && $_GET['like'] == true){
        $operacio = "like";
    }
    else if(isset($_GET['dislike']) && $_GET['dislike'] == true){
        $operacio = "dislike";
    }
    //pasarle la operacion que es
    $rows = usuarioHaDadoLikeVideo($idVideoAntic, $idUsuari, $operacio);
    
    if($rows['countOperacions'] == 0 || (isset($rows['operacion']) && $rows['operacion'] <> $operacio)){
        SumarLikesDislikesVideo($idVideoAntic, $operacio);
        calcularPuntuacion($idVideoAntic);
    }

        $row = getRandomVideo($idVideoAntic);
        //porque es un vector se hace esto
        $row = serialize($row);
        $row = urlencode($row);
        header("Location: ../home.php?rand=true&video=$row"); //Et retorna al Login
}
?>