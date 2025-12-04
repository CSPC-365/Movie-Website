<html>
<head>
     <link rel="stylesheet" href="WebStyle.css">
    <meta charset="UTF-8">
    <title>Register - Wes's Movie Website</title>
    <Style>
    p.one {
  color: #5289b5;; 
  font-family: "Georgia", serif;
  font-size: 30px;
  text-align: center;
}
h1{
    text-align: center
}

</Style>
</head>
<body>
    <h1>Register</h1>
    <form method="POST" action="Register.php">
        <label>First Name:</label><br>
        <input type="text" name="first_name" placeholder="First Name" required><br><br>
        <label>Last Name:</label><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br><br>
        <label>Username:</label><br>
        <input type="text" name="username" placeholder="Username" required><br><br>
        <label>Email:</label><br>
        <input type="text" name="email" placeholder="Email" required><br><br>
        <label>Phone Number:</label><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br><br>
        <label>Password:</label><br>
        <input type="text" name="password" placeholder="Password" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
<p class='one'>Have an account?<a href="Login.php">Login here</a>.</p>
</html>

<?php
session_start();

$host = "localhost";
$dbname = "movie_website"; 
$username = "root"; 
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into database
    $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, username, email, phone_number, password)
                            VALUES (:first_name, :last_name, :username, :email, :phone, :password_hash)");
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password_hash', $password_hash);
    $stmt->execute();

    // Redirect to login page
    header("Location: login.php");
    exit();
}



