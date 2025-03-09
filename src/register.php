<?php
include "UserReg.php";
include "connections/user_connection.php";
include "utils.php";
$login = $firstname = $lastname = $birthday = $email = $password = $confirm_password = "";
$loginErr = $firstnameErr = $lastnameErr = $dateErr = $emailErr =$passwordErr= $confirm_password_Err = $captchaErr = "";
$errNum = 0;
$a = rand(10, 99);
$b = rand(10, 99);
setcookie("captcha", $a+$b, time() + (86400 * 30), "/");
    if (isset($_POST['submit'])) {
        if (isset($_POST['captcha_challenge']) && isset($_COOKIE['captcha']) && $_POST['captcha_challenge'] == $_COOKIE['captcha']) {
            if (isset($_POST["login"])) {
                $login = utils::test_input($_POST['login']);
                if (!preg_match("/^[a-zA-Z]*$/", $login)) {
                    $loginErr = "Username contains invalid characters.";
                    $errNum++;
                } else {
                    if (strlen($login) < 5) {
                        $loginErr = "Login length must be at least 5 characters.";
                        $errNum++;

                    } else {
                        if (UserReg::loginRepeat($login, $conn)) {
                            $loginErr = "Account with this login already exists. Try another or login";
                            $errNum++;

                        }
                    }
                }
            }
            else { $errNum++;}

        if (isset($_POST["firstname"])) {
            $firstname = utils::test_input($_POST['firstname']);
            if (!preg_match("/^[a-zA-Z]*$/", $firstname)) {
                $firstname = '';
                $firstnameErr = "Username contains invalid characters.";
                $errNum++;
            }
        }
        else { $errNum++; }


        if (isset($_POST['lastname'])) {
            $lastname = utils::test_input($_POST['lastname']);
            if (!preg_match("/^[a-zA-Z]*$/", $lastname)) {
                $lastname = '';
                $lastnameErr = "Username contains invalid characters.";
                $errNum++;
            }
        }


        if (isset($_POST['birthday'])) {
            $birthday = $_POST['birthday'];
        }
        else { $errNum++; }

        if (isset($_POST['email'])) {
            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
            $email = utils::test_input($_POST['email']);
            if (!preg_match($pattern, $email))
            {
                $email = '';
                $emailErr = "Invalid email format.";
                $errNum++;
            }
            else
            {
                if (UserReg::emailRepeat($email, $conn)) {
                    $email = '';
                    $emailErr = "Account with this email already exists.";
                    $errNum++;
                }
            }
        }

        if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
            $password = utils::test_input($_POST['password']);
            $confirm_password = utils::test_input($_POST['confirm_password']);
            if (preg_match("/^\w\W*$/", $password) && preg_match("/^\w\W*$/", $confirm_password)) {
                $password = '';
                $passwordErr = "Password contains invalid characters.";
                $errNum++;
                $confirm_password = '';
                $confirm_passwordErr = "Password contains invalid characters.";
            } else {
                if ($password != $confirm_password) {
                    $confirm_password_Err = "Passwords do not match.";
                    $errNum++;
                } else {
                    if (strlen($password) < 6) {
                        $password = '';
                        $ch = isset($passwordErr) ? "<br>" : "";
                        $passwordErr = "{$ch}Password must be at least 6 characters.";
                        $errNum++;
                    }
                    if (!preg_match("/\d/", $password)) {
                        $ch = isset($passwordErr) ? "<br>" : "";
                        $passwordErr = "{$ch}Password must include at least one number!";
                        $errNum++;

                    }

                    if (!preg_match('/[a-z]+/', $password) && !preg_match('/[A-Z]+/', $password)) {
                        $ch = isset($passwordErr) ? "<br>" : "";
                        $passwordErr = "{$ch}Password must include mixed capital letters and numbers!";
                        $errNum++;

                    }
                    if (!preg_match("/[^\da-z]/", $password)) {
                        $ch = isset($passwordErr) ? "<br>" : "";
                        $passwordErr = "{$ch}Password must include at least one special character!";
                        $errNum++;
                    }
                }
            }
        }


        if ($errNum == 0) {
            UserReg::register($login, $password, $firstname, $lastname, $email, $birthday, $conn);
            header("location: index.php");
        }
        else { $captchaErr = "EMMMMM! ".$errNum; }
    }
}
else{
    $captchaErr = "<br>Captcha is not valid.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
                    <h1>Registration</h1>
                    <span class="subheading">Start an adventure</span>
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
                    <form id="registrationForm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                        <div class="form-floating">
                            <input class="form-control" id="login" type="text" name='login' placeholder="Enter your login..." data-sb-validations="required" value="<?php echo $login ?>" required />
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $loginErr ?></err>
                            <label for="login">Login<sup>*</sup></label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="firstname" type="text" name='firstname' placeholder="Enter your firstname..." data-sb-validations="required" value="<?php echo $firstname; ?>" required />
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $firstnameErr?></err>
                            <label for="firstname">First name<sup>*</sup></label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="lastname" type="text" name='lastname' placeholder="Enter your lastname..." data-sb-validations="required" value="<?php echo $lastname; ?>" />
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $lastnameErr?></err>
                            <label for="lastname">Last name</label>
                        </div>

                        <div class="form-floating">
                            <input class="form-control" id="password" type="password" name="password" placeholder="Password" data-sb-validations="required"  required/>
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $passwordErr?></err>
                            <label for="password">Password<sup>*</sup></label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="confirm_password" type="password" name="confirm_password" placeholder="Repeat password" required/>
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $confirm_password_Err?></err>
                            <label for="confirm_password">Confirm password<sup>*</sup></label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" id="email" type="email" name="email" placeholder="email" value="<?php echo $email; ?>" required/>
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $emailErr ?></err>
                            <label for="email">e-mail<sup>*</sup></label>
                        </div>
                        <div class="form-floating">
                            <label for="birthday">Birthday<sup>*</sup></label><br>
                            <input class="form-control" type="date" id="birthday" name="birthday" min="1920-12-31" max="2006-01-01" placeholder="Repeat birthday" value="<?php echo $birthday; ?>" required>
                        </div>

                        <div class="form-floating">
                            <input class="form-control" type="number" id="captcha" name="captcha_challenge" placeholder="Repeat captcha" required>
                            <err style="color: #DC3545FF; font-size: 10pt"><?php echo $captchaErr ?></err>
                            <label for="captcha"><?php echo "$a + $b" ?> =</label>
                        </div>
                        <err style="font-size: 10pt">* all fields are required.</err>

                        <br><br>

                        <!-- Submit Button-->
                        <input class="btn btn-primary text-uppercase" id="submitButton" type="submit" name="submit" value="Register">
                        <p>If you have an account, you can <a href="login.php">login</a></p>
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
