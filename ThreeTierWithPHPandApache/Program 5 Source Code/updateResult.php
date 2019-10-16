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
                <p><b>Update Results</b></p>
                <p>
                        The statement executed successfully.<br />
                        <?php print($affectedRows) ?> row(s) affected.
                </p>
