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
    
    public function getAlbumsForUser($user)
    {
        $albums = array();
        $sql = "SELECT Title, Description, Date_Updated, Accessibility_Code, Album_Id "
                . "FROM Album WHERE Owner_Id = :userId";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $user->getUserId()]);
        foreach ($stmt as $row){
            $dateUpdated = DateTime::createFromFormat('Y-m-d G:i:s',$row['Date_Updated']);
            //$album = new Album($row['Title'], $row['Description'], $row['Accessibility_Code'],$row['Album_Id'], $dateUpdated);
            $album = new Album($row['Title'], $row['Description'], $dateUpdated, $row['Accessibility_Code'], $row['Album_Id']);
            $this->getPicturesForAlbum($album);
            $albums[$album->getAlbumId()] = $album;
        }
        $user->setAlbums($albums);
    }
    
    public function updateAlbumAccessibillity($album , $newAccessibillityCode)
    {
        $sql = "UPDATE Album SET Accessibility_Code = :accessibilityCode WHERE Album_Id = :albumId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $access = array();
        foreach ($stmt as $row) {
            $accessibility = new Accessibility($row['Accessibility_Code'], $row['Description']);
            $access[] = $accessibility;
        }
        return $access;
    }

    
    public function saveAlbum ($user, $album)
    {
        $dateUpdated = $album->getDate_Updated();  //->format('Y-m-d G:i:s');
        $sql = "INSERT INTO Album (Title, Description, Owner_Id, Date_Updated, Accessibility_Code) VALUES( :title, :description, :userId, :dateUpdated, :accessibilityCode)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['title'=>$album->getTitle(), 'description'=>$album->getDescription(), 'userId' => $user->getUserId(), 'dateUpdated' => $dateUpdated,'accessibilityCode'=>$album->getAccessibility_code()]);
        
        $albumId = $this->pdo->lastInsertId();
        $album->setAlbumId($albumId);
        $user->addAlbum($album);
    }


    public function getUserById($userId)
    {
        $sql = "SELECT UserId, Name, Phone FROM User WHERE UserId = :userId";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $user = new User($row['UserId'], $row['Name'], $row['Phone']);
            
        }
        return $user;
    }

    public function saveUser($userId, $name, $phone, $password){
        $sql = "INSERT INTO User VALUES( :userId, :name, :phone, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'name' => $name, 'phone' => $phone, 'password' => $password]); 
       
          
        
        
    }
    
    public function saveComment($picture,$comment)
    {
        $sql = "INSERT INTO Comment VALUES(null, :authorId, :pictureId, :comentText, :date)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['Comment_Text' => $comment, 'Picture_Id' => $picture->getPictureId()]); 
       
        $comment->setCommentText($comment);
        $user->setPictureId($$picture->getPictureId());
        
    }
    
    public function getCommentsForPicture($picture) //not done
    {
        $sql = "SELECT Comment_Id, Comment_Text, Date, UserId, Name, Phone FROM Comment "
                . "INNER JOIN User ON Comment.Author_Id = User.UserId WHERE Picture_Id = :pictureId";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pictureId' => $picture->getPictureId()]);
        foreach ($stmt as $row){
            $dateUpdated = DateTime::createFromFormat('Y-m-d G:i:s',$row['Date_Updated']);
            //$album = new Album($row['Title'], $row['Description'], $row['Accessibility_Code'],$row['Album_Id'], $dateUpdated);
            $comment = new Comment($row['']);
            $this->getPicturesForAlbum($album);
            $albums[$album->getAlbumId()] = $album;
        }
        $user->setAlbums($albums);
    }

    public function savePicture($album, $picture) //not done
    {
        $sql = "INSERT INTO Picture (Album_Id, File_Name, Title, Description, Date_Added) VALUES( :albumId, :fileName, :title, :description, :dateAdded)";
    }
    
    public function getPicturesForAlbum($album)
    {
        $pictures = array();
        $sql = "SELECT Picture_Id, File_Name, Title, Description, Date_Added FROM Picture WHERE Album_Id = :albumId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['albumId'=> $album->getAlbumId()]);
        foreach ($stmt as $row)
        {
            $dateUploaded = DateTime::createFromFormat('Y-m-d G:i:s',$row['Date_Updated']);
            $pictures = new Picture($row['Title'], $row['Description'], $row['FileName'], $dateUploaded);
            
            $this->getCommentsForPicture($picture);
            $pictures[$picture->getPictureId()]= $picture;
        }
        $album->setPictures($pictures);
    }   

    public function deletePicture($picture) //not done
    {
        $sql = "DELETE FROM Comment WHERE Picture_Id = :pictureId";
	$sql1 = "DELETE FROM Picture WHERE Picture_Id = :pictureId";
    }
    
    public function deleteFriend($user, $friendId) //not done
    {
        $sql = "DELETE FROM Friendship "
                . "WHERE ((Friend_RequesterId = :userId AND Friend_RequesteeId= :friendId) "
                . "  OR (Friend_RequesterId = :friendId AND Friend_RequesteeId= :userId)) "
                . "    AND Status='accepted'";	
    }

    public function denyFriendRequest($user,$requestId) //not done
    {
        $sql = "DELETE FROME Friendship WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId AND Status='request'";
    }
    
    public function acceptAfriendRequest($user, $requestId) //not done
    {
        $sql = "UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId";
    }

    public function getFriendsForUser($user) //not done
    {
        $sql = "SELECT Friend_RequesteeId FROM Friendship "
                . "WHERE Friend_RequesterId = :userId AND Status = 'accepted'";
				
	$sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'accepted'";
    }

    public function getFriendRequestersForUser($user) //not done
    {
        $sql = "SELECT Friend_RequesterId FROM Friendship "
                . "WHERE Friend_RequesteeId = :userId AND Status = 'request'";
    }

    public function saveFriendRequest($user, $request) //not done
    {
        $sql = "INSERT INTO Friendship VALUES( :userId, :requesteeId, 'request')";
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
        $sql='SELECT Title, Description, Date_Updated, Accessibility_Code, Album_Id FROM Album';
        $stmt = $this->pdo->prepare($sql);
         $stmt->execute();
        foreach ($stmt as $row) {
            //$album = new Album($row['Title'], $row['Description'], $row['Date_Updated'], $row[Owner_Id], $row['Accessibility_Code']);
            $album = new Album($row['Title'], $row['Description'], $row['Date_Updated'], $row['Accessibility_Code'], $row['Album_Id']);
            $albums[] = $album;
        }
        return $albums;
    }
}

?>