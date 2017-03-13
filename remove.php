<?php

	$host = 'localhost';
  $user = '<db_user>';
  $pwd = '<db_password>';
  $dbname = '<db_name>';
  $error = "Impossible connect to database $dbname! ";

  $hour = date('G', time());

  $connect = mysqli_connect($host, $user, $pwd, $dbname) or die($error.mysqli_connect_error());

  $query = "UPDATE `statistics` SET `counter` = 0 WHERE `hour` = $hour";

  mysqli_query($connect, $query) or die("Query add_stats failed. ".mysqli_error($connect));

  mysqli_close($connect);

?>