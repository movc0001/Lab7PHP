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
        include "./Lab7Common/ImageFunction_Lib.php";

        $dao = new DataAccessObject(INI_FILE_PATH);
        session_start();

        if (!isset($_SESSION['user'])) {
            $_SESSION['rurl'] = "MyPictures.php";
            header("Location:Login.php");
            exit();
        }

        $user = $_SESSION["user"];
        $userId = $user->getUserId();
        $albums = $user->getAlbums();
        $commentText = $_POST['commentText'];

        extract($_POST);
        extract($_GET);

        if ($albumChangedFlag) {
            foreach ($albums as $album) {
                $currentAlbumId = $album->getAlbumId();
                if ($currentAlbumId == $selectedAlbumId) {
                    $selectedAlbum = $album;
                }
            }
            $selectedAlbumId = $selectedAlbum->getAlbumId();
            $_SESSION["selectedAlbum"] = $selectedAlbum;
            $_SESSION["selectedAlbumId"] = $selectedAlbumId;
        }
            $selectedAlbum =  $_SESSION["selectedAlbum"];

        if (isset($_SESSION['selectedAlbum']) && isset($_SESSION['selectedPicture'])) {
            $selectedAlbum = $_SESSION['selectedAlbum'];
            $selectedPicture = $_SESSION['selectedPicture'];
          
            
        }

        if (isset($selectedPictureId)) {
            $pics = $selectedAlbum->getPictures();
            foreach ($pics as $pic){
                if($pic->getPictureId() == $selectedPictureId ){
                    $selectedPicture = $pic;
                }
                
            }
            //$selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
            
            
        } else if (isset($btnComment)) {
            if (trim($commentText) != "") {
                //$comment = new Comment(null, $commentText, $user);
                
                $pictureCommentId = $selectedPicture->getPictureId();
                $dateAdded = date('Y-m-d\TH:i:s');
                $comment = new Comment($commentId, $user->getUserId(), $pictureCommentId, $commentText, $dateAdded);
                //$comment = new Comment($user->getUserId(), $pictureCommentId, $commentText, $dateAdded);
                $dao = new DataAccessObject(INI_FILE_PATH);
                $dao->saveComment($comment);
            }
        } else if (isset($selectedAlbumId)) {
//            $selectedAlbum = $albums[$selectedAlbumId];
            //$selectedAlbum = $dao->getAlbumById($selectedAlbumId);
            $numPics = $selectedAlbum->getPictures();

            if (count($numPics) == 0) {
                $noPictureMessage = "You do not have pictures in the selected album!";
            } else {
                $selectedPictureId = array_keys($selectedAlbum->getPictures())[0];
                $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
            }
        } else if (isset($action)) { //------------ needs to be done -----------------
            $selectedAlbumId = $selectedAlbum->getAlbumId();
            $selectedPictureFilePath = ALBUM_PICTURES_DIR . "/$userId/$selectedAlbumId/" . $selectedPicture->getFileName();
            $selectedThumbnailFilePath = ALBUM_THUMBNAILS_DIR . "/$userId/$selectedAlbumId/" . $selectedPicture->getFileName();
            $selectedOriginalPictureFilePath = ORIGINAL_PICTURES_DIR . "/$userId/$selectedAlbumId/" . $selectedPicture ->getFileName();

            if ($action === "Delete") {
                $dao = new DataAccessObject(INI_FILE_PATH);
                $dao->deletePicture($selectedPicture);
                $selectedAlbum->deletePicture($selectedPicture);

                unlink($selectedPictureFilePath);
                unlink($selectedThumbnailFilePath);
                unlink($selectedOriginalPictureFilePath);

                if (count($selectedAlbum->getPictures()) == 0) {
                    $noPictureMessage = "You do not have any pictures in the selected album!";
                } else {
                    $selectedPictureId = array_keys($selectedAlbum->getPictures())[0];
                    $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
                }
            } else if ($action === "RotateLeft") {
                rotateImage($selectedPictureFilePath, 90);
                rotateImage($selectedThumbnailFilePath, 90);
            } else if ($action === "RotateRight") {
                rotateImage($selectedPictureFilePath, -90);
                rotateImage($selectedThumbnailFilePath, -90);
            } else if ($action === "Download") {
                downloadFile($selectedOriginalPictureFilePath);
            }
        } else {
            if (count($albums) == 0) {
                $noAlbumMessage = "You do not have any albums yet!";
            } else {
                $noAlbumMessage = "";
                //$selectedAlbumId = array_keys($albums)[0];
                //$selectedAlbumId = $albums[0]->getAlbumId();
                //$selectedAlbum = $albums[$selectedAlbumId];
                $selectedAlbum = $albums[0];
                $selectedAlbumId =  $selectedAlbum->getAlbumId();
                
                if (count($selectedAlbum->getPictures()) == 0) {
                    $noPictureMessage = "You do not have any pictures in the selected album";
                } else {
                    $noPictureMessage = "";
                    $albumPics = $selectedAlbum->getPictures();
                    $selectedPicture = $albumPics[0];
                    $selectedPictureId =  $selectedPicture->getPictureId();
//                    $selectedPictureId = array_keys($selectedAlbum->getPictures())[0];
//                    $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
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
if ($noAlbumMessage != "") {
    ?>
                    <div class="row vertical-margin">
                        <div class="col-md-6 col-md-offset-3 text-center error"><?php print ($noAlbumMessage) ?></div>
                    </div>

    <?php
} else {
    ?>
                    <div class="row vertical-margin">
                        <div class="col-md-8">
                            <select name='selectedAlbumId' id="selectedAlbumId" class='form-control' onchange="onAlbumChange()">
    <?php
    foreach ($albums as $album) {
        $albumDisplayText = $album->getTitle() . " - Updated on - " . $album->getDate_Updated();
        $albumId = $album->getAlbumId();
        ?>
                                    <option value="<?php print $albumId ?>" 
                                    <?php
                                    //print "<option value='$albumId' " ;//. $albumId == $selectedAlbumId ? "selected" : "" . ">$albumId"; // ------- needs to be modified ------
                                    if ($albumId == $selectedAlbumId) {
                                        print 'selected';
                                    } else {
                                        print '';
                                    }
                                    ?>
                                            > 
                                    <?php print $albumDisplayText;
                                } ?>                             

                                </option>


                            </select>
                            <input type="hidden" id="albumChangedFlag" name="albumChangedFlag" value="" />
                        </div>
                    </div>

    <?php
}
if ($noPictureMessage != "") {
    ?>
                    <div class="row vertical-margin">
                        <div class="col-md-8 error"><?php print ($noPictureMessage) ?></div>
                    </div>
    <?php
} else if ($noPictureMessage == "" && $noAlbumMessage == "") {
    $selectedAlbumId = $selectedAlbum->getAlbumId();
    $selectedPictureFilePath = ALBUM_PICTURES_DIR . "/$userId/$selectedAlbumId/" . $selectedPicture->getFileName();
    ?>

                    <div class="row vertical-margin">
                        <div class="row col-md-8 text-center"><h3><?php print $selectedAlbum->getTitle(); ?></h3></div>
                    </div>

                    <div class="row vertical-margin">
                        <div class="col-md-8 text-center">
                            <div class="row vertical-margin">
                                <div class="col-lg-12 text-center img-container">
                                    <img style="width:100%" src="<?php print $selectedPictureFilePath . "?rnd=" . rand(); ?>"/>
                                    <a href="MyPictures.php?action=RotateLeft"><span style="position: absolute; left: 35%; top: 90%;" class="glyphicon glyphicon-repeat gly-flip-horizontal"></span></a>
                                    <a href="MyPictures.php?action=RotateRight"><span style="position: absolute; left: 45%; top: 90%;" class="glyphicon glyphicon-repeat"></span></a>
                                    <a href="MyPictures.php?action=Download"><span style="position: absolute; left: 55%; top: 90%;" class="glyphicon glyphicon-download-alt"></span></a>
                                    <a href="MyPictures.php?action=Delete" onclick="return confirm('This picture and all the comments to it will be deleted!');"><span style="position: absolute; left: 65%; top: 90%;" class="glyphicon-remove"></span></a>
                                </div>
                            </div>
                            <div class="row vertical-margin" style="white-space: nowrap">
                                <div class="col-md-12">
                                    <div style="overflow-x: auto; overflow-y: hidden;" id="thumbnail-bar" onscroll="onThumbnailBarScroll()">
    <?php
    $pictures = $selectedAlbum->getPictures();
    foreach ($pictures as $picture) {
        $thumbnailPath = ALBUM_THUMBNAIL_DIR . "/$userId/$selectedAlbumId/" . $picture->getFileName();
        $pictureId = $picture->getPictureId();
//                                            $rnd = rand();
        if ($selectedPictureId == $pictureId) {
            print "<button value='$pictureId' name='selectedPictureId' style='border-style: solid; border-color: blue; border-width: 3px'>";
        } else {
            print "<button value='$pictureId'  name='selectedPictureId'>";
        }
        ?>
                                            <img style="height:65px; auto" src="<?php print $thumbnailPath . "?rnd=" . rand(); ?>"/> 
                                            <?php
                                            print "</button>";
                                        }
                                        if (!isset($scrollPosition)) {
                                            $scrollPosition = 0;
                                        }
                                        $_SESSION['selectedPicture'] = $selectedPicture;
                                        $_SESSION['selectedAlbum'] = $selectedAlbum;
                                        ?> <!-- --------------------- --> 
                                        <input type="hidden" name="scrollPosition" id="scrollPosition" value="<?php print $scrollPosition; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div style="overflow-y: auto; overflow-x: hidden; text-align: left; max-height: 420px;">
    <?php
    if (trim($selectedPicture->getDescription()) != "") {
        print "<span style='font-weight:bold' >Description:</span><p>" . $selectedPicture->getDescription();
    }
//    foreach($pictures as $pic){
//        if($pic->getPictureId() == $selectedPictureId){
//            $selectPic = $pic;
//        }
//    }
    $selectPic = $dao->getPictureById($selectedPictureId);
    $allComments = $dao->getCommentsForPicture($selectPic);
    if(count($allComments) > 0){
    //if (count($selectedPicture->getComments()) > 0) {
        print "<span style='font-weight:bold' >Comments:</span><br/>";
      //  foreach ($selectedPicture->getComments() as $comment) {
        foreach ($allComments as $comment){
            $authurId = $comment->getAuthorId(); 
            $author = $dao->getUserById($authurId)->getName();
            $date = $comment->getCommentDate();
            $text = $comment->getCommentText();
            print "<p><span style='font-weight:italio; color:blue;'>$authur  ($date):</span>$text</p>";
        }
    }
    ?>
                            </div>
                            <textArea name="commentText" rows="4" style="width:100%; margin-top:2px" plceholder="Leave a comment..."></textArea>
                                <br/><br>
                                    <div class="text-right">
                                <input type="submit" value="Add Comment"  class="btn  btn-primary btn-min-width" name="btnComment" />
                                    </div>
                        </div>
                            <?php
                }
                ?>
        </form>
    </div>
    <br />
</body>
</html>
<?php
include './Lab7Common/Footer.php';
?>
          


