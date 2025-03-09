<?php
include "connections/user_connection.php";
include "connections/connection.php";
include "./UserReg.php";
include "utils.php";
include "article.php";
$elem = '';
if (isset($_COOKIE["rememberMe"])) {
    if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {
        $user = UserReg::loginSession($_SESSION['login'], $_SESSION['pass'], $conn);
//    print_r($user);
        $elem = "<li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href=\"user.php?id=" . $user->id . "\">" . $user->username . "</a></li>
             <li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href=\"logout.php\">logout</a></li>";


    }
}
else {
    $elem = "<li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href='login.php'>Login</a></li>";
    session_destroy();

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
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.php">Contact</a></li>
                        <?php echo $elem ?>

                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="site-heading">
                            <h1>Clean Blog</h1>
                            <span class="subheading">A Blog Theme by Start Bootstrap</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">

                    <form action="search.php" method="post">
                        <div class="form-floating">
                            <input class="form-control" type="text" name="search" id="search">
                            <label for="search">Search and article</label>

                            <div class="d-flex justify-content-end mb-4">
                            <input class="btn btn-primary text-uppercase" id="submitButton" type="submit" name="submit" value="Search">
                            </div>
                        </div>
                        <h1>Recent articles</h1>

                    </form>
                    <?php
                    $sql = "SELECT id FROM `article` ORDER BY date DESC LIMIT 20";
                    $query=mysqli_query($conn, $sql) or die (mysqli_error($conn));
                    while ($row=mysqli_fetch_array($query)){
                        if ($row['id'] != 0){
                        $art = new article($con, $row['id'] );
                        $usr = new UserReg($art->author, $conn);

                        ?>
                    <!-- Post preview-->
                    <div class="post-preview">

                        <a href="post.php?id=<?php echo $art->id ?>">
                            <h4 class="post-title" style="font-size: larger"><?php echo $art->title ?></h4>
                            <h5 class="post-subtitle"><?php echo $art->subtitle; ?></h5>
                        </a>
                        <p class="post-meta">
                            Posted by
                            <a href="user.php?id=<?php echo $art->author ?>"><?php echo $usr->username; ?></a>
                            on <?php echo utils::getDateFormat($art->date); ?>
                        </p>
                    </div>
                    <!-- Divider-->
                    <hr class="my-4" />
                    <?php }} ?>
                    <!-- Pager-->
                    <div class="d-flex justify-content-end mb-4"><a class="btn btn-link text-uppercase" href="#!">All posts →</a></div>
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
