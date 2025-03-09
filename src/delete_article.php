<?php
include "connections/user_connection.php";
include "connections/connection.php";
include "triggers/delete_trigger.php";
include "article.php";
include "UserReg.php";


if (isset($_GET['id']) && article::exists($_GET['id'], $con) ) {
    $article = new article($con, $_GET['id']  );
    if (isset($_COOKIE["rememberMe"])) {
        if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {
            $user = UserReg::loginSession($_SESSION['login'], $_SESSION['pass'], $conn);
            if ($article->author = $user->id) {
                $article->delete();
            }
            else {header('Location: login.php');}
        }
    }
}
header("Location: user.php");
