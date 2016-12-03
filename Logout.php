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
        
        session_start();
if (isset($_SESSION['usert'])) {
   session_destroy();
   echo "<br> <p> you are logged out successufuly!</p>";
} 
   echo "<br/><p><a href='Index.php'>login</a></p>";
        
        
        
        
        
        
        
      
        include './Lab7Common/Footer.php';
        
        ?>
