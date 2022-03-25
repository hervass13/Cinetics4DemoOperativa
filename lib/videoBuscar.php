<?php
include "./bbddConex.php";

    if (isset($_POST["hashtagS"]) && !empty($_POST["hashtagS"]))
    {
        $hashtag = filter_input(INPUT_POST, "hashtagS");
    }

    $row = getIdVideoFiltrado($hashtag);
    //porque es un vector se hace esto
    $row = serialize($row);
    $row = urlencode($row);
    if($row <> "N%3B")
    {
        header("Location: ../home.php?buscar=true&videoFiltrado=$row"); //Et retorna al Login
    }else{
        header("Location: ../home.php?noEncontrado=true");
    }


?>