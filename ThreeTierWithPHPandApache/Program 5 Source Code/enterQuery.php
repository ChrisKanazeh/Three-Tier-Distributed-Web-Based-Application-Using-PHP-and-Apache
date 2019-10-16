<?php
/*     
   Name: Christopher Kanazeh
   Course: CNT 4714 - Fall 2015
   Assignment Title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
   Due Date: November 29, 2015
*/ 
?>

<?php
        include 'checkAuthInfo.php';
?>
                <p><b>Enter Query</b></p>
                <p>Please enter a valid SQL query or update statement. You may allso just press "Submit Query" to select all Parts from the database.</p>
                <form method="post" action="">
                        <textarea name="userInput" cols="80" rows="10"><?php
                                if(isset($_POST["userInput"]))
                                {
                                        print($_POST["userInput"]);
                                }
                        ?></textarea>
                        <br/>
                        <button type="submit" name="submitQuery" value="1">Execute</button>
                        <button type="reset">Reset Window</button>
                </form>