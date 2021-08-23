<!DOCTYPE html>
<html lang="en" style="background: url(icon.png) no-repeat center center relative; background-size: cover">

<head>
    <title> M E M O R Y &nbsp; H O L E </title>
    <link rel="icon" href="sol.png">
    <link rel="stylesheet" href="home.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="jquery-3.5.1.min.js"></script>
</head>

<body class="hidden">
    <?php
    require_once "DB.php";
    if ($cnct->connect_error) {
        echo "<script>alert('Connection error.');</script>";
        die("<script>location.replace('index.php');</script>");
    }
    session_start();
    include_once("menu.php");
    ?>
    <div id="v2"><em>v3.0</em></div>
    <div id="header"></div>
    <div id="memory"></div>
    <div id="hole"></div>
    <div id="title">
        <p id="alive"></p>
    </div>
    <div id="landing">
        <p class="line"></p>
        <div id="navbar">
            <a href="./search.php">Search</a> &nbsp;
            <?php if (isset($_SESSION['user'])) : ?>
                <form id="log" method="post" action="index.php">
                    <input id="out" type="submit" name="logout" value="Logout"> &nbsp;
                </form>
            <?php else : ?>
                <a href='login.php'>Login</a> &nbsp;
            <?php endif; ?>
            <a href="./browse.php">Browse</a>
        </div>
        <p class="line"></p>
    </div>
    <div id="second" style="text-align: center;">
        <div><br>
            <?php
            if (isset($_POST['logout'])) :  //check if logged in
                $_SESSION = array();    //clear session and reload
                setcookie(session_name(), "", 1, '/');
                session_destroy();
                $cnct->close();
                die("<script>location.replace('index.php');</script>");
            elseif (isset($_SESSION['user'])) :
                include("upload.php");   //enable uploading function - REQUIRES GD LIBRARY 
            ?>
                <p data-text="P O S T" class="sections2" style="top: -15px; margin-bottom: 5px;">P O S T</p>
                <div id="pre" style="text-align: center;">
                    <div class="cell" id="post">
                        <form action="index.php" method="post" enctype="multipart/form-data">
                            <input placeholder="Enter title" style="margin: 10px;" type="text" name="ttl">
                            <input id="tags" placeholder="Enter tag IDs" style="margin: 10px;" type="text" name="tag">
                            <input style="margin: 10px;" type="file" name="img">
                            <input type="hidden" name="id">
                            <input id="upload" type="submit" value="UPLOAD">
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <p data-text="N E W S" class="sections2" style="top: 5px; margin-bottom: 25px;">N E W S</p>
        <div id="main" style="text-align: center;">
            <?php
            $query = "SELECT * FROM images ORDER BY ID DESC LIMIT 8";
            $result = $cnct->query($query);
            if (!$result) {
                echo "<script>alert('Database access failed.');</script>";
                die("<script>location.replace('index.php');</script>");
            } else {
                include('cell.php');
            }
            function sanitize($cnct, $str) {   //sanitize input - no htmlentities()
                $str = $cnct->real_escape_string($str);
                $str = trim(preg_replace("/[^A-Za-z0-9\s-]/", "", strip_tags(stripslashes($str))));
                return $str;
            }
            ?>
        </div>
        <div style="position: relative; bottom: 50px; padding: 50px;">
            <div id="more">
                <form action="index.php" method="get">
                    <p><input id="load" type="button" name="more" value="Load more"></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(more).click(function() { //process query using AJAX
            $.ajax({
                url: "./scroll.php",
                type: "GET",
                data: {
                    last: $(".cell:last").attr("id")
                }
            }).done(function(data) {
                data = data.substring(0, data.length - 1);
                $("#main").append(data);
            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                alert("No response from server.");
            });
        });
        if (window.pageYOffset < 155) {
            window.addEventListener("scroll", function() {
                title.style.opacity = 1 - window.pageYOffset / 155;
            });
        } else {
            window.removeEventListener("scroll");
        };
    </script>
</body>

</html>
