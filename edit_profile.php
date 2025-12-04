<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['UserID'];

// Fetch user info
$stmt = $conn->prepare("SELECT first_name, last_name, profile_picture, bio FROM user WHERE UserID = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch();

if (!$user) { die("User not found."); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $bio = $_POST['bio'];

    $profilePic = $user['profile_picture'];

    // Handle image upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
        $target = "uploads/" . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
            $profilePic = $fileName;
        }
    }

    $update = $conn->prepare("
        UPDATE user 
        SET first_name=?, last_name=?, bio=?, profile_picture=? 
        WHERE UserID=?
    ");
    $update->execute([$first, $last, $bio, $profilePic, $userID]);

    header("Location: profile.php?id=$userID");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { 
            background:#0b132b; 
            color:white; 
            font-family: "Georgia", serif;
        }
        
        .edit-box { 
            width:600px; 
            margin:40px auto;
            background:#1a243a; 
            padding:25px; 
            border-radius:12px; 
        }
       
        input, textarea { 
            width:100%; 
            padding:8px; 
            margin-top:10px; 
            border-radius:8px; }
        
        button { 
            margin-top:20px;
            background:#00cccc; 
            color:black; 
            padding:10px 16px; 
            border:none; 
            border-radius:8px; 
            cursor:pointer;
        }
    </style>
</head>
<body>

<div class="edit-box">
    <h2>Edit Profile</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>">

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>">

        <label>Bio</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>

        <label>Profile Picture</label><br>
        <img src="uploads/<?= $user['profile_picture'] ?>" width="120" style="border-radius:10px;margin-top:10px;">
        <input type="file" name="profile_picture">

        <button type="submit">Save Changes</button>

    </form>
</div>

</body>
</html>
