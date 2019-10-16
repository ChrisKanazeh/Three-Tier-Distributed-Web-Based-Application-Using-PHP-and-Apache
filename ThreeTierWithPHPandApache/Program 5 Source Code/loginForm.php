<?php
/*     
   Name: Christopher Kanazeh
   Course: CNT 4714 - Fall 2015
   Assignment Title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
   Due Date: November 29, 2015
*/ 
?>

<?php
        if(isset($_SESSION["user"]))
        {
?>
                <form method="get" action="logout.php" style="text-align: center">
                        <p>
                                <b>Welcome back</b><br />
                                <?php print($_SESSION["user"]); ?>
                        </p>
                        <button type="submit" value="logout">Logout</button>
                </form>
<?php
        }
        else
        {
?>
                <form method="post" action="login.php" style="text-align: center">
                        <h3>Username</h3>
                        <input name="username" /><br/>
                        <h3>Password</h3>
                        <input name="password" type="password" /><br/>
                        <button type="submit" value="login">Login</button>
                </form>
<?php
                checkAndDisplayErrors();
        }

        function checkAndDisplayErrors()
        {
                if(isset ($_GET["invalidLogin"]))
                {
                        print("<p class=\"loginError\">Login failed</p>");
                }
                if(isset ($_GET["failedToConnectToDb"]))
                {
                        print("<p class=\"loginError\">Database Error</p>");
                }
        }
?>