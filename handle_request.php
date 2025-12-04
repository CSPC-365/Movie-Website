<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['UserID'];

if (!isset($_POST['action'])) {
    header("Location: home.php");
    exit;
}

$action = $_POST['action'];

if ($action === 'send_request' && isset($_POST['friendID'])) {
    $friendID = $_POST['friendID'];
    if ($friendID != $userID) {
        $stmt = $conn->prepare("INSERT IGNORE INTO friend_requests (FromUserID, ToUserID, Status) VALUES (?, ?, 'pending')");
        $stmt->execute([$userID, $friendID]);
    }
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "home.php"));
    exit;
}

if ($action === 'accept' && isset($_POST['requestID'])) {
    $requestID = $_POST['requestID'];
    $stmt = $conn->prepare("UPDATE friend_requests SET Status = 'accepted' WHERE RequestID = ? AND ToUserID = ?");
    $stmt->execute([$requestID, $userID]);
    header("Location: friends.php");
    exit;
}

if ($action === 'reject' && isset($_POST['requestID'])) {
    $requestID = $_POST['requestID'];
    $stmt = $conn->prepare("UPDATE friend_requests SET Status = 'rejected' WHERE RequestID = ? AND ToUserID = ?");
    $stmt->execute([$requestID, $userID]);
    header("Location: friends.php");
    exit;
}

if ($action === 'remove' && isset($_POST['friendID'])) {
    $friendID = $_POST['friendID'];
    $stmt = $conn->prepare("
        UPDATE friend_requests
        SET Status = 'rejected'
        WHERE 
            (FromUserID = ? AND ToUserID = ? OR FromUserID = ? AND ToUserID = ?)
            AND Status = 'accepted'
    ");
    $stmt->execute([$userID, $friendID, $friendID, $userID]);
    header("Location: friends.php");
    exit;
}

header("Location: home.php");
exit;
?>
