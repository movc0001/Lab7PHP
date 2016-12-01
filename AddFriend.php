<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Add Album</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    </head>

    <body>

        <?php
        include "./Lab7Common/Header.php";
        include_once "./Lab7Common/EntityClass_Lib.php";
        include_once "./Lab7Common/DataAccessClass_Lib.php";
        include "./Lab7Common/Constants.php";
        include "./Lab7Common/Function_Lib.php";

        session_start();
        $dao = new DataAccessObject(INI_FILE_PATH);
        if (!isset($_SESSION['user'])) {
            $_SESSION['rurl'] = "AddFriend.php";
            header("Location: Login.php");
            exit();
        }
        
        extract($_POST);
        $user = $_SESSION["user"];
        $error = "";
        
        if (isset($btnSubmit)) {
            
        }
        
        ?>
        
        
        
        <div class="container">
            <div class="row vertical-margin col-md-8 text-center">
                <h2>Add Friend</h2>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <p>Welcome <?php print $user->getName(); ?>! (not you? Change <a href="Login.php"> user </a>here)</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <p>Enter the ID of the user you want to be friend with</p>
                </div>
            </div>
           
                    <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-group">
                            <div class="row-fluid">
                                
                                <div class="form-group">
            <label class="control-label">ID:</label>
            <div class="input-group ">
                <input type="text" class="form-control" id="id" name="id" value="<?php print $id ?>"/>
                <input class="btn btn-primary" type = "submit" name="btnSubmit" value = "Send Friend Request" class="button" />
            </div>
        </div>

                            </div>                       
                        </div>
                    </form>

                    </div>


                    </div>



    </body>
</html>