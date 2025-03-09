<?php
include "connections/user_connection.php";
include "connections/connection.php";
include "UserReg.php";
include "utils.php";
include "article.php";

$userElem = $buttonElem = '';

$my_account = false;
$admin = false;


if(isset($_COOKIE['rememberMe'])){
if (isset($_SESSION['login']) || isset($_SESSION['pass'])) {
    $user = UserReg::loginSession( $_SESSION['login'], $_SESSION['pass'], $conn);
    $elem = "<li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href=user.php?id=".$user->id.">".$user->username."</a></li>
             <li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href=\"logout.php\">logout</a></li>";
    $admin = $user->admin;

    if (isset($_GET['id'])){
        $userPage = UserReg::loginById($_GET['id'], $conn);
        if ($userPage == null){
            header("Location: 404.php");
        }
        if ($user->id == $userPage->id){
            $my_account= true;
            $buttonElem = "<div class=\"d-flex justify-content-end mb-4\"><a class=\"btn btn-primary text-uppercase\" href=\"addArticle.php\">+ add article</a></div>";
        }
    }
    else{
        $userPage = $user;
        $my_account = true;
        $buttonElem = "<div class=\"d-flex justify-content-end mb-4\"><a class=\"btn btn-primary text-uppercase\" href=\"addArticle.php\">+ add article</a></div>";

    }


}}
else {
    $elem = "<li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href='login.php'\">Login</a></li>";
    session_destroy();
    if (isset($_GET['id'])){
        $userPage = UserReg::loginById($_GET['id'], $conn);
        if ($userPage == null){
            header("Location: 404.php");
        }
    }
    else{
        header("Location: login.php");
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MyBlog</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="index.php">MyBlog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Home</a></li>
                <?php echo $elem ?>

            </ul>
        </div>
    </div>
</nav>
<!-- Page Header-->
<header class="masthead" style="background-image: url('assets/img/Writing Instructions Guide.webp');height: 50px">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="site-heading">
                    <h1>User page</h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content-->
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">

            <div>
                <h1>User information</h1>
                <h4 style="font-weight: lighter"><strong>Username:</strong> <?php echo $userPage->username?></h4>
                <h4 style="font-weight: lighter"><strong>e-mail:</strong> <?php echo $userPage->email?></h4>
                <h4 style="font-weight: lighter"><strong>Birthday:</strong> <?php echo utils::getDateFormat($userPage->birthday); ?></h4>
                <?php if ($my_account){
                    echo "<div class=\"d-flex justify-content-md-start mb-4\"><a class=\"btn btn-sm text-uppercase\" href=\"addArticle.php\">edit password</a></div>";
                } ?>
            </div>
            <?php
            echo $buttonElem;
            $sql = "SELECT id FROM `article` WHERE author='{$userPage->id}' ORDER BY date DESC";
            $query=mysqli_query($conn, $sql) or die (mysqli_error($conn));
            $k=0;
            while ($row=mysqli_fetch_array($query) and $k<=20){
                $art = new article( $con,$row['id']);
                ?>
            <!-- Post preview-->
            <div class="post-preview">

                <a href="post.php?id=<?php echo $art->id; ?>">

                    <h4 class="post-title" style="font-size: larger"><?php echo $art->title; ?></h4>

                    <h5 class="post-subtitle"><?php echo $art->subtitle; ?></h5>
                </a>
                <p class="post-meta">
                    <?php echo utils::getDateFormat($art->date); ?>
                </p>
            </div>
                <?php  if ($my_account or $admin) { ?>
                            <div class="d-flex justify-content-end mb-4">
                            <a class="btn btn-success text-uppercase" href="edit.php?id=<?php echo $art->id ?>">Edit</a>
                            <a class="btn btn-danger text-uppercase" href="delete_article.php?id=<?php echo $art->id ?>">Delete</a>
                            </div>
               <?php } ?>
            <!-- Divider-->
            <hr class="my-4" />
            <?php $k++; } ?>
            <!-- Pager-->
            <?php if ($k != 0){ ?>
            <div class="d-flex justify-content-end mb-4"><a class="btn btn-link text-uppercase" href="#!">All posts →</a></div>
            <?php }
            else {
                ?>
                <br><br><div class="d-flex justify-content-center mb-4 post-preview">THERE IS NO POST</div>
            <?php } ?>

        </div>
    </div>
</div>
<!-- Footer-->
<footer class="border-top">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <ul class="list-inline text-center">
                    <li class="list-inline-item">
                        <a href="#!">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                    </span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                    </span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                                    </span>
                        </a>
                    </li>
                </ul>
                <div class="small text-center text-muted fst-italic">Copyright &copy; Your Website 2023</div>
            </div>
        </div>
    </div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>
</body>
</html>