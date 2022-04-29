<?php
$servername = "109.106.246.1";
$username = "u635309332_root1";
$password = "rootPass1";

try {
    $db = new PDO("mysql:host=$servername;dbname=u635309332_cafateria1", $username, $password);
    }
catch(PDOException $e)
    {
    echo "Connection failed: ". $e->getMessage();
    }
?>