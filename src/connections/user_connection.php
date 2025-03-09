<?php
global $conn;
$conn = mysqli_connect('mysql_db','root','toor','users')
    or die("Fieled to connect: ".mysqli_error($conn));
?>