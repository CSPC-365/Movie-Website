<?php
session_start();
include 'Connection.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

if (empty($_POST['toUserID']) || empty($_POST['movie_id'])) {
    header("Location: home.php");
    exit;
}

$fromUser = $_SESSION['UserID'];
$toUser = intval($_POST['toUserID']);
$movie_id = intval($_POST['movie_id']);

if ($fromUser === $toUser) {
    header("Location: movie.php?id=" . $movie_id);
    exit;
}

$check = $conn->prepare("
    SELECT * FROM friend_requests 
    WHERE (FromUserID = :from AND ToUserID = :to)
       OR (FromUserID = :to AND ToUserID = :from)
    LIMIT 1
");
$check->execute([
    ':from' => $fromUser,
    ':to' => $toUser
]);

if ($check->rowCount() === 0) {
    $stmt = $conn->prepare("
        INSERT INTO friend_requests (FromUserID, ToUserID, Status)
        VALUES (:from, :to, 'pending')
    ");
    $stmt->execute([
        ':from' => $fromUser,
        ':to' => $toUser
    ]);
}

header("Location: movie.php?id=" . $movie_id);
exit;
?>
