<?php
session_start();
include "Connection.php";

$userID = $_SESSION['UserID'];
$movieID = $_POST['movie_id'];
$rating = $_POST['rating'];

$stmt = $conn->prepare("SELECT Rating FROM movie_ratings WHERE UserID = :user AND Movie_ID = :movie");
$stmt->execute([
    ':user' => $userID,
    ':movie' => $movieID
]);

if ($stmt->fetch()) {
    header("Location: movie.php?id=$movieID&error=already_rated");
    exit;
}
$stmt = $conn->prepare("INSERT INTO movie_ratings (UserID, Movie_ID, Rating) VALUES (:user, :movie, :rating)");
$stmt->execute([
    ':user' => $userID,
    ':movie' => $movieID,
    ':rating' => $rating
]);

header("Location: movie.php?id=$movieID&success=rating_added");
exit;
?>

