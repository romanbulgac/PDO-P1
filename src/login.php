<?php
include "UserReg.php";
include "connections/user_connection.php";
include "utils.php";
$login = '';
$password = '';
$block='';
if (isset($_POST['submit'])) {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $login = utils::test_input($_POST['login']);
        $password = utils::test_input($_POST['password']);
        if (preg_match("/[a-zA-Z0-9@.]*$/", $login)) {
//            $numeErr = "Only alphabets and white space are allowed";
            $err = 1;
            $user = UserReg::login($login, $password, $conn);
            if ($user) {
                header("location: index.php");
            }
            else {
                $block = "Login or password is incorrect";
            }
        } else {
            $block = "Only alphabets and white space are allowed";
        }
        if (isset($_POST['rememberMe'])){
            setcookie("rememberMe", $login, time() + (60*60*24*365), "/");
        }
        else{
            setcookie("rememberMe", $login, time() + (60*60*24), "/");
        }
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
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="about.php">About</a></li>
                        <li class="nav-item"></li>

                    </ul>
                </div>
            </div>
        </nav>

        <header class="masthead" style="height: 100px">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="site-heading">
                            <h1>Login</h1>
                            <span class="subheading">continue your adventure by logging in</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="mb-4">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="my-5">
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- * * SB Forms Contact Form * *-->
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- This form is pre-integrated with SB Forms.-->
                            <!-- To make this form functional, sign up at-->
                            <!-- https://startbootstrap.com/solution/contact-forms-->
                            <!-- to get an API token!-->
                            <form id="contactForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="form-floating">
                                    <input class="form-control" id="login" type="text" name='login' placeholder="Enter your login..." data-sb-validations="required" value="<?php echo $login ?>" required />
                                    <label for="login">Login or e-mail</label>
                                </div>

                                <div class="form-floating">
                                    <input class="form-control" id="password" type="password" name="password" placeholder="Password" data-sb-validations="required" required/>
                                    <label for="password">Password</label>
                                </div>
                                <err style="color: #DC3545FF; font-size: 10pt"><?php echo $block; ?></err>

                                <input type="checkbox" name="rememberMe" id="rememberMe">
                                <label for="rememberMe">Remember Me</label>
                                <br><br>
                                 <!-- Submit Button-->
                                <input class="btn btn-primary text-uppercase" id="submitButton" type="submit" name="submit" value="Login">
                                <p>If you do not have an account, you can <a href="register.php">create one.</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>




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
