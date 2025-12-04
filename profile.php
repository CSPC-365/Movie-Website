<?php
session_start();
include "Connection.php";

if (!isset($_GET['user'])) {
    header("Location: home.php");
    exit;
}
$profileID = $_GET['user'];  
$loggedInID = $_SESSION['UserID'] ?? null; 

$stmt = $conn->prepare("SELECT first_name, last_name, profile_picture, bio FROM user WHERE UserID = ?");
$stmt->execute([$profileID]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($user['first_name']) ?>'s Profile</title>
    <link rel="stylesheet" href="WebStyle.css">

    <style>
        body {
            background-color: #0b132b;
            color: white;
            font-family: "Georgia", serif;
            margin: 0;
            padding: 0;

        }
        .profile-container {
            width: 900px;
            margin: 40px auto;
            background: #1a243a;
            padding: 25px;
            border-radius: 12px;
        }
        .profile-picture {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #00cccc;
        }
        .section-box {
            margin-top: 30px;
            padding: 20px;
            background: #0f192e;
            border-radius: 10px;
        }
        h3 {
            color: #00cccc;
            margin-bottom: 10px;
        }
        .movie-item, .friend-item {
            padding: 10px 0;
            border-bottom: 1px solid #203150;
        }
        .friend-pic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #00cccc;
            margin-right: 10px;
            vertical-align: middle;
        }
        .edit-button {
            color:black;
            padding:8px 16px;
            background:#00cccc;
            border-radius:6px;
            text-decoration:none;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="profile-container">
    
    <img class="profile-picture" src="uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture">
    <h2><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></h2>

    <?php if ($loggedInID == $profileID): ?>
        <a href="edit_profile.php" class="edit-button">Edit Profile</a>
    <?php endif; ?>
    <div class="section-box">
        <h3>About Me</h3>
        <p><?= nl2br(htmlspecialchars($user['bio'] ?? "No bio added yet.")) ?></p>
    </div>

    <div class="section-box">
        <h3>Friends</h3>
        <?php
        $friendsQuery = $conn->prepare("
            SELECT u.UserID, u.first_name, u.last_name, u.profile_picture
            FROM friend_requests fr
            JOIN user u ON (
                (fr.FromUserID = u.UserID AND fr.ToUserID = ?) OR
                (fr.ToUserID = u.UserID AND fr.FromUserID = ?)
            )
            WHERE fr.Status = 'accepted' AND u.UserID != ?
        ");
        $friendsQuery->execute([$profileID, $profileID, $profileID]);
        $friends = $friendsQuery->fetchAll(PDO::FETCH_ASSOC);

        if ($friends):
            foreach ($friends as $f):
        ?>
                <div class="friend-item">
                    <a href="profile.php?user=<?= $f['UserID'] ?>" style="color:white; text-decoration:none;">
                        <img class="friend-pic" src="uploads/<?= htmlspecialchars($f['profile_picture']) ?>" alt="">
                        <?= htmlspecialchars($f['first_name'] . " " . $f['last_name']) ?>
                    </a>
                </div>
        <?php
            endforeach;
        else:
            echo "<p>No friends yet.</p>";
        endif;
        ?>
    </div>
    <div class="section-box">
        <h3>Recently Rated Movies</h3>

        <?php
        $ratingStmt = $conn->prepare("
            SELECT 
                m.Title AS Movie_title,
                r.Rating,
                r.rating_date
            FROM movie_ratings r
            JOIN movie m ON r.Movie_ID = m.Movie_id
            WHERE r.UserID = ?
            ORDER BY r.rating_date DESC
            LIMIT 10
        ");
        $ratingStmt->execute([$profileID]);   // <-- Correct user
        $recentRatings = $ratingStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($recentRatings):
            foreach ($recentRatings as $r):
        ?>
                <div class="movie-item">
                    <?= htmlspecialchars($r['Movie_title']) ?>
                    — Rated <?= htmlspecialchars($r['Rating']) ?>/5 Stars ☆
                    <?php 
                        if (!empty($r['rating_date'])) {
                            echo " on " . date("F j, Y", strtotime($r['rating_date']));
                        }
                    ?>
                </div>
        <?php
            endforeach;
        else:
            echo "<p>No recent ratings.</p>";
        endif;
        ?>
    </div>
</div>
</body>
</html>
