<?php
/* 
   Name: Christopher Kanazeh
   Course: CNT 4714 - Fall 2015
   Assignment Title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
   Due Date: November 29, 2015
*/ 
?>

<?php
        // Redirect users that are not logged in to the main page
        if(!isset($_SESSION["user"]))
        {
                header("Location: index.php");
        }
?>
