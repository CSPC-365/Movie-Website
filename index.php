<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="WebStyle.css">
    <meta charset="UTF-8">
    <title>Wes's Movie Website</title>
<Style>
    p.one {
  color: #5289b5;
  text-align: center;
  margin-top: 200px; 
  font-family: "Georgia", serif;
  font-size: 30px
}
div {
    font-family: "Georgia", serif;
    letter-spacing: 1px;
    font-size: 30px;
    text-align: right;
    margin-top: -63px;
    
}
      div.two {
    font-family: "Georgia", serif;
    letter-spacing: 1px;
    font-size: 30px;
    text-align: left;
    margin-top: 0px;
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

</Style>
</head>
<body>
<?php
session_start();
?>

            <div class='two'>
                <a class="one" href="index.php">LOGO</a>
            </div>
    <div>
                
                
              <a class="one" href="null">Friends</a>
               <a class="one" href="null">Recommended</a>
               
        </div>
    <p class='one'>Please <a href="login.php">Login</a> or <a href="register.php">Register</a> to continue.</p>


</body>
</html>

