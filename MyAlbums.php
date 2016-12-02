<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>My Albums</title>
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

        session_start();
        $dao = new DataAccessObject(INI_FILE_PATH);
        if (!isset($_SESSION['user'])) {
        $_SESSION['rurl'] = "MyAlbums.php";
        header("Location:Login.php");
        exit();
        }
        extract($_POST);
        $user = $_SESSION["user"];
        $error = "";

        if (isset($_SESSION['accessibility'])){
        $accessibility = $_SESSION['accessibility'];
        }
        else{
        $accessibility = $dao->getAccebility();
        $_SESSION['accessibility'] = $accessibility;
        }

        $albums = $dao->getAlbumsForUser($user);
        ?>

        <div class="container">
            <div class="row vertical-margin text-center col-md-10">
                <h2>My Albums</h2>
            </div>
            <div class="row vertical-margin">
                <div class="col-md-10">
                    <p>Welcome <?php print $user->getName(); ?>! (not you? Change <a href="Login.php"> user </a>here)</p>
                </div>
            </div>
            <div class="row vertical-margin col-md-10 text-right">
                <p><a href="AddAlbum.php">Create a New Album</a></p>
            </div>
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th>Title</th>
                            <th>Date Updated</th>
                            <th>Number of Pictures</th>
                            <th>Accessibility</th>
                        </tr>
<?php

foreach($albums as $album){
$title = $album->getTitle();
$date = $album->getDate_updated();
$numOfPics = sizeof($album->getPictures());
$access = $album->getAccessibility_code();



?>

                        <tr>
                            <td><a href="MyPictures.php"><?php print $title; ?></a></td>
                            <td><?php print $date; ?></td>
                            <td><?php print $numOfPics ?></td>
                            <td><select class="form-control" name="alumAccessibility[]">
                                    <option selected value="<?php $access ?>"><?php print $access; ?></option>
<?php
foreach ($accessibility as $accessType) {
$description = $accessType->getDescription();
$code = $accessType->getAccessibilityCode();
if($code != $access){
print "<option value='$code'> $description </option>";
}
}

?>
                                </select></td>

                        </tr>

<?php
//                                    print '<tr><td><a href="MyPictures.php"'. $title.'</a></td><td>'.$date.'</td><td>'.$numOfPics.'</td><td>'.$access.'</td></tr>';

}
?>

                    </table>
                </div>
            </div>
            <div class="col-md-10 text-right ">
                <input class="btn btn-primary" type = "submit" name="btnSave" value = "Save Changes" class="button" />
            </div>
            </form>








        </div>












    </body>
</html>

<?php
include './Lab7Common/Footer.php';
?>
