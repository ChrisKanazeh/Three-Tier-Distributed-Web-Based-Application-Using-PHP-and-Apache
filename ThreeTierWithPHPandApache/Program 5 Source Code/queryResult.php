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
        <p><b>Query Results</b></p>
<?php
        printResultsTable($result);
?>
                

<?php
function printResultsTable($result)
{
        $columns = mysql_num_fields($result);

        print("<table class=\"update\" border=\"2\">\n");
        print("<tr>\n");
        for($i = 0; $i < $columns; $i++)
        {
                printf("        <th class=\"result\">%s</th>\n", mysql_field_name($result, $i));
        }
        print("</tr>\n");

        $useOtherRowType = false;

        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
                print("<tr>\n");
                foreach($row as $cell)
                {
                        printf("        <td class=\"%s\">\n", $useOtherRowType ? "resultA" : "resultB");
                        printf("%s\n", $cell);
                        print(" </td>\n");
                }
                $useOtherRowType = !$useOtherRowType;
                print("</tr>\n");
        }
        print("</table>\n");
}
?>