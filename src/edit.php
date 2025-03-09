<?php
include "connections/user_connection.php";
include "connections/connection.php";
include "triggers/update_trigger.php";
include "UserReg.php";
include "utils.php";
include "article.php";

$elem = $titleErr = $subtitleErr = $categoryErr = $articleErr = $error = '';
$id = $image = $title = $subtitle = $category = $text = $target = "";
$err = 0;
if (isset($_COOKIE['rememberMe'])) {
    if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {
        $user = UserReg::loginSession($_SESSION['login'], $_SESSION['pass'], $conn);
        $elem = "<li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href=user.php?id=" . $user->id . ">" . $user->username . "</a></li>
             <li class=\"nav-item\"><a class=\"nav-link px-lg-3 py-3 py-lg-4\" href=\"logout.php\">logout</a></li>";

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (article::exists($id, $con)) {
                $article = new article($con, $_GET['id']);
                $title = $article->title;
                $subtitle = $article->subtitle;
                $category = $article->category;
                $text = $article->article;
                $target = $article->image;
                $usersPage = new UserReg($article->author, $conn);
            } else {
                header('location: 404.php');
            }
        } else {
            header('location: 404.php');
        }
        if (isset($_POST["submit"])) {
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {

                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $mime_type = mime_content_type($_FILES['image']['tmp_name']);
                    $max_size = 2 * 1024 * 1024; // 2MB
                    if ($_FILES['image']['size'] > $max_size) {
                        $err++;
                        $error = "File should be max 2MB.";
                    }

                    if (!in_array($mime_type, $allowed_types)) {
                        $err++;
                        $error = "Only JPEG, PNG or GIF.";
                    } else {
                        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['image']['name']);
                        $target = "./assets/img/" . uniqid() . '_' . $filename;

                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            $err++;
                            $error = "Error saving file.";
                        }
                    }
                } else {
                    $err++;
                    $error = "Error uploading: " . $_FILES['image']['error'];
                }
            } else {
                // Если изображение не загружено, сохраняем старое
                $target = $article->image;
            }


            if (isset($_POST['title'])) {
                $title = utils::test_input($_POST['title']);
                if (!preg_match("/^[a-zA-Z0-9\s.,!?-]+$/", $title)) {
                    $titleErr = "Only letters and white space allowed";
                    $err++;
                }
            } else {
                $err++;
            }


            if (isset($_POST['subtitle'])) {
                $subtitle = utils::test_input($_POST['subtitle']);
                if (!preg_match("/^[a-zA-Z0-9\s.,!?-]+$/", $subtitle)) {
                    $subtitleErr = "Only letters and white space allowed";
                    $err++;
                }
            }

            if (isset($_POST['category'])) {
                $category = $_POST['category'];
            } else {
                $err++;
            }

            if (isset($_POST['article'])) {
                $text = $_POST['article'];
            } else {
                $err++;
            }
            // echo $err;

            if ($err == 0) {
                $article->edit($title, $subtitle, $text, $category, $target, $user->id);
                header("location: post.php?id={$id}");
            }


        }
    }
} else {
    session_destroy();
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Edit article</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800"
        rel="stylesheet" type="text/css" />

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet">

    <script src="https://cdn.tiny.cloud/1/56cgkwdp5cj8wpwrall225ibbx7expxfl7xzv1wahllh3x12/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            plugins: [
                'lists', 'link', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
                'fullscreen', 'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
        });
    </script>
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">MyBlog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
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
    <header class="masthead" style="background-image: url('assets/img/Thom Milkovic Unsplash.jpg');height: 50px">
        <div class="container position-relative  px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1>Edit</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content-->
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <form method="post" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">

                    <input type="text" name="id" hidden="hidden" value="<?php echo $id ?>">
                    <err style="color: #DC3545FF; font-size: 10pt"><?php echo $error; ?></err>

                    <label for="image">Article cover</label>
                    <div><input placeholder="Upload the article cover..." data-sb-validations="required"
                            class="form-control" type="file" name="image" id="image" accept="image/*"></div>
                    <br>

                    <label for="title">Title</label>
                    <input class="form-control" id="title" type="text" name='title'
                        placeholder="Enter the article title..." data-sb-validations="required" max="100"
                        value="<?php echo $title; ?>" required /><br>
                    <err style="color: #DC3545FF; font-size: 10pt"><?php echo $titleErr; ?></err>

                    <label for="subtitle">Subtitle</label>
                    <input class="form-control" id="subtitle" type="text" name='subtitle'
                        placeholder="Enter the article title..." data-sb-validations="required" max="100"
                        value="<?php echo $subtitle ?>" /><br>
                    <err style="color: #DC3545FF; font-size: 10pt"><?php echo $subtitleErr; ?></err>

                    <label for="category">Choose the category...</label>
                    <select id="category" name="category" size="4" class="form-control dropdown">
                        <option value="no category" selected="selected">No category</option>
                        <option value="non-fiction">Non-fiction</option>
                        <option value="design">Design</option>
                        <option value="biology">Biology</option>
                        <option value="technology">Technology</option>
                        <option value="linux">Linux</option>
                        <option value="programming">Programming</option>
                        <option value="astronomy">Astronomy</option>
                    </select>
                    <br>

                    <label for="mytextarea">Article</label>
                    <textarea id="mytextarea" name="article"><?php echo $article->article; ?> </textarea>

                    <br>
                    <err style="color: #DC3545FF; font-size: 10pt"><?php echo $articleErr; ?></err>
                    <input class="btn btn-primary text-uppercase" id="submitButton" type="submit" name="submit"
                        value="UPDATE!">
                    <br><br>
                </form>

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