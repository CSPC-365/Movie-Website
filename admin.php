<?php
session_start();

if (!isset($_SESSION['UserID']) || empty($_SESSION['AdminID'])) {
    header("Location: access_denied.php");
    exit;
}

include 'Connection.php';
?>

<html>
<head>
    <link rel="stylesheet" href="WebStyle.css">
    <meta charset="UTF-8">
    <title>Wes's Movie Website - Admin</title>

<style>
    h1 {
        text-align: center;
        font-family: "Georgia", serif;
        color: #5289b5;
    }
    a.one {
        color: #5289b5;
        text-align: right;
        font-family: "Georgia", serif;
        letter-spacing: 1px;
        font-size: 30px;
    }
    form {
        text-align: center;
        margin-top: 40px;
    }
    input, button {
        font-size: 18px;
        margin: 10px;
    }
    textarea {
        min-width: 4%;
        height: 100px;
        margin-bottom: 10px;
        border-radius: 4px;
        padding: 10px;
    }
    .button {
        padding: 15px 25px;
        font-size: 24px;
        cursor: pointer;
        color: #050a30;
        background-color: #5289b5;
        border-radius: 15px;
        box-shadow: 0 9px whitesmoke;
    }
    .button:active {
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
    label {
    font-family: "Copperplate", fantasy;
    color: #5289b5;
    font-size: 22px;
    letter-spacing: 1px;
}

input[type="file"] {
    font-family: "Copperplate", fantasy;
    color: #5289b5;
    font-size: 20px;
}

</style>
</head>

<body>
<h1>Admin Panel - Add a Movie</h1>

<form method="POST" action="" enctype="multipart/form-data">
    <input type="text" name="Title" placeholder="Movie Title" required><br>
    <input type="text" name="Genre" placeholder="Genre" required><br>
    <input type="text" name="Hours" placeholder="Hours" required><br>
    <input type="text" name="Minutes" placeholder="Minutes" required><br>
    <input type="text" name="ReleaseDate" placeholder="Release Date" required><br>

    <label>Select Movie Poster Image:</label><br>
    <input type="file" name="Poster" accept="image/*" required><br>

    <textarea name="Description" placeholder="Description"></textarea><br>

    <button class="button" type="submit">Add Movie</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['Title']);
    $genre = trim($_POST['Genre']);
    $hours = trim($_POST['Hours']);
    $minutes = trim($_POST['Minutes']);
    $release_date = trim($_POST['ReleaseDate']);
    $description = trim($_POST['Description']);

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES["Poster"]["name"]);
    $targetFile = $uploadDir . $fileName;

    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "<p style='text-align:center;color:red;'>Invalid image format.</p>";
        exit;
    }

    move_uploaded_file($_FILES["Poster"]["tmp_name"], $targetFile);

    $stmt = $conn->prepare(
        "INSERT INTO movie (Title, Genre, Hours, Minutes, Realese_date, Description, Poster)
         VALUES (:Title, :Genre, :Hours, :Minutes, :Release_date, :Description, :Poster)"
    );

    $stmt->execute([
        ':Title' => $title,
        ':Genre' => $genre,
        ':Hours' => $hours,
        ':Minutes' => $minutes,
        ':Release_date' => $release_date,
        ':Description' => $description,
        ':Poster' => $targetFile
    ]);

    echo "<p style='text-align:center;color:green;'> ✓ Movie added successfully!</p>";
}
?>
</body>

<a class='one' href="home.php">Back</a>
</html>

