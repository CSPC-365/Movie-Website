<!DOCTYPE html>
<html>
<head>
     <link rel="stylesheet" href="WebStyle.css">
    <meta charset="UTF-8">
    <title>Wes's Movie Website - Login</title>
        <Style>
    p.one {
  color: #5289b5;; 
  font-family: "Georgia", serif;
  font-size: 30px;
  text-align: center
}
a.one:link, a.one:visited {
  background-color: #00cccc;
  color: black;
  padding: 14px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}

a.one:hover, a:active {
    background-color: white;
}
div {
    font-family: "Georgia", serif;
    letter-spacing: 1px;
    font-size: 30px;
    text-align: left;
    margin-top: -78px;
}

</Style>
</head>
<body>
<?php
session_start();

?>

<h2>Login to Wes's Movie Website</h2>    
<div><a class="one" href="index.php">LOGO</a></div>
<form method="POST" action="Login_Process.php">
    
    <input type="username" name="username" placeholder="Username" id="username" required><br><br>

   
    <input type="password" name="password" placeholder="Password" id="password" required><br><br>

    <input type="submit" value="Login">
</form>


<p class='one'>Don’t have an account? <a href="register.php">Register here</a>.</p>

</body>
</html>
