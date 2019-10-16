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
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title>Select and Update Databse Client</title>
                <link rel="stylesheet" type="text/css" href="./main.css">
    </head>
    <body>
                <div class="content">
                        <div class="header">
                                <h2>Jobs, Suppliers, Parts, Shipments Database</h2>
                                <hr  />
                        </div>

                        <div class="center">
                                <table align="center" style="width: 100%;">
                                        <tr>
                                                <td class="leftCell">
                                                        <?php displayLeftFrame(); ?>
                                                </td>
                                                <td class="rightCell">
                                                        <?php displayRightFrame(); ?>
                                                        <br />
                                                </td>
                                        </tr>
                                </table>
                        </div>
                        
                        <div class="footer">
                                <hr />
                                
                        </div>
                </div>
    </body>
</html>

<?php

// Displays the correct content of the left frame. Contains login form or current login details
function displayLeftFrame()
{
        include 'loginForm.php';
}

// Determins and displays the correct content of the right frame.
function displayRightFrame()
{
        if(!isset($_SESSION["user"]))
        {
                include 'welcome.php';
        }
        else
        {
                include 'enterQuery.php';

                if(isset($_POST["submitQuery"]))
                {
                        if(isset($_POST["userInput"]) && strlen(trim($_POST["userInput"])) > 0)
                        {
                                handleUserInput($_POST["userInput"]);
                        }
                        else
                        {
                                handleUserInput("select * from parts;");
                        }
                }
        }
}

// Processes the raw user input containing one or more sql commands. The last command executed
//   will determine the output. If last command is a select, a table is displayed, otherwise
//   number of affected rows is displayed. Any inserts/updated triggering business logic will
//   have additional output regarless of execution sequence.
function handleUserInput($userInput)
{
        $commands = explode(";", $userInput);

        foreach($commands as $userInput)
        {
                $fixedInput = trim($userInput);
                if(strlen($fixedInput) == 0)
                {
                        continue;
                }

                try
                {
                        $result = executeQuery($fixedInput);
                }
                catch(Exception $ex)
                {
                        print("<h3>Error:</h3>");
                        printf("<p>%s</p>", $ex->getMessage());
                        print("<br />");
                        return;
                }

                if(gettype($result) == "integer" && $result > 0)
                {
                        if(processQueryForBusinessLogic($fixedInput))
                        {
                                print("<h1>business logic</h1>");
                        }
                }
        }

        if(!isset($result))
        {
                return;
        }

        if(gettype($result) == "resource")
        {
                include 'queryResult.php';
                mysql_free_result($result);
        }
        else
        {
                $affectedRows = intval($result);
                include 'updateResult.php';
        }
}

// Executes a single mysql command. If command is an update, the number of affected rows is returned,
//   otherwise the resource containing rows is returned.
function executeQuery($query)
{
        $db = mysql_connect("localhost", $_SESSION["user"], $_SESSION["pass"]);
        if(!$db)
        {
                throw new Exception(mysql_error());
        }

        if(!mysql_select_db("project5", $db))
        {
                throw new Exception(mysql_error());
        }

        $result = mysql_query($query, $db);
        if(!$result)
        {
                throw new Exception(mysql_error());
        }
        
        if(gettype($result) == "boolean")
        {
                $affectedRows = mysql_affected_rows($db);

                mysql_close($db);
                return $affectedRows;
        }

        mysql_close($db);
        return $result;
}

// Check query string to see if it's an update or insert to shipments with quantity >= 100. If so
//   then increase status of suppliers with a shipment of quantity >= 100 by 5
function processQueryForBusinessLogic($query)
{
        $query = strtolower($query);
        
        // Pattern to capture "insert into shipments values ('<snum>', '<pnum>', '<jnum>', <quantity>);"
        $pattern = '/^insert[\s]+into[\s]+[`]?shipments[`]?[\s]+values[\s]*\((?:\'(?P<snum>[^\']+)\'\s*,\s*)(?:\'(?P<pnum>[^\']+)\'\s*,\s*)(?:\'(?P<jnum>[^\']+)\'\s*,\s*)(?P<quantity>[0-9]+)\)[\s]*[;]?$/i';
        if(preg_match($pattern, $query, $groups))
        {
                if($groups['quantity'] >= 100)
                {
                        // Non extra credit
                        //$result = executeQuery("update suppliers set status = status + 5 where snum in ( select snum from shipments where quantity >= 100);");
                        //printf("<p>Shipment '%s' added with quantity %d, %d supplier(s) updated</p>", $groups['snum'], $groups['quantity'], $result);

                        // Inserting part with quantity >= 100, update supplier of this part
                        $result = executeQuery("update suppliers set status = status + 5 where snum = '".$groups['snum']."';");
                        printf("<p>Shipment '%s' added with quantity %d, Status of supplier '%s' increased</p>", $groups['snum'], $groups['quantity'], $groups['snum'], $result);
                }
                return;
        }

        // Pattern to capture "update shipments set quantity = <expr> where pnum = <pnum>..."
        $pattern = '/^update[\s]+[`]?shipments[`]?[\s]+set[\s]+[`]?quantity[`]?[\s]+=[\s]+(.*)[\s]+where[\s]+[`]?pnum[`]?[\s]*=[\s]*[\'\"](?P<pnum>[^\s;]+)[\'\"]/i';
        if(preg_match($pattern, $query, $groups))
        {
                // Update with part specified, update all suppliers who ship this part if this part has quantity >= 100
                $result = executeQuery("update suppliers set status = status + 5 where snum in ( select distinct(snum) from shipments where pnum = '" . $groups['pnum'] . "' and quantity >= 100);");
                if($result > 0)
                {
                        printf("<p>Quantity of part '%s' increased >= 100, updating %d suppliers of this part</p>", $groups['pnum'], $result);
                }
                return;
        }

        // Pattern to capture "update shipments set quantity = <quantity>..."
        $pattern = '/^update[\s]+[`]?shipments[`]?[\s]+set[\s]+[`]?quantity[`]?[\s]+=[\s]+(?P<quantity>[0-9]+)/i';
        if(preg_match($pattern, $query, $groups))
        {
                if($groups['quantity'] >= 100)
                {
                        // No part specified, update all supplier with parts of quantity >= 100
                        $result = executeQuery("update suppliers set status = status + 5 where snum in ( select distinct(snum) from shipments where quantity >= 100);");
                        printf("<p>Shipment quantity set to %d for one or more shipments, %d supplier(s) updated</p>", $groups['quantity'], $result);
                        return;
                }
        }
}
?>