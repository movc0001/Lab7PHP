<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>My Pictures</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    </head>

    <body>

        <?php
        include "./Lab7Common/Header.php";
        include_once "./Lab7Common/EntityClass_Lib.php";
        include_once "./Lab7Common/DataAccessClass_Lib.php";
        include "./Lab7Common/Function_Lib.php";
        include "./Lab7Common/Constants.php";

        //$dao = new DataAccessObject(INI_FILE_PATH);
        session_start();
        
        if (!isset($_SESSION['user'])) {
        $_SESSION['rurl'] = "MyPictures.php";
        header("Location:Login.php");
        exit();
        }
        
        $user = $_SESSION["user"];
        $userId = $user->getUserId();
        $album = $user->getAlbums();
        
        extract($_POST);
        extract($_GET);
        
        if(isset($_SESSION['selectedAlbum']) && isset($_SESSION['selectedPicture']))
        {
            $selectedAlbum = $_SESSION['selectedAlbum'];
            $selectedPicture = $_SESSION['selectedPicture'];
        }
        
        if(isset($selectedPictureId))
        {
            $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
        }
        else if(isset ($btnComment))
        {
            if(trim($commentText) != "")
            {
                $comment = new Comment(null,$commentText, $user);
                $dao = new DataAccessObject(INI_FILE_PATH);
                $dao->saveComment($selectedPicture, $comment);
            }
        }
        else if(isset ($selectedAlbumId))
        {
            $selectedAlbum = $albums[$selectedAlbumId];
            
            if(sizeof($selectedAlbum->getPictures() == 0))
            {
                $noPictureMessage = "You do not have pictures in the selected album!";
            }
            else
            {
                $selectedPictureId = array_keys($selectedAlbum->getPictures())[0];
                $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
            }
        }
        else if(isset($action)) //------------ needs to be done -----------------
        {
            $selectedAlbumId = $selectedAlbum->getAlbum();
            $selectedPictureFilePath= ALBUM_PICTURES_DIR."/$userId/$selectedAlbumId/".$selectedPicture->getFileName();
            $selectedThumbnailFilePath = ALBUM_THUMBNAILS_DIR."/$userId/$selectedAlbumId/".$selectePicture->getFileName();
            $selectedOriginalPictureFilePath = ORIGINAL_PICTURES_DIR."/$userId/$selectedAlbumId/".$selectedPicture;
                    
            if($action === "Delete")
            {
                $dao = new DataAccessObject(INI_FILE_PATH);
                $dao->deletePicture($selectedPicture);
                $selectedAlbum->deletePicture($selectedPicture);
                
                unlink($selectedPictureFilePath);
                unlink($selectedThumbnailFilePath);
                unlink($selectedOriginalPictureFilePath);
                
                if(sizeof($selectedAlbum->getPictures() == 0))
                {
                    $noPictureMessage  = "You do not have any pictures in the selected album!";
                }
                else
                {
                    $selectePictureId = array_keys($selectedAlbum->getPictures())[0];
                    $selectedPicture = $selectedAlbum->getPictures()[$selectePictureId]; 
                }  
            }
            else if ($action === "RotateLeft")
            {
                rotateImage($selectedPictureFilePath, 90);
                rotateImage($selectedThumbnailFilePath, 90);
            }
            
             else if ($action === "RotateRight")
            {
                rotateImage($selectedPictureFilePath, -90);
                rotateImage($selectedThumbnailFilePath, -90);
            }
            else if($action === "Download")
            {
                downloadFile($selectedOriginalPictureFilePath);
            }
        }
        else
        {
            if(sizeof($albums == 0)
            {
                $noAlbumMessage = "You do not have any albums yet!";
            }
            else
            {
                $noAlbumMessage = "";
                $selectedAlbumId = array_keys($albums)[0];
                $selectedAlbum = $albums[$selectedAlbumId];
                if(sizeof($selectedAlbum->getPictures() == 0))
                {
                    $noPictureMessage = "You do not have any pictures in the selected album";
                }
                else
                {
                    $noPictureMessage = "";
                    $selectedPictureId = array_keys($selectedAlbum->getPictures())[0];
                    $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
                }
            }  
        } 
        ?>

  <div class="container">
      <form class="form-horizontal" method="post" id="picture-form" action="MyPictures.php">
            <div class="row vertical-margin text-center">
                <h2>My Pictures</h2>
            </div>
          <?php 
          if($noAlbumMessage != "")
          {
              ?>
          <div class="row vertical-margin">
              <div class="col-md-6 col-md-offset-3 text-center error"><?php print ($noAlbumMessage) ?></div>
          </div>
          
          <?php 
          }
          else
          {
          ?>
          <div class="row vertical-margin">
              <div class="col-md-8">
                  <select name='selectedAlbumId' class='form-control' onchange="onAlbumChange()">
                      <?php
                      foreach($albums as $album)
                      {
                          $albumDisplayText = $album;
                          $albumId = $album->getAlbumId();
                          print "<option value='$albumId' ".($albumId == $selectedAlbumId ? "selected" : ""). ">$album"; // ------- needs to be modified ------
                      }
                      ?>
                  </select>
              </div>
          </div>
          
          <?php
          }
          if($noPictureMessage != "")
          {
          ?>
          <div class="row vertical-margin">
              <div class="col-md-8 error"><?php print ($noPictureMessage) ?></div>
          </div>
 <?php
          }
          else if ($noPictureMessage == "" && $noAlbumMessage == "")
          {
              $selectedAlbumId = $selectedAlbum->getAlbumId();
              $selectedPictureFilePath = ALBUM_PICTURES_DIR."/$userId/$selectedAlbumId"";
              
?>

<div class="row vertical-margin">
          }
            
            
