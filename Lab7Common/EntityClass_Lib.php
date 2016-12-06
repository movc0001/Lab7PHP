<?php

class Accessibility {

    private $accessibilityCode;
    private $description;

    function __construct($accessibilityCode, $description) {
        $this->accessibilityCode = $accessibilityCode;
        $this->description = $description;
    }

    function getAccessibilityCode() {
        return $this->accessibilityCode;
    }

    function getDescription() {
        return $this->description;
    }

}

class User {

    private $userId;
    private $name;
    private $phone;
    private $password;
    private $albums;
    private $friends;
    private $friendrequesters;

    function __construct($userId, $name, $phone) {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;

        $this->albums = array();
        $this->friends = array();
        $this->friendrequesters = array();
    }

    function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    function getName() {
        return $this->name;
    }

    function getPhone() {
        return $this->phone;
    }

    function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    function getSharedAlbums() {
        $sharedAlbums = array();
//        $userAlbums = $this->albums;
//        if(count($userAlbums)>0){
        foreach ($this->albums as $album) {
            
            if ($album->getAccessibility_code() == 'shared') {
                $sharedAlbums[] = $album;
            }
            
        }
        return $sharedAlbums;
    }
    
//            }

    function getAlbums() {
        return $this->albums;
    }

    function setAlbums($albums) {
        $this->albums = $albums;
    }

    function getAlbumById($albumId) {
        $userAlbums = $this->getAlbums();
        foreach ($userAlbums as $album) {
            if ($album->getAlbumId() == $albumId) {
                $selectedAlb = $album;
            }
        }
        return $selectedAlb;
    }

    function addFriend($friend) {
        $this->friends[$frind->getUserId()] = $friend;
    }

    function defriend($friend) {
        
    }

    function isFriend($userId) {
        return array_key_exists($userId, $this->friends);
    }

    function getFriends() {
        return $this->friends;
    }

    function setFriends($friends) {
        $this->friends = $friends;
    }

    function getFriendrequesters() {
        return $this->friendrequesters;
    }

    function setFriendrequesters($friendrequesters) {
        $this->friendrequesters = $friendrequesters;
    }

    function addFriendRequest($requesteeId) {
        
    }

    function acceptRequest($requester) {

        $this->friends[] = $requester;
    }

    function isRequestedBy($userId) {
        return array_key_exists($userId, $this->friendrequesters);
    }

    public function addAlbum($album) {
        $albums = array();
        $albums = $this->getAlbums();
        $albums[] = $album;
    }

}

class Album {

    private $albumId;
    private $title;
    private $description;
    private $date_updated;
    //private $ownerId;
    private $accessibility_code;
    private $pictures;

    function __construct($title, $description, $date_updated, $accessibility_code, $albumId) {
        $this->title = $title;
        $this->description = $description;
        $this->date_updated = $date_updated;
        //$this->ownerId = $ownerId;
        $this->accessibility_code = $accessibility_code;
        $this->pictures = array();
        $this->albumId = $albumId;
    }

    function getTitle() {
        return $this->title;
    }

    function getAlbumId() {
        return $this->albumId;
    }

    function setAlbumId($albumId) {
        $this->albumId = $albumId;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function getPictures() {
        return $this->pictures;
    }

    function getDate_Updated() {
        return $this->date_updated;
    }

    function setDate_updated($date_updated) {
        $this->date_updated = $date_updated;
    }

    function getOwner_id() {
        return $this->owner_id;
    }

    public function getAccessibility_code() {
        return $this->accessibility_code;
    }

    function setPictures($pictures) {
        $this->pictures = $pictures;
    }

//    public function __toString() {
//        return $this->title . " -- Updated on " . $this->getDate_Updated()->format('Y-m-d');
//    }

}

class Picture {

    private $pictureId;
    private $title;
    private $description;
    private $dateUploaded;
    private $fileName;
    private $comments;

    function __construct($title, $description, $fileName, $pictureId) {
        $this->pictureId = $pictureId;
        $this->title = $title;
        $this->description = $description;
        $this->fileName = $fileName;

        $this->comments = array();
        $this->dateUploaded = date('Y-m-d\TH:i:s');
    }

    public function getPictureId() {
        return $this->pictureId;
    }

    public function setPictureId($pictureId) {
        $this->pictureId = $pictureId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDateUploaded() {
        return $this->dateUploaded;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function getComments() {
        return $this->comments;
    }

}

Class Comment {
    private $commentId;
    private $authorId;
    private $pictureId;
    private $commentText;
    private $commentDate;
    
    
    public function __construct($commentId, $authorId, $pictureId, $commentText, $commentDate=null) {
        $this->commentId = $commentId;
        $this->authorId = $authorId;
        $this->pictureId = $pictureId;
        $this->commentText = $commentText;
        $this->commentDate = $commentDate;
    }

//    public function __construct($authorId, $pictureId, $commentText, $commentDate, $commentId=null ) {
//        $this->commentId = $commentId;
//        $this->authorId = $authorId;
//        $this->pictureId = $pictureId;
//        $this->commentText = $commentText;
//        $this->commentDate = $commentDate;
//    }
    
    public function getCommentId() {
        return $this->commentId;
    }

    public function getCommentText() {
        return $this->commentText;
    }

    public function setCommentId($commentId) {
        $this->commentId = $commentId;
    }

    public function setCommentText($commentText) {
        $this->commentText = $commentText;
    }
    
    public function getAuthorId() {
        return $this->authorId;
    }

    public function getPictureId() {
        return $this->pictureId;
    }

    public function getCommentDate() {
        return $this->commentDate;
    }
    
    public function setPictureId($pictureId) {
        $this->pictureId = $pictureId;
    }
   
}

?>