<?php
/*     
   Name: Christopher Kanazeh
   Course: CNT 4714 - Fall 2015
   Assignment Title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
   Due Date: November 29, 2015
*/ 
?>

<html>
        <head>
        </head>
        <body>
                <p>Testing</p>
                <p>Result: <?php printResult(); ?></p>
        </body>
</html>

<?php
function printResult()
{
        $query1 = "INSERT INTO                   `shipments`    VaLuES ('S5'    ,          'P6', 'J7', 400)";
        $query2 = "update    `shipments`   sEt quantity = quantity + 28 where `pnum` = 'P7';";

        // Pattern to capture "insert into shipments values ('<snum>', '<pnum>', '<jnum>', <quantity>);"
        $pattern = '/^insert[\s]+into[\s]+[`]?shipments[`]?[\s]+values[\s]*\((?:\'(?P<snum>[^\']+)\'\s*,\s*)(?:\'(?P<pnum>[^\']+)\'\s*,\s*)(?:\'(?P<jnum>[^\']+)\'\s*,\s*)(?P<quantity>[0-9]+)\)[\s]*[;]?$/i';
        if(preg_match($pattern, $query1, $groups))
        {
                // TODO: Business logic
                printf("Did insert into shipment %s with quantity of %d<br />", $groups['snum'], $groups['quantity']);
                print("<pre>");
                print_r($groups);
                print("</pre>");
        }


        $patternUpdate = '/^update[\s]+[`]?shipments[`]?[\s]+set[\s]+[`]?quantity[`]?[\s]+=[\s]+(.*)[\s]+where[\s]+[`]?pnum[`]?[\s]*=[\s]*[\'\"](?P<pnum>[^\s;]+)[\'\"]/i';
        if(preg_match($patternUpdate, $query2, $groups))
        {
                print("<pre>");
                print_r($groups);
                print("</pre>");
        }

        // Pattern to capture "update shipments set quantity = <expr>"
        //$patternUpdate = '/^update[\s]+[`]?shipments[`]?[\s]+set[\s]+[`]?quantity[`]?[\s]+=[\s]+(.*)[\s]+(?:where[\s]+[`]?pnum[`]?[\s]*=[\s]*[\'\"](?P<pnum>[^\s;]+)[\'\"])?/i';
        $patternUpdate = '/^update[\s]+[`]?shipments[`]?[\s]+set[\s]+[`]?quantity[`]?[\s]+=[\s]+(.*)[\s]+/i';
        if(preg_match($patternUpdate, $query2, $groups))
        {
                print("<pre>");
                print_r($groups);
                print("</pre>");
        }



}
?>