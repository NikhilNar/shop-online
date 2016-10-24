<?php

//echo $response;
$expression="";
 for($i=0;$i<=$no_of_responses-1;$i++)
 {
     $expression="ASIN=".$ASIN[$i]." Title=".$title[$i]." Manufacturer=".$manufacturer[$i]."</br>";
     echo "<div color='red'>";
     print_r($expression);
     echo"</div>";
 }

?>