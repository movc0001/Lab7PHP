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
    }

    function getUserId() {
        return $this->userId;
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

    function getSharedAlbums() {
        $sharedAlbums = array();
        foreach ($this->albums as $album) {
            if ($album->getAccessibilityCode() == 'shared') {
                $sharedAlbums[$album->getAlbumId()] = $album;
            }
            return $sharedAlbums;
        }
    }

    function getAlbums() {
        return $this->albums;
    }

    function setAlbums($albums) {
        $this->albums = $albums;
    }

    function getAlbumById($albumId) {
        
    }

    function addFriend($friend) {
        $this->friends[$frind->getUserId()] = $friend;
    }

    function defriend($friend) {
        
    }

    function isFriend($userId) {
        
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

    function isRequestedBy($userId) {
        
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

    function __construct($title, $description, $date_updated, $accessibility_code, $albumId = null) {
        $this->title = $title;
        $this->description = $description;
        $this->date_updated = $date_updated;
        //$this->ownerId = $ownerId;
        $this->accessibility_code = $accessibility_code;
        $this->pictures = array();
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

    function getAccessibility_code() {
        return $this->accessibility_code;
    }

    //public function _toString();
}

class Picture {

    private $pictureId;
    private $title;
    private $description;
    private $dateUploaded;
    private $fileName;
    private $comments;

    function __construct($pictureId, $title, $description, $fileName) {
        $this->pictureId = $pictureId;
        $this->title = $title;
        $this->description = $description;
        $this->fileName = $fileName;
    }

}

Class Comment {
    
}

?>