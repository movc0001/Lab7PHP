<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Course Registration</title>
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
        
        if(isset($_SESSION['user'])){
            $loginMessage = "You are already logged in!";
        }
        else{
            $loginMessage ="";
        }
        
        extract($_POST);
        

        $dao = new DataAccessObject(INI_FILE_PATH);

        if (isset($_POST["btnsubmit"])) {
            
            

        $userId = trim($_POST["id"]);
        $pass = trim($_POST["password"]);
        $hashedpass =  sha1($pass);
        $user = $dao->getUserByIdAndPassword($userId, $hashedpass);
        $userIdValidateError = validateUserId($userId);
        $passValidateError = validatePassword($pass);
        $loginError='';
        $errorlist = [];

        if (strlen($userIdValidateError) > 0) {
        array_push($errorlist, $nameValidateError);
        }

        if (strlen($passValidateError) > 0) {
        array_push($errorlist, $passValidateError);
        }

        $validateUser = $dao->getUserByIdAndPassword($userId, $hashedpass);
        if($validateUser != null)
        {

        if (count($errorlist) <= 0){

        $_SESSION["user"] = $user;
        
        if($_SESSION['rurl'] = "AddAlbum.php"){
             header("Location: AddAlbum.php");
             exit();
        }
        header("Location: AddAlbum.php");
        exit();

        }
        }
        else
        {
        $loginError = "Login and password do not match!";
        }

        }
        if(isset($_POST["btnClear"]))
        {
        $_SESSION["id"] = false;
        $stdId = "";
        $pass = "";
        unset($_SESSION["id"]);
        

        }
        ?>
        <div class="container">
            <div class="row vertical-margin">
                <div class="col-md-8 text-center">
                    <h2>Log In</h2>
                </div>          
            </div>
            <div class="row vertical-margin">
                <div class="col-md-6">
                    <p>You need to <a href="NewUser.php"> sign up </a> if you are a new user!</p>
                    <p style="color: red"><?php print $loginError; ?></p>
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


                <div class="form-group" >
                    <label class="control-label col-md-2" for="password" >Password: </label>  
                    <div class="col-md-4">
                        <input class="form-control col-md-4" type="password" id="password" name="password" value="<?php print $pass ?>"><span style="color:red"><?php print $passValidateError ?></span>  
                    </div> 

                </div>


                <br/>

                <div class="col-md-6 text-right">
                    <input class="btn btn-primary" type = "submit" name="btnsubmit" value = "Submit" class="button" />
                    <button class="btn btn-primary" type="reset" name="btnClear" value="Reset" class="button">Clear</button>
                </div>

            </form>

        </div>
    </body>
</html>  

</body>
</html>
<?php
include './Lab7Common/Footer.php';
?>
