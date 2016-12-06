<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Algonquin Social media</title>
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

        if (isset($_POST["btnSubmit"])) {
            $userId = trim($_POST["id"]);
            $userName = trim($_POST["name"]);
            $phone = trim($_POST["phone"]);
            $pass = trim($_POST["password"]);
            $passAgain = trim($_POST["passwordAgain"]);
            $hashedPassword = sha1($pass);
            $userIdValidateError = validateUserId($userId);
            $nameValidateError = validateName($userName);
            $phoneValidateError = validatePhone($phone);
            $passValidateError = validatePassword($pass);
            $passAgainValidateError = validatePasswordAgain($passAgain);

            $userExistValidateError = $dao->userExists($userId);

            $errorlist = [];

            if (strlen($nameValidateError) > 0) {
                array_push($errorlist, $nameValidateError);
            }
            if (strlen($userIdValidateError) > 0) {
                array_push($errorlist, $userIdValidateError);
            }
            if (strlen($phoneValidateError) > 0) {
                array_push($errorlist, $phoneValidateError);
            }
            if (strlen($passValidateError) > 0) {
                array_push($errorlist, $passValidateError);
            }
            if (strlen($passAgainValidateError) > 0) {
                array_push($errorlist, $passAgainValidateError);
            }


            $exists = $dao->userExists($userId);
            if (!$exists) {

                if (count($errorlist) <= 0) {

                    $_SESSION["name"] = $userName;
                    $_SESSION["id"] = $userId;
                    $_SESSION["phone"] = $phone;
                    $_SESSION["password"] = $pass;
                    $_SESSION["Login"] = true;
                    $dao->saveUser($userId, $userName, $phone, $hashedPassword);

                    $user= $dao->getUserByIdAndPassword($userId, $hashedPassword);
                    $_SESSION["user"] = $user;

                    header("Location: MyAlbums.php");
                    exit();
                }
            } else {
                array_push($errorlist, $userExistValidateErrorExistValidateError); // needs to be improved
            }
        }
        if (isset($_POST["btnClear"])) {
            $_SESSION["name"] = false;
            $_SESSION["id"] = false;
            $_SESSION["phone"];
            $_SESSION["password"];
            $userName = "";
            $userId = "";
            $phone = "";
            $pass = "";


            unset($_SESSION["name"]);
            unset($_SESSION["id"]);
            unset($_SESSION["phone"]);
            unset($_SESSION["password"]);
        }
        ?>

        <div class="container">
            <div class="row vertical-margin">
                <div class="col-md-10">
                    <h2 class="text-left">Sign Up</h2>
                </div>          
            </div>
            <div class="row vertical-margin">
                <div class="col-md-10">
                    <p>All fields are required</p>
                </div>          
            </div>

            <br/>
            <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label class="control-label col-md-2" for="id">User ID:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="id" name="id" 
                               value="<?php print $userId ?>"/><span style="color:red"><?php print $userIdValidateError ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2" for="name">Name:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php print $userName ?>"/><span style="color:red"><?php print $nameValidateError ?></span>
                    </div>
                </div>
                <div class="form-group" >
                    <label class="control-label col-md-2" for="phone">Phone Number: </label>
                    <div class="col-md-4">
                        <input class="form-control col-sm-4" type="text" id="phone" name="phone" 
                               value="<?php print $phone ?>"/><span style="color:red"><?php print $phoneValidateError ?></span>
                    </div>

                </div>
                <div class="form-group" >
                    <label class="control-label col-md-2" for="password" >Password: </label>  
                    <div class="col-sm-4">
                        <input class="form-control col-md-4" type="password" id="password" name="password" value="<?php print $pass ?>"><span style="color:red"><?php print $passValidateError ?></span>  
                    </div> 

                </div>

                <div class="form-group" >
                    <label class="control-label col-md-2" for="passwordAgain" >Password Again: </label>  
                    <div class="col-sm-4">
                        <input class="form-control col-md-4" type="password" id="passwordAgain" name="passwordAgain" value="<?php print $passAgain ?>"><span style="color:red"><?php print $passAgainValidateError ?></span>  
                    </div> 

                </div>
                <br/>

                <div class="col-md-6 text-right">
                    <input class="btn btn-primary" type = "submit" name="btnSubmit" value = "Submit" class="button" />
                    <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                </div>
            </form>

        </div>


    </body>
</html>
<?php
include './Lab7Common/Footer.php';
?>
