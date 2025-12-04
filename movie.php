<?php
session_start();
include "Connection.php";

if (!isset($_GET["id"])) {
    header("Location: home.php");
    exit;
}

$movie_id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM movie WHERE Movie_id = :movie_id LIMIT 1");
$stmt->execute([":movie_id" => $movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    echo "<h2 style='text-align:center;color:red;'>Movie not found.</h2>";
    exit;
}

$userID = $_SESSION['UserID'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($movie["Title"]) ?> - Movie Details</title>
    <link rel="stylesheet" href="WebStyle.css">

    <style>
        body {
            font-family: "Georgia", serif;
            background-color: #111;
            color: whitesmoke;
            margin: 0;
        }
        .movie-details-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
        }
        .movie-details img {
            width: 350px;
            height: 520px;
            object-fit: cover;
            border-radius: 12px;
        }
        .movie-info {
            max-width: 600px;
            font-size: 22px;
        }
        .movie-info h1,
        .movie-info p,
        .movie-info strong {
            color: white;
        }
        .btn,
        .rate-btn,
        .friend-btn {
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: "Georgia", serif;
            transition: 0.25s ease;
        }
        .btn {
            display: inline-block;
            background-color: #00cccc;
            padding: 10px 20px;
            color: black;
            font-size: 22px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: white;
        }
        .rate-btn {
            background-color: #00cccc;
            color: black;
            padding: 8px 15px;
            font-size: 16px;
        }
        .rate-btn:hover {
            background-color: white;
        }
        .friend-btn {
            background-color: #5289b5;
            color: white;
            padding: 8px 15px;
            font-size: 16px;
            margin-left: 10px;
        }
        .friend-btn:hover {
            background-color: #406d90;
        }
        .movie-page textarea,
        .movie-page select,
        .movie-page input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #5289b5;
            font-size: 18px;
            font-family: "Georgia", serif;
            background-color: #222;
            color: white;
            outline: none;
        }
        .section-heading {
            text-align: center;
            color: #5289b5;
            font-size: 32px;
            margin-top: 40px;
        }
        .comment-box {
            border: 1px solid #5289b5;
            background: #0f192e;
            color: white;
            padding: 10px;
            margin: 10px auto;
            max-width: 600px;
            border-radius: 8px;
        }
        .no-comments {
            text-align: center;
            color: #5289b5;
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>
    
    <?php if (isset($_GET['success']) && $_GET['success'] === 'rating_added'): ?>
    <p style="color: #00ff99; font-size: 22px; text-align:center; margin-top: 20px;">
        ✔ You rated this movie successfully!
    </p>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'already_rated'): ?>
    <p style="color: #ff4444; font-size: 22px; text-align:center; margin-top: 20px;">
        ✘ You already rated this movie.
    </p>
<?php endif; ?>

<div class="movie-page">
<div class="movie-details-container">
    <div class="movie-details">
        <img src="<?= htmlspecialchars($movie['Poster']) ?>" alt="Movie Poster">
    </div>
    <div class="movie-info">
        <h1><?= htmlspecialchars($movie['Title']) ?></h1>
        <p><strong>Genre:</strong> <?= htmlspecialchars($movie['Genre']) ?></p>
        <p><strong>Runtime:</strong> <?= $movie['Hours'] ?>h <?= $movie['Minutes'] ?>m</p>
        <p><strong>Release Date:</strong> <?= htmlspecialchars($movie['Realese_date']) ?></p>
        <p><?= nl2br(htmlspecialchars($movie['Description'])) ?></p>
    </div>
</div>
<div style="text-align:center; margin-top:40px;">
    <a href="home.php" class="btn">⬅ Back to Home</a>
</div>
<?php if ($userID): ?>
    <h2 class="section-heading">Rate This Movie</h2>
    <form action="rate_movie.php" method="POST" style="text-align:center;">
        <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
        <select name="rating" required style="font-size:20px;">
            <option value="">Select Rating</option>
            <option value="1">★☆☆☆☆</option>
            <option value="2">★★☆☆☆</option>
            <option value="3">★★★☆☆</option>
            <option value="4">★★★★☆</option>
            <option value="5">★★★★★</option>
        </select><br><br>
        <button type="submit" class="rate-btn">Submit Rating</button>
    </form>
    <h2 class="section-heading">Leave a Comment</h2>
    <form action="comment.php" method="POST" style="max-width:600px; margin:auto;">
        <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
        <textarea name="comment" placeholder="Write your comment..." required></textarea>
        <button type="submit" class="rate-btn">Post Comment</button>
    </form>
<?php endif; ?>
<h2 class="section-heading">Recent Comments</h2>
<?php
$stmt = $conn->prepare("
    SELECT c.CommentID, c.Comment_Text, c.Comment_Time, u.UserID, u.first_name, u.last_name
    FROM comments c
    JOIN user u ON c.UserID = u.UserID
    WHERE c.MovieID = ?
    ORDER BY c.Comment_Time DESC
    LIMIT 10
");
$stmt->execute([$movie_id]);
$comments = $stmt->fetchAll();
?>
<?php foreach ($comments as $comment): ?>
    <div class="comment-box">
        <strong><?= htmlspecialchars($comment['first_name'] . " " . $comment['last_name']) ?></strong>
        <p><?= nl2br(htmlspecialchars($comment['Comment_Text'])) ?></p>
        <small><?= htmlspecialchars($comment['Comment_Time']) ?></small>

        <?php if ($userID && $userID != $comment['UserID']): ?>
            <?php
            $check = $conn->prepare("
                SELECT Status FROM friend_requests
                WHERE (FromUserID=? AND ToUserID=?) OR (FromUserID=? AND ToUserID=?)
                LIMIT 1
            ");
            $check->execute([$userID, $comment['UserID'], $comment['UserID'], $userID]);
            $status = $check->fetchColumn();
            ?>

            <?php if (!$status): ?>
                <form action="handle_request.php" method="POST" style="display:inline;">
                    <input type="hidden" name="friendID" value="<?= $comment['UserID'] ?>">
                    <button type="submit" name="action" value="send_request" class="friend-btn">Add Friend</button>
                </form>
            <?php elseif ($status == 'pending'): ?>
                <span style="color:#00cccc; margin-left:10px;">Request Sent</span>
            <?php elseif ($status == 'accepted'): ?>
                <span style="color:#00cccc; margin-left:10px;">Friends</span>
            <?php endif; ?>

        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>
