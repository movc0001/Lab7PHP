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
        include_once "./Lab7Common/EntityClass_Lib.php";
        include_once "./Lab7Common/DataAccessClass_Lib.php";
        include "./Lab7Common/Function_Lib.php";
        include "./Lab7Common/Constants.php";
        ?>
        <div class="container">
            <div class="row vertical-margin">
                <div class="col-md-12">
            <h3>Welcome to Algonquin Sociaal Media Website!</h3>
                </div>
            </div>
            <div class="row vertical-margin">
                <div class="col-md-12">
                    <p>If you have never used this before you have to <a href="NewUser.php" >sign up</a> first.</p>
                    <p>If you have already signed up, you can <a href="Login.php" >log in</a> now.</p>
                </div>          
            </div>
            
        </div>

    </body>
</html>
        <?php
        include './Lab7Common/Footer.php';
        ?>
