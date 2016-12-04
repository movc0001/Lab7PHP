<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>Add Friend</title>
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
            $message = ValidateUserId($requesteeId);

            if ($message == "") {
                if ($requesteeId === $user->getUserId()) {
                    $message = "You can not be a friend of yourself!";
                }
            }
            if ($message == "") {
                if ($user->isFriend($requesteeId)) {
                    $friend = $user->getFriends()[$requesteeId];
                    $message = $friend->getName() . " (ID: $requesteeId) is already your friend!";
                }
            }

            if ($message == "") {
                $dao = new DataAccessObject(INI_FILE_PATH);

                $requestee = $dao->getUserById($requesteeId);

                if ($requestee == null) {
                    $message = "$requesteeId is not a valid user ID!";
                }
            }

//            if ($message == "") {
//                if ($user->isRequestedBy($requesteeId)) {
//                    $dao->acceptFriendRequester($user, $requesteeId);
//
//                    $friend = $user->getFriends()[$requesteeId];
//                    $friendName = $friend->getName();
//                    $message = "Since $friendName also has requested to be your friend too, you and $friendName are now friends";
//                }
//            }
            if ($message == "") {
                $dao->saveFriendRequest($user, $requesteeId);
                
                $requesteeName = $requestee->getName();
                $message = "Your request has sent to $requesteeName (ID: $requesteeId). <br/>"
                        . "Once $requesteeName accepts your request,"
                        . "you and $requesteeName will be friends and be able to view each other'shared albums.";
                $requesteeId = "";
            }
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
            <div class="row vertical-margin">
                <div class="col-md-5 col-md-offset-2 error"><?php print($message) ?></div>
            </div>

            <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="row vertical-margin">
                    <div class="col-md-1 col-md-offset-2"><label class="no-margin no-padding">ID:</label></div>
                    <div class="col-md-3"><input type="text" name="requesteeId" class="form-control"></div>
                    <div class="col-md-3">
                        <input type="submit" class="btn btn-primary btn-min-width" name="btnSubmit">
                    </div>    
                </div>
            </form>

        </div>


        </div>



    </body>
</html>