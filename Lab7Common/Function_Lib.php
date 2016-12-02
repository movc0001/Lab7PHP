<?php
function validateName($name)
{
    $name = trim($name);
    if(empty($name))
    {
        return "Name cannot be blank!";
    }
    else
    {
        return "";    
    }
}
function validateUserId($id)
{
    $id = trim($id);
    if(empty($id))
    {
        return "User ID cannot be blank!";
    }
    else
    {
        return "";
    }
}

function validatePhone($phone)
{
    if (trim($phone) != "") {

        $phoneRegExp = "/[2-9][0-9]{2}-[2-9][0-9]{2}-[0-9]{4}/";
        if (preg_match($phoneRegExp, $phone) != 1) {

            $invalidPhoneNumError = "Phone number must be in the form of nnn-nnn-nnnn";
            return $invalidPhoneNumError;
        }
        return "";
    } else {
        $phoneError = "Phone number cannot be blank";
        return $phoneError;
    }
    return "";
}

function validatePassword($pass)
{
    if (trim($pass) != "") {
        $passRegEx = "/(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])/";
        
        if (preg_match($passRegEx, $pass) != 1) {

            $invalidPassError = "Password is at least 6 characters long, contains at least one upper case, one lowercase and one digit";
            return $invalidPassError;
        }
        return "";
    } else {
        $passError = "Password cannot be blank";
        return $passError;
    }
    return "";
        
}

function validatePasswordAgain($passAgain)
{
     if (trim($passAgain) != "") {
        if (validatePassword($pass) != $passAgain) {

            $invalidPassAgainError = "Password doesn't match";
            return $invalidPassAfainError;
        }
        return "";
    } else {
        $passAgainError = "Password cannot be blank";
        return $passAgainError;
    }
    return "";
}

function IsLoggedIn()
{
    if(isset($_SESSION['id']) && isset($_SESSION['pass']))
    {
        $userName = $_SESSION['id'];
        $password = $_SESSION['pass'];
        
        if (CheckUserAndPassword($userName, $password))
        {
            echo "<li><a href='Logout.php'>Log Out</a></li>";
        }
        else
    {
        echo "<li><a href='Login.php'>Log In</a></li>";
    }
    
    }
    else
    {
        echo "<li><a href='Login.php'>Log In</a></li>";
    }
}
   
?>