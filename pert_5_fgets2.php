<?php
$file = fopen("pert_5_test1.txt","r");
while(! feof($file))
  {
  echo fgets($file). "<br />";
  }
fclose($file);
?>