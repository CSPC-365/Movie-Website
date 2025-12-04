<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['movie_id']) || !isset($_POST['comment'])) {
    header("Location: home.php");
    exit;
}

$userID = $_SESSION['UserID'];
$movieID = $_POST['movie_id'];
$commentText = trim($_POST['comment']);

if ($commentText === "") {
    header("Location: movie.php?id=$movieID");
    exit;
}

$stmt = $conn->prepare("INSERT INTO comments (MovieID, UserID, Comment_Text, Comment_Time) VALUES (?, ?, ?, NOW())");
$stmt->execute([$movieID, $userID, $commentText]);

header("Location: movie.php?id=$movieID");
exit;
?>
