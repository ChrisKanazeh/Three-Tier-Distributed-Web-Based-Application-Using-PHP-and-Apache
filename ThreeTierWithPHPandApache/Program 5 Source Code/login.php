<?php
/*     
   Name: Christopher Kanazeh
   Course: CNT 4714 - Fall 2015
   Assignment Title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
   Due Date: November 29, 2015
*/ 
?>

<?php
        session_start();

        // Redirect to index if login.php is directly accessed
        if(!isset($_POST["username"]))
        {
                header("Location: index.php");
                return;
        }

        session_unset();

        $username = $_POST["username"];
        $password = $_POST["password"];


        // Attempt to connect to database with specified credentials. If
        //  it succeeds then user has correct credentials.
        $db = mysql_connect("localhost", $username, $password);
        if(!$db)
        {
                switch(mysql_errno())
                {
                        case 1045:
                                header("Location: index.php?invalidLogin=1");
                                return;
                        default:
                                header("Location: index.php?failedToConnectToDb=" . mysql_errno());
                                return;
                }
        }
        mysql_close($db);

        // username and password stored in session for future connections to database. Only set
        //  after database verifies credentials are valid. Checking session for 'user' will be used
        //  to see if user is logged in.
        $_SESSION["user"] = $username;
        $_SESSION["pass"] = $password;

        header("Location: index.php");
?>