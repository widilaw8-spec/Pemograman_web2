<?php
$con = mysqli_connect("localhost","root","","lat_dbase");
 
if (!$con) {
  die('Could not connect: ' . mysqli_connect_error());
}
 
mysqli_select_db($con, "lat_dbase");
 
mysqli_query($con, "DELETE FROM tbl_mhs WHERE LastName='Hidayat'");

mysqli_close($con);

echo "Delete berhasil!";
?>