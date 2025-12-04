<link rel="stylesheet" href="WebStyle.css">
<style>
a.one {
  color: #5289b5;
  text-align: center;
  margin-top: 200px;
  font-family: "Georgia", serif;
  letter-spacing: 1px;
  font-size: 30px;
}
p.one {
  color: #5289b5;
  text-align: center;
  margin-top: 200px;
  font-family: "Georgia", serif;
  letter-spacing: 1px;
  font-size: 30px;
}
</style>

<?php
session_start();
include 'Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);  
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, password, AdminID FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['UserID'] = $user['UserID'];
        $_SESSION['AdminID'] = $user['AdminID'];

        if (!empty($user['AdminID'])) {
            header("Location: admin.php");
            exit;
        } else {
            header("Location: home.php");
            exit;
        }
    } else {
        sleep(2); 
        echo "<p class='one'>Invalid username or password.</p>";
        echo "<a class='one' href='login.php'>Try again</a>";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
