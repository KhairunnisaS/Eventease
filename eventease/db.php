<?php
    $host = 'localhost';
    $dbname = 'eventease';
    $port = '5432';
    $user = 'postgres';
    $pass = 'nisalulusptn2023';

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");

    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }
?>
