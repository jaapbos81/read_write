<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "server_client_exercise";

  // Create connection
  $mysqli = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($mysqli->connect_error) {
     die("Connection failed: " . $mysqli->connect_error);
  }
?>
