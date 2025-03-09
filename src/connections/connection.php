<?php
$dbms = 'mysql';
$host = 'mysql_db';
$db = 'users';
$user = 'root';
$pass = 'toor';
$dsn = "$dbms:host=$host;dbname=$db";
$con = new PDO($dsn, $user, $pass);
