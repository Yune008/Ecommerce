<?php
$conn = new mysqli("localhost", "root", "", "landersdb");

if ($conn->connect_error) {
    die("Connection failed");
}
?>