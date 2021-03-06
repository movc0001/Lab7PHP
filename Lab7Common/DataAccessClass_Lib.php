<?php

include_once 'EntityClass_Lib.php';
include_once 'Constants.php';

class DataAccessObject {

    private $pdo;

    function __construct($iniFile) {
        $dbConnection = parse_ini_file($iniFile);
        extract($dbConnection);
        $this->pdo = new PDO($dsn, $user, $password);
    }

    function __destruct() {
        $this->pdo = null;
    }

    public function getAccebility() {
        $sql = "SELECT Accessibility_Code, Description FROM Accessibility";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $access = array();
        foreach ($stmt as $row) {
            $accessibility = new Accessibility($row['Accessibility_Code'], $row['Description']);
            $access[] = $accessibility;
        }
        return $access;
    }

    public function getAlbumsForUser($user) {
        $albums = array();
        $sql = "SELECT Title, Description, Date_Updated, Accessibility_Code, Album_Id "
                . "FROM Album WHERE Owner_Id = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $user->getUserId()]);
        foreach ($stmt as $row) {
            //$dateUpdated = DateTime::createFromFormat('Y-m-d G:i:s', $row['Date_Updated']);
            //$album = new Album($row['Title'], $row['Description'], $row['Accessibility_Code'],$row['Album_Id'], $dateUpdated);
            $album = new Album($row['Title'], $row['Description'], $row['Date_Updated'], $row['Accessibility_Code'], $row['Album_Id']);

            $this->getPicturesForAlbum($album);
            //$albums[$album->getAlbumId()] = $album;
            $albums[] = $album;
        }
        $user->setAlbums($albums);
        return $albums;
    }

    public function getAlbumById($albumId) {
        $sql = 'SELECT Album_Id, Title, Description, Date_Updated, Owner_Id, Accessibility_Code FROM Album WHERE Album_Id = :albumId';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['albumId' => $albumId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            $album = new Album($row['Title'], $row['Description'], $row['Date_Updated'], $row['Accessibility_Code'], $row['Album_Id']);
        }
        return $album;
    }

    public function updateAlbumAccessibillity($albumid, $newAccessibillityCode) {
        $sql = "UPDATE Album SET Accessibility_Code = :accessibilityCode WHERE Album_Id = :albumId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['accessibilityCode' => $newAccessibillityCode, 'albumId' => $albumid]);
        $access = array();
        foreach ($stmt as $row) {
            $accessibility = new Accessibility($row['Accessibility_Code'], $row['Description']);
            $access[] = $accessibility;
        }
        return $access;
    }

    public function saveAlbum($user, $album) {
        $dateUpdated = $album->getDate_Updated();  //->format('Y-m-d G:i:s');
        $sql = "INSERT INTO Album (Title, Description, Owner_Id, Date_Updated, Accessibility_Code) VALUES( :title, :description, :userId, :dateUpdated, :accessibilityCode)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['title' => $album->getTitle(), 'description' => $album->getDescription(), 'userId' => $user->getUserId(), 'dateUpdated' => $dateUpdated, 'accessibilityCode' => $album->getAccessibility_code()]);

        $albumId = $this->pdo->lastInsertId();
        $album->setAlbumId($albumId);
        $user->addAlbum($album);
    }

    public function getUserById($userId) {
        $sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User($row['UserId'], $row['Name'], $row['Phone']);
        }
        return $user;
    }

    public function saveUser($userId, $name, $phone, $password) {
        $sql = "INSERT INTO User VALUES( :userId, :name, :phone, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'name' => $name, 'phone' => $phone, 'password' => $password]);
    }

    public function saveComment($comment) {
        $sql = "INSERT INTO Comment (Comment_Id, Author_Id, Picture_Id, Comment_Text, Date) VALUES(:id, :authorId, :pictureId, :commentText, :date)";
        $commentId = $this->pdo->lastInsertId();
        //$dateAdded = date('Y-m-d\TH:i:s');
        $author = $comment->getAuthorId();
        $pic = $comment->getPictureId();
        $text = $comment->getCommentText();
        $date = $comment->getCommentDate();

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $commentId, 'authorId' => $author, 'pictureId' => $pic, 'commentText' => $text, 'date' => $date]);

        $picture = $this->getPictureById($pic);

        $pictureComments = $picture->getComments();
        $pictureComments[] = $comment;
        $picture->setComments($pictureComments);
        //temp comment
