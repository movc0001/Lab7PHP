<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <title>My Friends</title>
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
            $_SESSION['rurl'] = "MyFriends.php";
            header("Location:Login.php");
            exit();
        }
        extract($_POST);
        $user = $_SESSION["user"];
        
       $dao->getFriendsForUser($user);
       $dao->getAlbumsForUser($user);
        
        if (isset($btnDefriend)) {
            if (isset($toDefriend)) {
                $dao = new DataAccessObject(INI_FILE_PATH);
                foreach ($toDefriend as $friendId) {
                    $dao->deleteFriend($user, $friendId);
                }
            }
        }
        if (isset($btnDeny)) {
            if (isset($acceptDeny)) {
                $dao = new DataAccessObject(INI_FILE_PATH);
                foreach ($acceptDeny as $requesterId) {
                    $dao->denyFriendRequest($user, $requesterId);
                }
            }
        }
        if (isset($btnAccept)) {
           // if (isset($acceptDeny)) {
                $dao = new DataAccessObject(INI_FILE_PATH);
                foreach ($acceptDeny as $requesterId) {
                    $dao->acceptFriendRequester($user, $requesterId);
                }
            //}
        }
        ?>
        <div class="container">
            <div class="row vertical-margin text-center col-md-10">
                <h2>My Friends</h2>
            </div>
            <div class="row vertical-margin">
                <div class="col-md-10">
                    <p>Welcome <?php print $user->getName(); ?>! (not you? Change <a href="Login.php"> user </a>here)</p>
                </div>
            </div>
            <div class="row vertical-margin col-md-10 text-right">
                <p><a href="AddFriend.php">Add Friends</a></p>
            </div>
            <form class="form-horizontal" method="post" id="friendForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="row vertical-margin">
                    <div class="col-md-12">
                        <table class="table">
                            <tr>
                                <th>Name</th>
                                <th>Shared Albums</th>
                                <th>Defriend</th>
                            </tr>

                            <?php
                            $friends = $user->getFriends();
                            if (sizeof($friends) == 0) {
                                print "<tr><td colspan='3' style='text-align: center; color:red'>You have no friends";
                            }
                            foreach ($friends as $friend) {
                                $id = $friend->getUserId();
                                $name = $friend->getName();
                                $dao->getAlbumsForUser($friend);
                                $numAlbums = sizeof($friend->getSharedAlbums());
                                print "<tr><td><a href='FriendPictures.php?friendId=$id'>$name</a></td><td>$numAlbums</td>"
                                        . "<td><input type='checkbox' name='toDefriend[]' value='$id'></td></tr>";
                            }
                            ?>

                        </table>
                    </div>
                </div>


                <div class="col-md-10 text-right ">
                    <input class="btn btn-primary" type = "submit" name="btnDefriend" onclick="return confirm('Are you sure you want to delete this friend?') "value = "Defriend Selected" class="button" />
                </div>


                <!------------------------- FRIEND REQUEST ------------------------->
                <br/>
                <br/>
                <form class="form-horizontal" method="post" id="friendRequestForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row vertical-margin">
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <th>Accept or Deny</th>
                                
                                </tr>
                                <?php
                                //$requesters = $user->getFriendRequesters();
                                $requesters = array();
                                $request= $dao->getFriendRequestersForUser($user);
                                foreach($request as $requester){
                                    $req = $dao->getUserById($requester);
                                    $requesters[] = $req;
                                }
                                if (sizeof($requesters) > 0) {
                                    foreach ($requesters as $requester) {
                                        $id = $requester->getUserId();
                                        $name = $requester->getName();
                                        print "<tr><td>$name</a></td>"
                                                . "<td><input type='checkbox' name='acceptDeny[]' value='$id'></td></tr>";
                                    }
                                }
                                else
                                {
                                    print "<tr><td colspan='3' style='text-align: center; color:red'>You have no friend requests!";
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-10 text-right ">
                        <input class="btn btn-primary" type = "submit" name="btnAccept" value = "Accept Selected" class="button" />
                        <input class="btn btn-primary" type = "submit" name="btnDeny" value = "Deny Selected" class="button" />
                    </div>

                </form>

            </form>
        </div>
    </body>
</html>

<?php
include './Lab7Common/Footer.php';
?>
