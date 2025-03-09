<?php
require_once 'connections/connection.php';
$sql1 = "DROP TRIGGER IF EXISTS BeforeUpdateTrigger";
$sql2 = "CREATE TRIGGER BeforeUpdateTrigger BEFORE UPDATE ON article FOR EACH ROW
BEGIN
    SET NEW.title = LOWER(NEW.title);
END ;";

$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();

$sql3 = "DROP TRIGGER IF EXISTS AfterUpdateTrigger";
$sql4 = "CREATE TRIGGER AfterUpdateTrigger AFTER UPDATE ON article FOR EACH ROW
BEGIN
    INSERT INTO article_update (nume, status, edtime) VALUE (NEW.title, 'UPDATED', NOW());
END ;";

$stmt1 = $con->prepare($sql3);
$stmt2 = $con->prepare($sql4);
$stmt1->execute();
$stmt2->execute();