<?php

$host = "sql109.infinityfree.com";  
$user = "if0_40440306";      
$pass = "i5JAChB1Dla";  
$dbname = "if0_40440306_lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    
    die("Database connection failed: " . $conn->connect_error);
}
?>
