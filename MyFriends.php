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
        
        $albums = $dao->getAlbumsForUser($user);
        $friends = $dao->getFriendsForUser($user);
        $friendRequests = $dao->getFriendRequestersForUser($user);
        
        if($btnDefriend){
            
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
                                for ($i=0; $i < count($friends); $i++){
                                    $name = $friends[$i]->getName();
                                    $userId = $friends[$i]->getUserId();
                                    $userSharedAlbums = $userId->getSharedAlbum();
                                    $numOfSharedAlbum;
                                    foreach ($userSharedAlbums as $album)
                                    {
                                        if($userSharedAlbums->getAccessibility_code() == "shared")
                                        {
                                            $numOfSharedAlbum++;
                                        }
                                    }
                                    ?>
                            
                            <tr>
                                <td><a href="MyFriends.php"><?php print $name; ?></a></td>
                                <td><?php print $numOfSharedAlbum ?></td>
                                <td><input type="checkbox" id="chkDefriend" name="chkDefriend"</td>  
                            </tr>
                           
 <?php
}
?>
                    
                        </table>
                </div>
                </div>
            <div class="col-md-10 text-right ">
                    <input class="btn btn-primary" type = "submit" name="btnDefriend" value = "Defriend Selected" class="button" />
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
                                for ($i=0; $i < count($friendRequests); $i++){
                                    $name = $friendRequests[$i]->getName();
                                }
                                    ?>
                            
                            <tr>
                                <td><a href="MyFriends.php"><?php print $name; ?></a></td>
                                
                                <td><input type="checkbox" id="chkAcceptOrDeny" name="chkAcceptOrDeny" /></td>  
                            </tr>

                    
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
