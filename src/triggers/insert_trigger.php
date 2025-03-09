<?php 
require_once "connections/connection.php";
$sql1="DROP TRIGGER IF EXISTS BeforeInsertFlori";
$sql2="CREATE TRIGGER BeforeInsertFlori
    BEFORE INSERT ON article FOR EACH ROW
    BEGIN
        SET NEW.title = UPPER(NEW.title);
    END";
$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();


$sql3 = "DROP TRIGGER IF EXISTS AfterInsertFlori";
$sql4 = "CREATE TRIGGER AfterInsertFlori
    AFTER INSERT ON article
    FOR EACH ROW
    BEGIN
        INSERT INTO article_update(nume, status, edtime) VALUES(NEW.title, 'ISERTED', NOW());
    END";
$stmt3 = $con->prepare($sql3);
$stmt4 = $con->prepare($sql4);
$stmt3->execute();
$stmt4->execute();