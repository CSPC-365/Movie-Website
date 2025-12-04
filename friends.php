<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['UserID'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Friends - Wes's Movie Website</title>
    <link rel="stylesheet" href="WebStyle.css">
    <style>
        body { 
            background-color:#0b132b; 
            color:white; font-family: "Georgia", serif;
            margin:0; 
            padding:0 20px; 
        }
        h2 { text-align:center; 
             margin-top:40px; 
             color:#00cccc;
        }
        .friend-list { 
            max-width:800px; 
            margin:20px auto; 
            background-color:#1f2a44; 
            padding:20px; 
            border-radius:12px; 
        }
        .friend-item { 
            display:flex; 
            justify-content:space-between; 
            align-items:center; 
            padding:10px 0; 
            border-bottom:1px solid #00cccc; 
        }
        .friend-item:last-child { 
            border-bottom:none; 
        }
        .friend-item button { 
            padding:6px 12px; 
            border:none; 
            border-radius:6px; 
            cursor:pointer; 
            font-family:"Georgia", serif;
            font-size:16px; 
        }
        .accept-btn { 
            background-color:#00cccc; 
            color:black; 
        }
        .accept-btn:hover { 
            background-color:white; 
        }
        .remove-btn { 
            background-color:#5289b5; 
            color:white; 
        }
        .remove-btn:hover { 
            background-color:#406d90; 
        }
        .cancel-btn { 
            background-color:#ff5555; 
            color:white; 
        }
        .cancel-btn:hover { 
            background-color:#ff7777; 
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<h2>Friend Requests Received</h2>
<div class="friend-list">
<?php
$stmt = $conn->prepare("
    SELECT fr.RequestID, fr.FromUserID, u.first_name, u.last_name
    FROM friend_requests fr
    JOIN user u ON fr.FromUserID = u.UserID
    WHERE fr.ToUserID = ? AND fr.Status = 'pending'
");
$stmt->execute([$userID]);
$received = $stmt->fetchAll();

if ($received):
    foreach ($received as $req):
?>
<div class="friend-item">
    <span><?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?></span>
    <form action="handle_request.php" method="POST" style="display:inline;">
        <input type="hidden" name="requestID" value="<?= $req['RequestID'] ?>">
        <button type="submit" name="action" value="accept" class="accept-btn">Accept</button>
        <button type="submit" name="action" value="reject" class="remove-btn">Reject</button>
    </form>
</div>
<?php endforeach; else: ?>
<p style="text-align:center; color:#00cccc;">No pending received requests!</p>
<?php endif; ?>
</div>

<h2>Friend Requests Sent</h2>
<div class="friend-list">
<?php
$stmt = $conn->prepare("
    SELECT fr.RequestID, fr.ToUserID, u.first_name, u.last_name
    FROM friend_requests fr
    JOIN user u ON fr.ToUserID = u.UserID
    WHERE fr.FromUserID = ? AND fr.Status = 'pending'
");
$stmt->execute([$userID]);
$sent = $stmt->fetchAll();

if ($sent):
    foreach ($sent as $req):
?>
<div class="friend-item">
    <span><?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?></span>
    <form action="handle_request.php" method="POST" style="display:inline;">
        <input type="hidden" name="requestID" value="<?= $req['RequestID'] ?>">
        <button type="submit" name="action" value="cancel" class="cancel-btn">Cancel Request</button>
    </form>
</div>
<?php endforeach; else: ?>
<p style="text-align:center; color:#00cccc;">No pending sent requests!</p>
<?php endif; ?>
</div>

<h2>Your Friends</h2>
<div class="friend-list">
<?php
$stmt = $conn->prepare("
    SELECT u.UserID, u.first_name, u.last_name
    FROM friend_requests fr
    JOIN user u ON u.UserID = fr.FromUserID
    WHERE fr.ToUserID = ? AND fr.Status = 'accepted'
    UNION
    SELECT u.UserID, u.first_name, u.last_name
    FROM friend_requests fr
    JOIN user u ON u.UserID = fr.ToUserID
    WHERE fr.FromUserID = ? AND fr.Status = 'accepted'
");
$stmt->execute([$userID, $userID]);
$friends = $stmt->fetchAll();

if ($friends):
    foreach ($friends as $f):
?>
<div class="friend-item">
    <span><?= htmlspecialchars($f['first_name'] . ' ' . $f['last_name']) ?></span>
    <form action="handle_request.php" method="POST" style="display:inline;">
        <input type="hidden" name="friendID" value="<?= $f['UserID'] ?>">
        <button type="submit" name="action" value="remove" class="remove-btn">Remove</button>
    </form>
</div>
<?php endforeach; else: ?>
<p style="text-align:center; color:#00cccc;">You have no friends yet!</p>
<?php endif; ?>
</div>

</body>
</html>