//        $comment->setCommentId($commentId);
//        $comment->setAuthorId($author);
//        $comment->setPictureId($pic);
//        $comment->setCommentText($text);
//        $comment->setCommentDate($date);
        /////////
        //$comment ->setDate();
    }

    public function getCommentsForPicture($picture) {
        $sql = "SELECT Comment_Id, Author_Id, Picture_Id, Comment_Text, Date FROM Comment WHERE Picture_Id = :pictureId";
        $allcommentsForPicture = array();
        $pictureId = $picture->getPictureId();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pictureId' => $pictureId]);
        foreach ($stmt as $row) {
            //$dateUpdated = DateTime::createFromFormat('Y-m-d G:i:s', $row['Date_Updated']);
            //$album = new Album($row['Title'], $row['Description'], $row['Accessibility_Code'],$row['Album_Id'], $dateUpdated);
            //$comment = new Comment($row['Author_Id'], $row['Picture_Id'], $row['Comment_Text'], $row['Date'], $row['Comment_Id']);
            $comment = new Comment($row['Comment_Id'], $row['Author_Id'], $row['Picture_Id'], $row['Comment_Text'], $row['Date']);

            $allcommentsForPicture[] = $comment;
//            $this->getPicturesForAlbum($album);
//            $albums[$album->getAlbumId()] = $album;
        }
        return $allcommentsForPicture;
    }

    public function savePicture($album, $picture) { //not done
        $sql = "INSERT INTO Picture (Album_Id, FileName, Title, Description, Date_Added) VALUES( :albumId, :fileName, :title, :description, :dateAdded)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['albumId' => $album->getAlbumId(), 'fileName' => $picture->getFileName(), 'title' => $picture->getTitle(), 'description' => $picture->getDescription(), 'dateAdded' => $picture->getDateUploaded()]);

        $pictureId = $this->pdo->lastInsertId();
        $pictureWithId = $picture->setPictureId($pictureId);
        //$user->addAlbum($album);
        //$album = getAlbumById($albumid);

        $picToAlbum = $album->getPictures();
        $picToAlbum[] = $pictureWithId;
        $album->setPictures($picToAlbum);
    }

    public function getPicturesForAlbum($album) {
        $pictures = array();
        $sql = "SELECT Picture_Id, FileName, Title, Description, Date_Added FROM Picture WHERE Album_Id = :albumId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['albumId' => $album->getAlbumId()]);
        foreach ($stmt as $row) {
            $dateUploaded = DateTime::createFromFormat('Y-m-d G:i:s', $row['Date_Updated']);
            $picture = new Picture($row['Title'], $row['Description'], $row['FileName'], $row['Picture_Id']);

            $this->getCommentsForPicture($picture);
            //$pictures[$picture->getPictureId()] = $picture;
            $pictures[] = $picture;
        }
        $album->setPictures($pictures);
    }

    public function deletePicture($picture) { //not done
        $sql = "DELETE FROM Comment WHERE Picture_Id = :pictureId";
        $sql1 = "DELETE FROM Picture WHERE Picture_Id = :pictureId";
    }

    public function deleteFriend($user, $friendId) {
        $sql = "DELETE FROM Friendship "
                . "WHERE ((Friend_RequesterId = :userId AND Friend_RequesteeId= :friendId) "
                . "  OR (Friend_RequesterId = :friendId AND Friend_RequesteeId= :userId)) "
                . "    AND Status='accepted'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $user->getUserId(), 'friendId' => $friendId]);

        $friend = $user->getFriends()[$friendId];
        $user->defriend($friend);
    }

    public function denyFriendRequest($user, $requesterId) {
        $sql = "DELETE FROM Friendship WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId AND Status='request'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['requesterId' => $requesterId, 'userId' => $user->getUserId()]);
    }

    public function acceptFriendRequester($user, $requesteeId) {
        $sql = "UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['requesterId' => $requesteeId, 'userId' => $user->getUserId()]);

        $requester = $this->getUserById($requesteeId);

        $user->acceptRequest($requester);
    }

    public function getFriendsForUser($user) { //not done
        $sql = "SELECT Friend_RequesteeId FROM Friendship "
                . "WHERE Friend_RequesterId = :userId AND Status = 'accepted'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $user->getUserId()]);
        $friendIds = array();
        foreach ($stmt as $row) {
            $friendIds[] = $row[Friend_RequesteeId];
        }
        $friends = array();
        foreach ($friendIds as $friend) {
            $aFriend = $this->getUserById($friend);
            $friends[] = $aFriend;
        }
        $user->setFriends($friends);
    }

    public function getFriendRequestersForUser($user) {
        $sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'request'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $user->getUserId()]);
        $requestersId = array();
        foreach ($stmt as $row) {

            $id = $row[Friend_RequesterId];
            $requestersId[] = $id;
        }
        return $requestersId;
    }

    public function saveFriendRequest($user, $requesteeId) {
        $requestee = $this->getUserById($requesteeId);

        if ($requestee == null) {
            return FALSE;
        }

        $sql = "INSERT INTO Friendship VALUES(:userId, :requesteeId, 'request')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $user->getUserId(), 'requesteeId' => $requesteeId]);



        return $requestee;
    }

    public function userExists($userId) {
        $sql = "SELECT COUNT(UserId) AS num FROM User WHERE UserId = :userId";
        $stmt = $this->pdo->prepare($sql);

        //Bind the provided username to our prepared statement.
        $stmt->bindValue(':userId', $userId);

        //Execute.
        $stmt->execute();

        //Fetch the row.
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['num'] > 0) {
            die("The User ID already exists!");
        }
    }

    public function getUserByIdAndPassword($userId, $password) {
        $user = null;
        $sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId AND Password = :password";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'password' => $password]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User($row['UserId'], $row['Name'], $row['Phone']);
        }
        return $user;
    }

    public function getAllAlbums() {
        $albums = array();
        $sql = 'SELECT Title, Description, Date_Updated, Accessibility_Code, Album_Id FROM Album';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        foreach ($stmt as $row) {
            //$album = new Album($row['Title'], $row['Description'], $row['Date_Updated'], $row[Owner_Id], $row['Accessibility_Code']);
            $album = new Album($row['Title'], $row['Description'], $row['Date_Updated'], $row['Accessibility_Code'], $row['Album_Id']);
            $albums[] = $album;
        }
        return $albums;
    }

    public function getPictureById($pictureId) {
        $sql = 'SELECT Picture_Id, Album_Id, FileName, Title, Description, Date_Added FROM Picture where Picture_Id = :pictureId';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pictureId' => $pictureId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            $picture = new Picture($row['Title'], $row['Description'], $row['FileName'], $pictureId);
        }
        return $picture;
    }

}

?>