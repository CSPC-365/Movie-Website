<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit;
}

$userID = $_SESSION['UserID'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recommended Movies</title>
    <style>
        body {
            background-color: #0b132b;
            color: white;
            font-family: "Georgia", serif;
            margin: 0;
        }
        .container {
            width: 900px;
            margin: 40px auto;
            background: #1a243a;
            padding: 25px;
            border-radius: 12px;
        }
        h2 {
            color: #00cccc;
            text-align: center;
        }
        .movie-item {
            padding: 12px;
            border-bottom: 1px solid #203150;
            font-size: 20px;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 16px;
            background: #00cccc;
            color: black;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
        }
    </style>
</head>

<body>
<?php include "navbar.php"; ?>

<div class="container">
    <a href="home.php" class="back-btn">⬅ Back</a>
    <h2>Recommended Movies</h2>

<?php
//Find all accepted friends
$friendsQuery = $conn->prepare("
    SELECT 
        CASE 
            WHEN fr.FromUserID = :id THEN fr.ToUserID 
            ELSE fr.FromUserID 
        END AS FriendID
    FROM friend_requests fr
    WHERE (fr.FromUserID = :id OR fr.ToUserID = :id)
    AND fr.Status = 'accepted'
");
$friendsQuery->execute([':id' => $userID]);
$friends = $friendsQuery->fetchAll(PDO::FETCH_COLUMN);

if (!$friends) {
    echo "<p>You have no friends yet.</p>";
    exit;
}

// Prepare comma separated list
$friendsList = implode(',', array_map('intval', $friends));


// Get top recommended movies (5-star movies your friends rated)
$recQuery = $conn->query("
    SELECT 
        m.Movie_id,
        m.Title,
        COUNT(*) AS rating_count
    FROM movie_ratings r
    JOIN movie m ON r.Movie_ID = m.Movie_id
    WHERE r.UserID IN ($friendsList)
      AND r.Rating = 5
    GROUP BY m.Movie_id, m.Title
    ORDER BY rating_count DESC
    LIMIT 10
");

$results = $recQuery->fetchAll(PDO::FETCH_ASSOC);

if (!$results) {
    echo "<p>No recommendations yet — your friends haven't rated any movies 5 stars.</p>";
} else {
    foreach ($results as $movie) {
        echo "<div class='movie-item'>
                <a href='movie.php?id={$movie['Movie_id']}' style='color:white; text-decoration:none;'>
                    {$movie['Title']} — Recommended by {$movie['rating_count']} friend(s)
                </a>
              </div>";
    }
}
?>
</div>

</body>
</html>
