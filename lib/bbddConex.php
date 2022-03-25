<?php 
    include "mail.php";
    require_once "config.php";

    function openDB(){
        $cadenaConnexio = CADENABD;
        $usuari = USUARIOBD;
        $passwd = PASSWDBD;
        $db = false;
        try{
            //Ens connectem a la BDs
            $db = new PDO($cadenaConnexio, $usuari, $passwd);
            //Tallem la connexió a la BDs
            //$db = null;
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
        return $db;
    }
    ///////////////////////////********************************************////////////////////////////
    ///////////////////////////FUNCIONES USUARIO////////////////////////////
    ///////////////////////////********************************************////////////////////////////
    ///////////////////////////********************************************////////////////////////////
    function crearUsuario($email, $username, $password, $firstName, $lastName, $activationCode){
        $db = openDB();
    
        ///Con esta sintaxis el orden da igual
        $sql = 'INSERT INTO `users`(mail,username,passHash,userFirstName,userLastName,creationDate,removeDate,lastSignIn, 
        activationDate,activationCode,resetPassExpiry,resetPassCode,active) 
        VALUES(:email,:username,:passwordx,:firstname,:lastname, NOW(), null, null,null,:activationcode,null,null, 0)';
            $usuaris = $db->prepare($sql);
            $usuaris->execute(array(':email'=>$email, ':username'=>$username, ':passwordx'=>$password, 
            ':firstname'=>$firstName, ':lastname'=>$lastName, ':activationcode'=>$activationCode));
    
            enviarMailActivacio($email, $activationCode);
    }

    //no funciona
    function insertResetPassCode($resetPassCode, $email){
        $db = openDB();
        $sql = "UPDATE `users` SET resetPassCode = '$resetPassCode' WHERE mail = ?";
        $result = $db->prepare($sql);
        $result->execute(array($email));
    }

    function obtenirUsuari($username){
        $db = openDB();
        $sql = 'SELECT id, username, mail, passHash FROM `users` WHERE (`username` = ? OR `mail` = ?) and active = 1';
        $usuaris = $db->prepare($sql);
        $usuaris->execute(array($username, $username));

        return $usuaris;
    }

    function comptarUsuariNoActiu($activacionCode, $mail){
        
    $db = openDB(); 
    $sql = "SELECT count(*) FROM users WHERE mail = ?  AND activationCode = ? AND active = 0";
    $result = $db->prepare($sql);
    $result->execute(array($mail, $activacionCode));
    $rows = $result->fetchColumn();

    return $rows;
    }

    function comptarUsuariResetPassword($resetCode, $mail){
        
        $db = openDB(); 
        $sql = "SELECT count(*) FROM users WHERE mail = ?  AND resetPassCode = ?";
        $result = $db->prepare($sql);
        $result->execute(array($mail, $resetCode));
        $rows = $result->fetchColumn();
    
        return $rows;
    }

    function activarUsuari($mail){

        $db = openDB();
        $sql = "UPDATE users SET active = 1, activationCode = null, activationDate = now() WHERE mail = ?";
        $result = $db->prepare($sql);
        $result->execute(array($mail));
    }

    function updateLastSingIn($xUsername){
    $db = openDB();
    $sql = "UPDATE `users` SET lastSignIn = now() WHERE `username` = ?";
    $result = $db->prepare($sql);
    $result->execute(array($xUsername));
    }

    ///////////////////////////********************************************////////////////////////////
    ///////////////////////////FUNCIONES RESET PASSWORD////////////////////////////
    ///////////////////////////********************************************////////////////////////////
    ///////////////////////////********************************************////////////////////////////
    function resetPassword($password, $mail, $resetCode){
        $db = openDB();
        $sql = "UPDATE `users` SET passHash = '$password', resetPassExpiry = null, resetPassCode = null WHERE `mail` = ? and resetPassCode = ?";
        $result = $db->prepare($sql);
        $result->execute(array($mail, $resetCode));
    }

    function validacioPasswordTempsExpirat($email, $passCodeReset){
        $db = openDB(); //Abre la BBDD;
        $sql = "UPDATE `users` SET resetPassExpiry = ADDTIME(now(), 3000) WHERE mail = ? and resetPassCode = ?";
        $result = $db->prepare($sql);
        $result->execute(array($email, $passCodeReset));
    }

    function hashExpirat($email, $passCodeReset){
        $expirat = false;

        $db = openDB();

        $fechaActual = getdate();
        $fechaExpiracio = "SELECT resetPassExpiry FROM `users` WHERE mail = ? and resetPassCode = ?";
        $result = $db->prepare($fechaExpiracio);
        $result->execute(array($email, $passCodeReset));

        if($fechaActual >= $result)
        {
            $expirat = true;
        }

        return $expirat;
    }

    ///////////////////////////********************************************////////////////////////////
    ///////////////////////////FUNCIONES VIDEOS////////////////////////////
    ///////////////////////////********************************************////////////////////////////
    ///////////////////////////********************************************////////////////////////////
        
    function insertarVideo($titol, $hashtagArray, $descripcio, $path, $idUsuari){
        $db = openDB();
        $etiquetaVideo= null;
        ///Con esta sintaxis el orden da igual
        //INSERT ETIQUETA
        for($i = 0; $i < count($hashtagArray); $i++){
            $sql = 'INSERT INTO `etiqueta`(nombre)
            VALUES(:nombre)';
                $usuaris = $db->prepare($sql);
                $usuaris->execute(array(':nombre'=>$hashtagArray[$i]));
        }
        //INSERT VIDEO
        $sql = 'INSERT INTO `video`(titulo,descripcio,likes,dislikes,`path`,`date`,idUsuari) 
                    VALUES(:titulo,:descripcio,0,0, :path, CURRENT_DATE(), :idUsuari)';
            $usuaris = $db->prepare($sql);
            $usuaris->execute(array(':titulo'=>$titol, ':descripcio'=>$descripcio, ':path'=>$path, ':idUsuari'=>$idUsuari));
            $idVideo = $db->lastInsertId();
        //INSERTVIDEOETIQUETA

            for($i = 0; $i < count($hashtagArray); $i++){

                $idEtiquetas = obtenirIdBe($hashtagArray[$i]);
                
                foreach ($idEtiquetas as $fila) {
                    $etiquetaVideo = $fila['idEtiqueta'];
                }
                
                $sql = 'INSERT INTO `videoetiqueta`(idEtiqueta, idVideo) VALUES(:idEtiqueta, :idVideo)';
                    $usuaris = $db->prepare($sql);
                    $usuaris->execute(array(':idEtiqueta'=>$etiquetaVideo, ':idVideo'=>$idVideo));
            }
    }

    function obtenirIdBe($hashtagArrayParaula){
        $db = openDB();
        
        $sql = 'SELECT idEtiqueta FROM `etiqueta` WHERE nombre = ?';
        $usuaris = $db->prepare($sql);
        $usuaris->execute(array($hashtagArrayParaula));

        return $usuaris;
    }

    function getLastVideo(){
        $db = openDB();
        
        $sql = 'SELECT * FROM `video` ORDER BY idVideo DESC LIMIT 1';
        $video = $db->prepare($sql);
        $video->execute();

        $data = $video;
        foreach ($data as $row) :
            return $row;
        endforeach; 
    }
    

    ///esta vale para los dos
    function getHashtagsVideo($xlastIdVideo)
    {
        $db = openDB();
        $hastag=null;
        $sql = 'SELECT e.nombre FROM `etiqueta` as `e` INNER JOIN `videoetiqueta` as `ve` ON e.idEtiqueta = ve.idEtiqueta WHERE ve.idVideo = ?';
        $video = $db->prepare($sql);
        $video->execute(array($xlastIdVideo));
        $data = $video;
        $i = 0;

        foreach($data as $row) :
            $hastag[$i] = $row['nombre'];
            $i++;
        endforeach;

        return $hastag;
    }

    function getRandomVideo($idVideoNoRepetir)
    {
        $db = openDB();
        $existe = null;
        //agafar el id minim de la bbdd
        $minIdVideo = getMinIdVideo();
        $maxIdVideo = getMaxIdVideo();

        $random = rand($minIdVideo, $maxIdVideo);
        //por si
        $existeRow = existeRandom($random);

        if($existeRow > 0){
            $existe = true;
        }
        else $existe = false;

        while($random == $idVideoNoRepetir || $existe == false){
            $random = rand($minIdVideo, $maxIdVideo);
            $existeRow = existeRandom($random);
            if($existeRow > 0){
                $existe = true;
            }
            else $existe = false;
        }
        
        $sql = 'SELECT * FROM `video` WHERE idVideo = ?';
        $video = $db->prepare($sql);
        $video->execute(array($random));

        $data = $video;
        foreach ($data as $row) :
            return $row;
        endforeach; 
    }
    
    function getMinIdVideo(){
        $db = openDB();
    
        $sql = 'SELECT min(idVideo) FROM `video`';
        $resultat = $db->prepare($sql);
        $resultat->execute(array());
        $rows = $resultat->fetchColumn();

        return $rows;
    }

    function getMaxIdVideo(){
        $db = openDB();
    
        $sql = 'SELECT max(idVideo) FROM `video`';
        $resultat = $db->prepare($sql);
        $resultat->execute(array());
        $rows = $resultat->fetchColumn();

        return $rows;
    }

    function existeRandom($randomId){
        $db = openDB();
    
        $sql = 'SELECT count(*) FROM `video` WHERE idVideo = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($randomId));
        $rows = $resultat->fetchColumn();

        return $rows;
    }

    function selectUsuariVideo($idUser){

        $db = openDB();
    
        $sql = 'SELECT username FROM `users` WHERE id = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idUser));
        $rows = $resultat->fetchColumn();

        return $rows;
    }

    function SumarLikesDislikesVideo($idVideo, $operacio){
        $db = openDB(); //Abre la BBDD;
        if($operacio == "like"){
            $sql = "UPDATE `video` SET likes = likes + 1 WHERE idVideo = ?";
        }
        else if($operacio == "dislike"){
            $sql = "UPDATE `video` SET dislikes = dislikes + 1 WHERE idVideo = ?";
        }
        if($sql != null){
            $result = $db->prepare($sql);
            $result->execute(array($idVideo));
        }
    }

    function restarLikesDislikesVideo($idVideo, $operacion){
        $db = openDB(); //Abre la BBDD;
        if($operacion == "like"){
            $sql = "UPDATE `video` SET dislikes = dislikes - 1 WHERE idVideo = ?";
        }
        else if($operacion == "dislike"){
            $sql = "UPDATE `video` SET likes = likes - 1 WHERE idVideo = ?";
        }
        if($sql != null){
            $result = $db->prepare($sql);
            $result->execute(array($idVideo));
        }

    }
    function CalcularPuntuacion($idVideo){
        $db = openDB(); //Abre la BBDD;
        $sql = "UPDATE `video` SET puntuacio = likes / (likes+dislikes) WHERE idVideo = ?";
        $result = $db->prepare($sql);
        $result->execute(array($idVideo));
    }

    function usuarioHaDadoLikeVideo($idVideo, $idUser, $newoperacion){
        $db = openDB();

        $sql = "SELECT count(*) as countOperacions, operacion FROM `operaciones` WHERE idVideo = ?  AND idUsuari = ?";

        $result = $db->prepare($sql);
        $result->execute(array($idVideo, $idUser));
        //$rows = $result->fetchColumn();
        $data = $result;
        foreach ($data as $row) :
            $row = $row;
        endforeach; 
        //si la operacion que ya estaba insertada es diferente a la que se intenta poner:
        if($row['countOperacions'] > 0 && $row['operacion'] <> $newoperacion){

            restarLikesDislikesVideo($idVideo, $newoperacion);
            eliminarViejaOperacion($idVideo, $idUser, $row['operacion']);
            insertarNuevaOperacion($idVideo, $idUser, $newoperacion);
        }
        //no hay operaciones insertadas:
        if($row['countOperacions'] == 0){
            insertarNuevaOperacion($idVideo, $idUser, $newoperacion);
        }
        //si la operacion que intentas poner ya estaba insertada no se hace nada

        return $row;
    }

    function insertarNuevaOperacion($idVideo, $idUser, $newoperacion)
    {
        $db = openDB();

        $sql = 'INSERT INTO `operaciones`(idVideo, idUsuari, operacion) VALUES(:idVideo, :idUser, :operacio)';
        $resultat = $db->prepare($sql);
        $resultat->execute(array(':idVideo'=>$idVideo, ':idUser'=>$idUser, ':operacio'=>$newoperacion));
    }

    function eliminarViejaOperacion($idVideo, $idUser, $operacionVieja)
    {
        $db = openDB();

        $sql = 'DELETE FROM `operaciones` WHERE idVideo = ? and idUsuari = ? and operacion = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo, $idUser, $operacionVieja));
    }

    function mirarLikesDislikesVideo($idVideo, $idUser){

        $db = openDB();

        $sql = 'SELECT * FROM `operaciones` WHERE idVideo = ? and idUsuari = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo, $idUser));

        $data = $resultat;
        foreach ($data as $row) :
            return $row;
        endforeach; 
    }

    function selectPuntuacioVideo($idVideo){

        $db = openDB();

        $sql = 'SELECT puntuacio FROM `video` WHERE idVideo = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo));
        $rows = $resultat->fetchColumn();

        return $rows;
    }

    function selectUserVideos($idUser){
        $db = openDB();

        $sql = 'SELECT * FROM `video` WHERE idUsuari = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idUser));
        $videos=null;
        $i = 0;

        foreach($resultat as $row) :
            $videos[$i] = $row;
            $i++;
        endforeach;

        return $videos;
    }
    function eliminarVideoUsu($idVideo, $idUser){
        $db = openDB();

        $sql = 'DELETE FROM `video` WHERE idVideo = ? and idUsuari = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo, $idUser));
    }

    function getIdVideoFiltrado($hashtagFirst)
    {
        $db = openDB();
        $idHashtag = getIdHashtagFiltrado($hashtagFirst);

        $sql = 'SELECT idVideo FROM `videoetiqueta` WHERE idEtiqueta = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idHashtag));
        $rowIdVideo = $resultat->fetchColumn();

        //devuelve el video
        $rowVideoBuscar = getVideoFiltradoHashtag($rowIdVideo);

        return $rowVideoBuscar;
    }

    function getVideoFiltradoHashtag($idVideo){
        $db = openDB();

        $sql = 'SELECT * FROM `video` WHERE idVideo = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo));

        $data = $resultat;
        foreach ($data as $row) :
            return $row;
        endforeach; 
    }
    function getIdHashtagFiltrado($hashtag){
        $db = openDB();

        $sql = "SELECT idEtiqueta FROM `etiqueta` WHERE nombre LIKE '%' :nombre '%' ";
        $resultat = $db->prepare($sql);
        $resultat->execute(array(':nombre'=>$hashtag));

        $rows = $resultat->fetchColumn();

        return $rows;
    }


    function getPopularVideo(){
        $db = openDB();

        $sql = 'SELECT * FROM `video` ORDER BY puntuacio DESC LIMIT 4';
        $resultat = $db->prepare($sql);
        $resultat->execute(array());
        $videos=null;
        $i = 0;

        foreach($resultat as $row) :
            $videos[$i] = $row;
            $i++;
        endforeach;

        return $videos;
    }

    function inserirOpVideoGuardado($idVideo, $idUser, $newoperacion)
    {
        $db = openDB();

        $sql = 'INSERT INTO `VideoGuardado`(idVideo, idUsuari, guardado) VALUES(:idVideo, :idUser, :guardat)';
        $resultat = $db->prepare($sql);
        $resultat->execute(array(':idVideo'=>$idVideo, ':idUser'=>$idUser, ':guardat'=>$newoperacion));
    }

    function eliminarOpVideoGuardado($idVideo, $idUser, $operacionVieja)
    {
        $db = openDB();

        $sql = 'DELETE FROM `VideoGuardado` WHERE idVideo = ? and idUsuari = ? and guardado = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo, $idUser, $operacionVieja));
    }

    function usuarioHaGuardadoVideo($idVideo, $idUser, $newoperacion){
        $db = openDB();

        $sql = "SELECT count(*) as countOperacions FROM `VideoGuardado` WHERE idVideo = ?  AND idUsuari = ? and guardado = ?";

        $result = $db->prepare($sql);
        $result->execute(array($idVideo, $idUser, $newoperacion));
        //$rows = $result->fetchColumn();
        $data = $result;
        foreach ($data as $row) :
            $row = $row;
        endforeach; 
        //si la operacion que ya estaba insertada es diferente a la que se intenta poner:
        if($row['countOperacions'] > 0){

            eliminarOpVideoGuardado($idVideo, $idUser, $newoperacion);
        }
        //no hay operaciones insertadas:
        if($row['countOperacions'] == 0){
            inserirOpVideoGuardado($idVideo, $idUser, $newoperacion);
        }
    }

    function idVideoSaved($idUsu)
    {
        $db = openDB();
        
        $sql = 'SELECT idVideo FROM `VideoGuardado` WHERE idUsuari = ?';
            $result = $db->prepare($sql);
            $result->execute(array($idUsu));

        return $result;

    }

    function getSavedVideos($idUsu)
    {
        $db = openDB();

        $idVideosGuardados = idVideoSaved($idUsu);
        $idVideoGuardado=null;
        $j = 0;
        $i = 0;
        $videos=null;
        
        foreach ($idVideosGuardados as $fila) {
            $idVideoGuardado = $fila['idVideo'];
        
        $sql = 'SELECT * FROM `video` WHERE idVideo = ?';
            $resultat = $db->prepare($sql);
            $resultat->execute(array($idVideoGuardado));
            $j++;
    
            foreach($resultat as $row) :
                $videos[$i] = $row;
                $i++;
            endforeach;
        }
    
            return $videos;
    }

    function getVideoQueYaEstaba($idVideo)
    {
        $db = openDB();

        $sql = 'SELECT * FROM `video` WHERE idVideo = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo));

        $data = $resultat;
        foreach ($data as $row) :
            $row = $row;
        endforeach; 

        return $row;
    }

    function mirarGuardadoVideo($idVideo, $idUser){

        $db = openDB();

        $sql = 'SELECT * FROM `videoguardado` WHERE idVideo = ? and idUsuari = ?';
        $resultat = $db->prepare($sql);
        $resultat->execute(array($idVideo, $idUser));

        $data = $resultat;
        foreach ($data as $row) :
            return $row;
        endforeach; 
    }

    
?>