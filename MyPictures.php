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

        if (isset($_SESSION['selectedAlbum']) && isset($_SESSION['selectedPicture'])) {
            $selectedAlbum = $_SESSION['selectedAlbum'];
            $selectedPicture = $_SESSION['selectedPicture'];
        }

        if (isset($selectedPictureId)) {
            $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
        } else if (isset($btnComment)) {
            if (trim($commentText) != "") {
                $comment = new Comment(null, $commentText, $user);
                $dao = new DataAccessObject(INI_FILE_PATH);
                $dao->saveComment($selectedPicture, $comment);
            }
        } else if (isset($selectedAlbumId)) {
            $selectedAlbum = $albums[$selectedAlbumId];

            if (sizeof($selectedAlbum->getPictures() == 0)) {
                $noPictureMessage = "You do not have pictures in the selected album!";
            } else {
                $selectedPictureId = array_keys($selectedAlbum->getPictures())[0];
                $selectedPicture = $selectedAlbum->getPictures()[$selectedPictureId];
            }
        } else if (isset($action)) { //------------ needs to be done -----------------
            $selectedAlbumId = $selectedAlbum->getAlbum();
            $selectedPictureFilePath = ALBUM_PICTURES_DIR . "/$userId/$selectedAlbumId/" . $selectedPicture->getFileName();
            $selectedThumbnailFilePath = ALBUM_THUMBNAILS_DIR . "/$userId/$selectedAlbumId/" . $selectePicture->getFileName();
            $selectedOriginalPictureFilePath = ORIGINAL_PICTURES_DIR . "/$userId/$selectedAlbumId/" . $selectedPicture;

            if ($action === "Delete") {
                $dao = new DataAccessObject(INI_FILE_PATH);
                $dao->deletePicture($selectedPicture);
                $selectedAlbum->deletePicture($selectedPicture);

                unlink($selectedPictureFilePath);
                unlink($selectedThumbnailFilePath);
                unlink($selectedOriginalPictureFilePath);

                if (sizeof($selectedAlbum->getPictures() == 0)) {
                    $noPictureMessage = "You do not have any pictures in the selected album!";
                } else {
                    $selectePictureId = array_keys($selectedAlbum->getPictures())[0];
                    $selectedPicture = $selectedAlbum->getPictures()[$selectePictureId];
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
            if(sizeof($albums == 0)) {
                $noAlbumMessage = "You do not have any albums yet!";
            } else {
                $noAlbumMessage = "";
                $selectedAlbumId = array_keys($albums)[0];
                $selectedAlbum = $albums[$selectedAlbumId];
                if (sizeof($selectedAlbum->getPictures() == 0)) {
                    $noPictureMessage = "You do not have any pictures in the selected album";
                } else {
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
                            <select name='selectedAlbumId' class='form-control' onchange="onAlbumChange()">
            <?php
            foreach ($albums as $album) {
                $albumDisplayText = $album;
                $albumId = $album->getAlbumId();
                print "<option value='$albumId' " . ($albumId == $selectedAlbumId ? "selected" : "") . ">$album"; // ------- needs to be modified ------
            }
            ?>
                            </select>
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
                    $selectedPictureFilePath = ALBUM_PICTURES_DIR . "/$userId/$selectedAlbumId/" . $selectePicture->getFileName();
                    ?>

                    <div class="row vertical-margin">
                        <div class="row text-center"><h3><?php print $selectePicture->getTitle() ?></h3></div>
                    </div>

                    <div class="row vertical-margin">
                        <div class="col-md-8 text-center">
                            <div class="row vertical-margin">
                                <div class="col-lg-12 text-center img-container">
                                    <img style="width:100%" src="<?php print $selectedPictureFilePath . "?rnd=" . rand(); ?>"/>
                                    <a href="MyPictures.php>action=RotateLeft"><span style="position: absolute; left: 35%; top: 90%;" class="glyphicon glyphicon-repeat gly-flip-horizontal"></span></a>
                                    <a href="MyPictures.php>action=RotateRight"><span style="position: absolute; left: 45%; top: 90%;" class="glyphicon glyphicon-repeat"></span></a>
                                    <a href="MyPictures.php>action=Download"><span style="position: absolute; left: 55%; top: 90%;" class="glyphicon glyphicon-download-alt"></span></a>
                                    <a href="MyPictures.php>action=Delete" onclick="return confirm('This picture and all the comments to it will be deleted!');"><span style="position: absolute; left: 65%; top: 90%;" class="glyphicon-remove"></span></a>
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
        $rand = rand();
        if ($selectedPictureId == $pictureId) {
            print "<button value='$pictureId' name='selectedPictureId' style='border-style: solid; border-color: blue; border-width: 3px'>";
        } else {
            print "<button value='$pictureId' name='selectedPictureId'>";
        }
        print "<img style='height:65px; auto;' src='$thumbnailPath?rnd=$rand'></button>";
    }
    if (!isset($scrollPosition)) {
        $scrollPosition = 0;
    }
    $_SESSION['selectedPicture'] = $selectePicture;
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
    if (sizeof($selectePicture->getComments()) > 0) {
        print "<span style='font-weight:bold' >Comments:</span><br/>";
        foreach ($selectedPicture->getComments() as $comment) {
            $authur = $comment->getAuthur()->getname();
            $date = $comment->getDate()->format('Y-m-d');
            $text = $comment->getCommentText();
            print "<p><span style='font-weight:italio; color:blue;'>$authur ($date):</span>$text</p>";
        }
    }
    ?>
                            </div>
                            <textArea name="commentText" rows="4" style="width:100%; margin-top: 2px" plceholder="Leave a comment..."/>
                            <br/>
                            <input type="submit" value="Add Comment" class="btn btn-primary btn-min-width" name="btnComment"
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
          


