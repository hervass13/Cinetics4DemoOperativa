<?php
$idVideoAntic = null;
$cont=0;
if (!isset($_COOKIE[session_name()])) {
  header("Location: ./index.php");
  exit;
} else {
  session_start();

  if (!isset($_SESSION['username']) && !isset($_SESSION['mail'])) {
    header("Location: ./lib/log-out.php");
    exit;
  } else {
    include "Includes/header.php";
    include "lib/bbddConex.php";
  }
}
?>

<body class="register-page col-12 col-lg-12">

  <div class="d-flex justify-content-center col-12 col-lg-12">
    <div class="squares square1"></div>
    <div class="squares square2"></div>
    <div class="squares square3"></div>
    <div class="squares square5"></div>
    <div class="squares square6"></div>
    <div class="squares square7"></div>

    <div class="col-lg-12 col-md-12 col-12" id="tengoHambre">

      <?php

      $videosUsu = getPopularVideo();

      foreach ($videosUsu as $videosUsumini) {

        $rowHashtags = getHashtagsVideo($videosUsumini['idVideo']); /* Obtener los hashtags */

        echo "<div class='col-12 col-md-4 col-lg-3' id='tengoHambre2' style='margin-top: 3%'>";

          echo "<h1 class='titulo-video2 col-12 col-lg-12'>" . $videosUsumini['titulo'] . "</h1>";

          echo "<fieldset class='rating' style='font-size: .5em; margin-right: 25%'>

              <input type='radio' id='star5$cont' name='rating$cont' value='5' disabled='disabled'/>
              <label class='full' for='star5'></label>

              <input type='radio' id='star4half$cont' name='rating$cont' value='4.5' disabled='disabled'/>
              <label class='half' for='star4half' title='Pretty good - 4.5 stars'></label>

              <input type='radio' id='star4$cont' name='rating$cont' value='4' disabled='disabled'/>
              <label class='full' for='star4' title='Pretty good - 4 stars'></label>

              <input type='radio' id='star3half$cont' name='rating$cont' value='3.5' disabled='disabled'/>
              <label class='half' for='star3half' title='Meh - 3.5 stars'>

              </label> <input type='radio' id='star3$cont' name='rating$cont' value='3' disabled='disabled'/>
              <label class='full' for='star3' title='Meh - 3 stars'></label>

              <input type='radio' id='star2half$cont' name='rating$cont' value='2.5' disabled='disabled'/>
              <label class='half' for='star2half' title='Kinda bad - 2.5 stars'></label>

              <input type='radio' id='star2$cont' name='rating$cont' value='2' disabled='disabled'/>
              <label class='full' for='star2' title='Kinda bad - 2 stars'></label>

              <input type='radio' id='star1half$cont' name='rating$cont' value='1.5' disabled='disabled'/>
              <label class='half' for='star1half' title='Meh - 1.5 stars'></label>

              <input type='radio' id='star1$cont' name='rating$cont' value='1' disabled='disabled'/>
              <label class='full' for='star1' title='Sucks big time - 1 star'></label>

              <input type='radio' id='starhalf$cont' name='rating$cont' value='0.5' disabled='disabled'/>
              <label class='half'for='starhalf' title='Sucks big time - 0.5 stars'></label>
            </fieldset>";
          $rowPuntuacio = selectPuntuacioVideo($videosUsumini['idVideo']);
          echo '<script language="javascript" src="../js/valoracionStars.js"></script>';
          echo "<script> var puntuacioJs = '$rowPuntuacio'</script>";
          echo "<script> var cont = '$cont'</script>";
          echo '<script>starsValorationGeneral(puntuacioJs, cont);</script>';
          //para que me ponga la valoracion en cada grupo de estrellas
          $cont++;

          echo "<div id='tengoHambre3'><video id='video-lista' src='videos/" . $videosUsumini['path'] . "' width='250' height='300'  controls loop autoplay muted></video></div>";

          echo "<div class='col-12 col-lg-12 col-md-12' style='display: flex;'>";

          echo "<div class='col-4 col-lg-4 col-md-4' style='margin-left: 10%;'><i class='fa fa-thumbs-up fa-2x likeDonat'></i>";
          $rowLikes = $videosUsumini['likes'];
          echo "<p>$rowLikes</p></div>";
          
          echo "<div class='col-4 col-lg-4 col-md-4' style='margin-left: 28%;'><i class='fas fa-thumbs-down fa-2x likeDonat'></i>";
          $rowdislikes = $videosUsumini['dislikes'];
          echo "<p>$rowdislikes</p></div>";

          echo "</div>";

          echo "<div class='col-12 col-lg-12 centrardiv'>";
          foreach ($rowHashtags as $rowHashtag) {
            echo "<span class='badge-home badge-default-home centrardiv'>"  . $rowHashtag . "  </span>";
          }
          echo "</div>";

        echo "</div>";
      }
      ?>

    </div>
  </div>
</body>