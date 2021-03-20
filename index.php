<!DOCTYPE html>
<html lang="en" style="overflow: hidden !important;">
    <head>
        <title> M E M O R Y &nbsp; H O L E </title>
        <link rel="icon" href="sol.png">
        <style>
            #nav1:hover{
                color: rgb(0,255,255);
            }
            #nav2:hover{
                color: rgb(0,255,255);
            }
            #nav3:hover{
                color: rgb(0,255,255);
            }
            #navbar{
                color: rgb(0,240,60);
            }
            body{
                height: 100%;
                width: 100%;
                background: url(icon.png) no-repeat center center fixed;
                background-size: cover;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
            }
            @font-face {
                font-family: "sym";
                src: url("./seguisym.ttf");
            }   
        </style>
        <link rel="stylesheet" href="home.css" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body class="hidden">     
        <?php include_once("menu.php"); ?>
        <div id="v2"><em>v2.0</em></div>
        <div id="header"></div> 
        <div id="memory"></div>
        <div id="hole"></div>
        <div id="title">
            <p id="alive"></p>
        </div>  
        <div id="landing">
            <p class="line"></p>
            <p id="navbar">
                <a id="nav1" href="./search.php">Search</a> &nbsp;
                <a id="nav2" href="./PLACEHOLDER.jpg" target="_blank">Files</a> &nbsp; 
                <a id="nav3" href="./browse.php">Browse</a>
            </p>
            <p class="line"></p>
        </div> 
        <script> index.classList.remove("hide"); </script>
    </body>
</html>