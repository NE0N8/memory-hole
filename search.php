<!DOCTYPE html>
<html lang="en" style="background: url('./background.png') 100% 100%;">
    <head>
        <title> S E A R C H </title>
        <link rel="icon" href="sol.png">
        <style>
            @font-face {
                font-family: "sym";
                src: url("./seguisym.ttf");
            }   
        </style>
        <link rel="stylesheet" href="home.css" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="jquery-3.5.1.min.js"></script>
    </head>
    <body name="top" class="hidden" style="box-shadow: 0 0 25vw black inset, 0 0 25vw black inset !important;">      
        <?php include_once('menu.php'); ?>  
        <div id="content">
            <p class="line"></p>
            <p id="navbar">
                <a href="./search.php">Search</a> &nbsp;
                <a href="./index.php">Home</a> &nbsp;
                <a href="./browse.php">Browse</a>
            </p>
            <p class="line"></p>
            <div id="first"><br>
                <p class="sections"><b>S&nbsp; e&nbsp; a&nbsp; R&nbsp; C&nbsp; H</b></p>
                <div id="search">
                    <form action="search.php" method="get">
                        <p>
                            <input type="text" name="search" size="23">
                            <input id="enter" type="submit" value="ENTER" 
                                   style="font-family: label !important; font-weight: 100;">
                        </p>
                    </form>
                </div>
            </div>
            <div id="main">
                <?php
                require_once 'DB.php';
                if ($conn->connect_error)
                    die($conn->connect_error);

                $src = '';
                if (isset($_GET['search'])) {
                    $src = get_post($conn, $_GET['search']);
                    $query = "SELECT DISTINCT i.name, i.file, i.ID FROM images i
                    INNER JOIN image_tag it ON i.ID = it.imageID
                    INNER JOIN tags t ON it.tagID = t.ID
                    WHERE t.word LIKE '%$src%' XOR i.name LIKE '%$src%'
                    LIMIT 8";
                    $result = $conn->query($query);
                    if (!$result) {
                        die("Database query failed.");
                    }
                    $rows = $result->num_rows;
                    if (!$rows) {
                        die('<p style="width:200px; height: 60vh;">No results.</p>');
                    } else {
                        include('cell.php');
                    }
                } else {
                    $query = "SELECT * FROM images LIMIT 8";
                    $result = $conn->query($query);
                    if (!$result) {
                        die("Database access failed.");
                    } else {
                        include('cell.php');
                    }
                }

                function get_post($conn, $str) {
                    $str = $conn->real_escape_string($str);
                    $str = stripslashes($str);
                    $str = strip_tags($str);
                    $str = htmlentities($str);
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
            $(more).click(function () {     
                var search = '<?php echo "$src" ?>';
                if (search) {
                    var IDlist = "";
                    var cells = document.querySelectorAll(".cell");
                    for (var i = 0; i < cells.length; i++) {
                        IDlist += (cells[i].id + ',');
                    }
                    IDlist = IDlist.slice(0, -1);
                    $.ajax({
                        url: './scroll.php',
                        type: 'GET',
                        data: {ids: IDlist, src: search}
                    }).done(function (data) {
                        data = data.substring(0, data.length - 1);
                        $('#main').append(data);
                    }).fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('No response from server.');
                    });
                } else {
                    $.ajax({
                        url: './scroll.php',
                        type: 'GET',
                        data: {last: $(".cell:last").attr("id")}
                    }).done(function (data) {
                        data = data.substring(0, data.length - 1);
                        $('#main').append(data);
                    }).fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('No response from server.');
                    });
                }
                //}
            });
        </script>
    </body>
</html>