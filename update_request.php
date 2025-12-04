<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['UserID']) || !isset($_POST['requestID']) || !isset($_POST['action'])) {
    header("Location: friends.php");
    exit;
}

$requestID = intval($_POST['requestID']);
$action = $_POST['action'];

switch($action) {
    case 'accept':

        $stmt = $conn->prepare("UPDATE friend_requests SET Status='accepted' WHERE RequestID=? AND ToUserID=?");
        $stmt->execute([$requestID, $_SESSION['UserID']]);
        break;

    case 'decline':

        $stmt = $conn->prepare("DELETE FROM friend_requests WHERE RequestID=? AND ToUserID=?");
        $stmt->execute([$requestID, $_SESSION['UserID']]);
        break;

    case 'cancel':
        $stmt = $conn->prepare("DELETE FROM friend_requests WHERE RequestID=? AND FromUserID=?");
        $stmt->execute([$requestID, $_SESSION['UserID']]);
        break;
}

header("Location: friends.php");
exit;
?>


