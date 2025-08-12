<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "pu_connect";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn) {
    echo"";
}
else{
    echo"Something went wrong";
}
?>