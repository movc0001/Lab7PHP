<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Upload Pictures</title>
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
        include "./Lab7Common/ImageFunction_Lib.php";
      
        include './Lab7Common/Footer.php';
        
        session_start();
        if(!isset($_SESSION["user"]))
        {
            $_SESSION["rurl"] = "UploadPictures.php";
//            header("Location:Login.php");
    header("Location: Login.php");
            exit();
        }
        
        $user = $_SESSION["user"];
        
        extract($_POST);
        
        if(isset($btnUpload))
        {
            $numFiles = sizeof($_FILES['txtUpload']['error']);
            if($numFiles == 0)
            {
                $message = "No upload file specified";
            }
            else
            {
                $userId = $user->getUserId();
                $album = $user->getAlbums()[$selectedAlbumId];
                $selectedAlbumId = $album->getAlbumId();
                for($i = 0; $i< $numFiles; $i++)
                {
                    if($_FILES['txtUpload']['error'][$i] == 0)
                    {
                        $filePath = save_uploaded_file(ORIGINAL_PICTURES_DIR."/$userId/$selectedAlbumId", $i);
                        
                        $imageDetails = getimagesize($filePath);
                        
                        if($imageDetails && in_array($imageDetails, $supportedImageTypes))
                        {
                            $imageFilePath = resamplePicture(ALBUM_PICTURES_DIR."/$userId/$selectedAlbumId", $i);
                        }
                    }
                }
            }
            
        }
        
        ?>

        <div class="container-fluid">
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <h2 class="text-left">Upload Pictures</h2>
                </div>          
            </div>
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <p>Accept picture types: JPG(JPEG), GIF and PNG</p>
                    <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures</p>
                    <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
                </div>          
            </div>

            <br/>
            <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="sltAlbum">Upload to Album:</label>
                    <div class="row vertical-margin">
                    <div class="col-md-3 text-center">
                        
                        <!-- -------------- Needs to be changed ---------------- -->
                        
                        <select name="sltAlbum" class="form-control" onchange="onSemesterChanged()"> 

                            <?php
                            foreach ($albums as $album) {
                                $albumTitle = $album->getTitle();
                            }
                            ?>
                        </select>
                        <input type="hidden" id="semesterChangedFlag" name="semesterChangedFlag" value="" />
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="txtUpload">File to Upload:</label>
                    <div class="col-sm-4">
                        <input type="file" name="txtUpload" size="40"/>
                        <span style="color:red"><?php print $txtUploadValidateError ?></span>
                    </div>
                </div>
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="title">Title: </label>
                    <div class="col-sm-4">
                        <input class="form-control col-sm-4" type="text" id="title" name="phone" 
                               value="<?php print $title ?>"/><span style="color:red"><?php print $titleValidateError ?></span>
                    </div>

                </div>
                <div class="form-group" >
                    <label class="control-label col-sm-2" for="description" >Description: </label>  
                    <div class="col-sm-4">
                        <textarea class="form-control" rows="5" id="description"></textarea><span style="color:red"><?php print $descriptionValidateError ?></span>  
                    </div> 

                </div>

                <br/>

                <div class="col-sm-6">
                    <input class="btn btn-primary" type = "submit" name="btnUpload" value = "Upload" class="button" />
                    <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                </div>
            </form>

        </div>

        
    </body>
</html>

        
