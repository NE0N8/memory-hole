<!DOCTYPE html>
<html lang="en" style="background: url('./background.png') 100% 100%;">

<head>
    <title> S E A R C H </title>
    <link rel="icon" href="sol.png">
    <link rel="stylesheet" href="home.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="jquery-3.5.1.min.js"></script>
</head>

<body name="top" class="hidden">
    <div style="position: fixed; height: 100vh; width: 100%; box-shadow: 0 0 25vw black inset, 0 0 25vw black inset !important;"></div>
    <?php
    require_once "DB.php";
    if ($cnct->connect_error) {
        die($cnct->connect_error);
    }
    session_start();
    include_once("menu.php"); //import menu 
    ?>
    <div id="content">
        <p class="line"></p>
        <p id="navbar">
            <a href="./search.php">Search</a> &nbsp;
            <a href="./index.php">Home</a> &nbsp;
            <a href="./browse.php">Browse</a>
        </p>
        <p class="line"></p>
        <div id="first"><br>
            <p data-text="S E A R C H" class="sections2" style="text-shadow: none;">S E A R C H</p>
            <div id="search">
                <form action="search.php" method="get">
                    <p>
                        <input type="text" name="search" size="23" placeholder="<?= isset($_GET['search']) ? $_GET['search'] : 'Enter text' ?>">
                        <input id="enter" type="submit" value="ENTER" style="font-family: label !important; font-weight: 100;">
                    </p>
                </form>
            </div>
        </div>
        <div id="main">
            <?php
            $src = "";
            if (isset($_GET['search'])) {
                $src = sanitize($cnct, $_GET['search']);
                $query = "SELECT DISTINCT i.name, i.file, i.ID FROM images i
                    INNER JOIN image_tag it ON i.ID = it.imageID
                    INNER JOIN tags t ON it.tagID = t.ID
                    WHERE t.word LIKE '%$src%' XOR i.name LIKE '%$src%'
                    LIMIT 8";
                $result = $cnct->query($query);
                if (!$result) {
                    die("Database query failed.");
                }
                $rows = $result->num_rows;
                if (!$rows) {
                    die("<p style='width:200px; height: 60vh;'>No results.</p>");
                } else {
                    include('cell.php');
                }
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
                <form action="search.php" method="get">
                    <p><input id="load" type="button" name="more" value="Load more"></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        var search = '<?php echo "$src" ?>';
        search ? more.classList.remove("hide") : more.classList.add("hide");
        $(more).click(function() { //process query using AJAX
            if (search) {
                var IDlist = "";
                var cells = document.querySelectorAll(".cell");
                for (var i = 0; i < cells.length; i++) {
                    IDlist += (cells[i].id + ',');
                }
                IDlist = IDlist.slice(0, -1);
                $.ajax({
                    url: "./scroll.php",
                    type: "GET",
                    data: {
                        ids: IDlist,
                        src: search
                    }
                }).done(function(data) { //append query results to page
                    data = data.substring(0, data.length - 1);
                    $("#main").append(data);
                }).fail(function(jqXHR, ajaxOptions, thrownError) {
                    alert("No response from server.");
                });
            }
        });
    </script>
</body>

</html>