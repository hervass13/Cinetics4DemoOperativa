<?php
$idVideoAntic=null;
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
?>

    <body class="register-page col-12 col-lg-12">

      <div class="d-flex justify-content-center col-12 col-lg-12">
        <div class="squares square1"></div>
        <div class="squares square2"></div>
        <div class="squares square3"></div>
        <div class="squares square5"></div>
        <div class="squares square6"></div>
        <div class="squares square7"></div>

        <div class="content-center brand">
        <form method="POST" action="./lib/videoBuscar.php">
                <div class="form-group">
                    <input type="text" name="hashtagS" class="form-control" placeholder="Enter a hashtag" value="" />
                </div>
                <div class="">         
                    <input type="submit" class="btn btn-primary btn-sm" name="submit" class="btnContact" value="Buscar" />
                </div>
         </form>

          <div class="carousel-inner">
            <div class="carousel-item active">
              <?php
              $row = null;
              $likeDislike = false;
              if (isset($_GET['rand']) == true && $_GET['rand'] == true) {
                //viene por la función de videoRandom.php
                $row = unserialize($_GET['video']);
                $idVideoAntic= $row['idVideo'];
              }
              else if(isset($_GET['buscar']) == true && $_GET['buscar'] == true){
                $row = unserialize($_GET['videoFiltrado']);
                $idVideoAntic= $row['idVideo'];
              }
              else if(isset($_GET['noEncontrado']) == true && $_GET['noEncontrado'] == true){
                echo '<script>alert("No se ha encontado ningún video que contenga esa etiqueta!")</script>';
                $row = getLastVideo();
                $idVideoAntic= $row['idVideo'];
              }
              else if(isset($_GET['saved']) == true && $_GET['saved'] == true){
                $row = unserialize($_GET['videoSaved']);
                $idVideoAntic= $row['idVideo'];
              }
              else {
                $row = getLastVideo();
                $idVideoAntic= $row['idVideo'];
              }

                $rowHashtags = getHashtagsVideo($row['idVideo']);
              echo "<h1 class='titulo-video col-12 col-lg-12 centrardiv' style='margin-bottom:3%;'>" . $row['titulo'] . "</h1>";?>

              <div class="text-center centrardiv">
                <fieldset class="rating">
                  <input type="radio" id="star5" name="rating" value="5" disabled="disabled"/>
                  <label class="full" for="star5"></label>

                  <input type="radio" id="star4half" name="rating" value="4.5" disabled="disabled"/>
                  <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

                  <input type="radio" id="star4" name="rating" value="4" disabled="disabled"/>
                  <label class="full" for="star4" title="Pretty good - 4 stars"></label>

                  <input type="radio" id="star3half" name="rating" value="3.5" disabled="disabled"/>
                  <label class="half" for="star3half" title="Meh - 3.5 stars">

                  </label> <input type="radio" id="star3" name="rating" value="3" disabled="disabled"/>
                  <label class="full" for="star3" title="Meh - 3 stars"></label>

                  <input type="radio" id="star2half" name="rating" value="2.5" disabled="disabled"/>
                  <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

                  <input type="radio" id="star2" name="rating" value="2" disabled="disabled"/>
                  <label class="full" for="star2" title="Kinda bad - 2 stars"></label>

                  <input type="radio" id="star1half" name="rating" value="1.5" disabled="disabled"/>
                  <label class="half" for="star1half" title="Meh - 1.5 stars"></label>

                  <input type="radio" id="star1" name="rating" value="1" disabled="disabled"/>
                  <label class="full" for="star1" title="Sucks big time - 1 star"></label>

                  <input type="radio" id="starhalf" name="rating" value="0.5" disabled="disabled"/>
                  <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                </fieldset>
              </div>

                <?php
                    $rowPuntuacio = selectPuntuacioVideo($row['idVideo']);
                    echo '<script language="javascript" src="../js/valoracionStars.js"></script>';
                    echo "<script> var puntuacioJs = '$rowPuntuacio'</script>";
                    echo '<script>starsValoration(puntuacioJs);</script>';
                ?>

              <div>
                <?php echo "<video class='video-home col-12 col-lg-12 centrardiv' src='videos/" . $row['path'] . "'controls autoplay> </video>"; ?>
              </div>

              <div class='col-12 col-lg-12 centrardiv'>

              </div>
              
              <div class="col-12 col-lg-12 centrardiv" style="margin-top: 2%">
                <?php echo "<a class='col-4 col-lg-6 centrardiv hoverSinLike' id='liked' href='./lib/videoRandom.php?random=true&idVideoAntic=$idVideoAntic&like=true'>"?>
                  <i onclick="myFunction(this)" class="fa fa-thumbs-up fa-2x"></i>
                  <?php $rowLikes = $row['likes'];
                    echo "<div><br><br>$rowLikes</div>";?>
                  <span aria-hidden="true" role="button" data-slide="prev"></span>
                  <span class="sr-only">Previous</span>
                <?php echo "</a>";
                ?>

                <?php 
                  $rowUsuari = selectUsuariVideo($row["idUsuari"]);
                  echo "<p class='dataPenjat col-5' style='text-align:center;'>" . $row['date'] . ' by ' . $rowUsuari . "</p>"; ?>

                <?php echo "<a id='dislike' class='col-4 col-lg-6 centrardiv hoverSinLike' href='./lib/videoRandom.php?random=true&idVideoAntic=$idVideoAntic&dislike=true'>"?>
                  <i onclick="myFunction(this)" id="dilike1" class="fas fa-thumbs-down fa-2x"></i>
                    <?php $rowDislikes = $row['dislikes'];
                    echo "<div><br><br>$rowDislikes</div>";?>
                  <span aria-hidden="true" role="button" data-slide="next"></span>
                  <span class="sr-only">Next</span>
                <?php echo "</a>";?>
              </div>

              <?php echo "<a id='save' class='col-4 col-lg-6 centrardiv hoverSinLike' style='margin-left: 25%;' href='./lib/videoSaved.php?save=true&idVideoAntic=$idVideoAntic&guardado=true'>"?>
                  <i onclick="myFunction(this)" class="bi bi-bookmarks-fill fa-2x"></i>
                <?php echo "</a>";?>
              </div>

              <?php
                $rows = mirarLikesDislikesVideo($row['idVideo'], $_SESSION['idUser']);
                  if($rows > 0){
                      if($rows['operacion'] == "like"){
                          echo '<script language="javascript" src="../js/likeDislike.js"></script>';
                          echo '<script>like();</script>';
                        }
                      else if($rows['operacion'] == "dislike"){
                          echo '<script language="javascript" src="../js/likeDislike.js"></script>';
                          echo '<script>dislike();</script>';
                        }
                      }
                      else{
                         echo '<script language="javascript" src="../js/likeDislike.js"></script>';
                         echo '<script>likeDislike();</script>';
                        }

                        $rows = mirarGuardadoVideo($row['idVideo'], $_SESSION['idUser']);
                        if($rows > 0){
                            if($rows['guardado'] == "si"){
                                echo '<script language="javascript" src="../js/guardado.js"></script>';
                                echo '<script>guardat();</script>';
                              }
                            }
                            else{
                               echo '<script language="javascript" src="../js/guardado.js"></script>';
                               echo '<script>noGuardat();</script>';
                              }

              echo "<br>";
              echo "<div class='col-12 col-lg-12 centrardiv'>";
              foreach ($rowHashtags as $rowHashtag) {
                echo "<span class='badge-home badge-default-home centrardiv'>"  . $rowHashtag . "  </span>";
              }
              echo "</div>";
              ?>

              <div class="centrardiv" style="margin: 2%">
                <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" ?>Información</button>
              </div>

            </div>
          </div>
        </div>
      </div>


      <?php
      $activated = isset($_GET['activated']) ? $_GET['activated'] : false;

      if (isset($activated) && $activated == true) {
        echo "<script>alert('Your account has been activated');</script>";
      }
      include "./Includes/footer.php"; ?>

  <?php
  }
} ?>
    </body>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <?php echo "<h2 style='color:violet'>" . $row['titulo'] . "</h2>"; ?>
          </div>
          <div class="modal-body">
            <h4 style="color: violet;">Descripcion</h4>
            <?php echo "<area class='descripcio-home'>" . $row['descripcio'] . "</area>";
            echo "<hr>";
            foreach ($rowHashtags as $rowHashtag) {
              echo "<span class='badge-home badge-default-home'>" . $rowHashtag . " </span>";
            }?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary"  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>