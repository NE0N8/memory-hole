<!DOCTYPE html>
<html lang="en" style="background: url('./background.png') 100% 100%; overflow: hidden;">

<head>
   <title> L O G I N </title>
   <link rel="icon" href="sol.png">
   <link rel="stylesheet" href="home.css" type="text/css">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta charset="UTF-8">
</head>

<body style="overflow: hidden; box-shadow: 0 0 25vw black inset, 0 0 25vw black inset !important;">
   <div id="content" style="top: 20px;">
      <p class="line"></p>
      <p id="navbar">
         <a href="./search.php">Search</a> &nbsp;
         <a href="./index.php">Home</a> &nbsp;
         <a href="./browse.php">Browse</a>
      </p>
      <p class="line"></p>
      <div id="backGif"></div>
      <?php
      ini_set('session.use_only_cookies', 1);
      session_start();
      require_once 'DB.php';
      if ($cnct->connect_error) {
         echo "<script>alert('Connection error.');</script>";
         die("<script>location.replace('login.php');</script>");
      }
      include_once("menu.php"); //import menu 
      if (isset($_SESSION['user']) && isset($_SESSION['pass'])) {
         die("<script>location.replace('index.php');</script>");
      } elseif (isset($_POST['user']) && isset($_POST['pass'])) {
         $usr = sanitize($cnct, $_POST['user']);
         $pwd = sanitize($cnct, $_POST['pass']);
         if ($usr == "" || $pwd == "") {
            echo "<script>alert('Enter your username and password.');</script>";
            die("<script>location.replace('login.php');</script>");
         }
         $stmt = $cnct->prepare("SELECT * FROM sekrit WHERE user=?");
         $stmt->bind_param("s", $usr);
         $stmt->execute();
         $result = $stmt->get_result();
         if (!$result) {
            die("Connection error.");
         } elseif ($result->num_rows > 0) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            $salt = "*5&@p%";
            $token = hash('sha256', "$salt$pwd$salt");
            if ($token == $row[1]) {
               $_SESSION['user'] = $usr;
               $_SESSION['pass'] = $pwd;
               $_SESSION['id'] = $row[2];
               $stmt->close();
               die("<script>location.replace('index.php');</script>");
            } else {
               echo "<script>alert('Incorrect username or password.');</script>";
               die("<script>location.replace('login.php');</script>");
            }
         } else {
            echo "<script>alert('User not found.');</script>";
            die("<script>location.replace('login.php');</script>");
         }
      }
      ?>
      <div>
         <form class="center" action="login.php" method="post">
            <span class="names">Username</span><input class="loginField" type="text" name="user">
            <span class="names">Password</span><input class="loginField" type="text" name="pass">
            <input style="margin-top: 2.5vh;" type="submit" value="LOG IN">
         </form>
      </div>
      <div class="center" style="top: 70vh;">
         <input type="submit" onclick="location.replace('signup.php');" value="SIGN UP">
      </div>
      <?php
      $cnct->close();
      function sanitize($cnct, $str) {   //sanitize input - no htmlentities()
         $str = $cnct->real_escape_string($str);
         $str = trim(preg_replace("/[^A-Za-z0-9-]/", "", strip_tags(stripslashes($str))));
         return $str;
      }
      ?>
   </div>
</body>

</html>