<?php
require_once "connections/connection.php";

$sql1="DROP TRIGGER IF EXISTS BeforeDeleteTrigger";
$sql2="CREATE TRIGGER BeforeDeleteTrigger BEFORE DELETE ON article FOR EACH ROW
BEGIN
INSERT INTO article_update (nume, status, edtime) VALUES (OLD.title, 'DELETED', NOW());
END ;";

$stmt1 = $con->prepare($sql1);
$stmt2 = $con->prepare($sql2);
$stmt1->execute();
$stmt2->execute();
