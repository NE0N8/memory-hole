<!DOCTYPE html>
<html lang="en" style="background: url('./background.png') 100% 100%; overflow: hidden;">

<head>
   <title> S I G N U P </title>
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
      <?php
      ini_set('session.use_only_cookies', 1);
      session_start();
      require_once 'DB.php';
      if ($cnct->connect_error) {
         die($cnct->connect_error);
      }
      include_once("menu.php"); //import menu 
      if (isset($_POST['user']) && isset($_POST['pass1']) && isset($_POST['pass2'])) {
         $usr = sanitize($cnct, $_POST['user']);
         $pwd1 = sanitize($cnct, $_POST['pass1']);
         $pwd2 = sanitize($cnct, $_POST['pass2']);
         $valid = validate($usr, $pwd1, $pwd2);
         if ($valid == "") {
            $query = "SELECT * FROM sekrit WHERE user='$usr'";     //check if username exists
            $result = $cnct->query($query);
            if (!$result) {
               die($cnct->error);
            } elseif (!$result->num_rows > 0) {     //username not found in DB
               $salt = "*5&@p%";
               $token = hash('sha256', "$salt$pwd1");
               $query = "INSERT INTO sekrit VALUES('$usr', '$token', NULL)";
               $result = $cnct->query($query);
               if (!$result) {
                  die($cnct->error);
               } else {
                  $cnct->close();
                  die("<script>location.replace('login.php');</script>");
               }
            } else {
               echo "<script>alert('Username not available.');</script>";
               die("<script>location.replace('signup.php');</script>");
            }
         } else {
            echo "<script>alert('$valid');</script>";
            die("<script>location.replace('signup.php');</script>");
         }
      }
      ?>
      <div id="backGif">
         <form class="center" action="signup.php" method="post">
            <span class="names">Username</span><input class="loginField" type="text" name="user">
            <span class="names">Password</span><input class="loginField" type="text" name="pass1">
            <span class="names">Confirm Password</span><input class="loginField" type="text" name="pass2">
            <div class="center" style="top: 48vh;">
               <input type="submit" value="SIGN UP">
            </div>
         </form>
      </div>
      <?php
      $cnct->close();
      function sanitize($cnct, $str) {   //sanitize input - no htmlentities()
         $str = $cnct->real_escape_string($str);
         $str = trim(preg_replace("/[^A-Za-z0-9-]/", "", strip_tags(stripslashes($str))));
         return $str;
      }
      function validate($user, $pass1, $pass2) {
         if ($user == "" || $pass1 == "" || $pass2 == "") {
            return "Please enter a username and password.\n";
         } elseif (strlen($user) < 5) {
            return "Username must be at least 5 characters.\n";
         } elseif (preg_match("/[^a-zA-Z0-9]/", $user)) {
            return "Username must contain letters and numbers only.\n";
         } elseif ($pass1 != $pass2) {
            return "Passwords do not match.\n";
         } elseif (strlen($pass1) < 5) {
            return "Password must be at least 5 characters.\n";
         } elseif (!preg_match("/[a-zA-Z]/", $pass1) || !preg_match("/[0-9]/", $pass1)) {
            return "Password must contain both letters and numbers.\n";
         }
         return "";
      }
      ?>
</body>

</html>