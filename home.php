<?php
session_start();
include "Connection.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wes's Movie Website - Home</title>
    <link rel="stylesheet" href="WebStyle.css">

    <style>
        body {
            margin: 0;
            background-color: #0b132b;
            font-family: "Georgia", serif;
        }

        .movie-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 40px 20px;
        }

        .movie-card {
            width: 240px;
            background-color: #5289b5;
            padding: 15px;
            border-radius: 14px;
            text-align: center;
            font-family: "Georgia", serif;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
            color: #050a30;
            transition: transform 0.25s ease;
        }

        .movie-card:hover {
            transform: scale(1.05);
        }

        .movie-card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
        }

        h3 {
            margin-top: 10px;
            color: #fff;
        }

        .page-title {
            text-align: center;
            font-family: "Georgia", serif;
            font-size: 38px;
            color: #fff;
            margin-top: 90px; 
        }
    </style>
</head>

<body>

<?php include "navbar.php"; ?>

<h2 class="page-title">Latest Movies</h2>

<?php
$stmt = $conn->prepare("SELECT Movie_ID, Title, Poster FROM movie ORDER BY Movie_ID DESC LIMIT 10");
$stmt->execute();
$movies = $stmt->fetchAll();
?>

<div class="movie-container">
    <?php foreach ($movies as $movie): ?>
        <a href="movie.php?id=<?php echo $movie['Movie_ID']; ?>" style="text-decoration:none;">
            <div class="movie-card">
                <img src="<?php echo $movie['Poster']; ?>" alt="Movie Poster">
                <h3><?php echo $movie['Title']; ?></h3>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<script>
    const friendsTab = document.querySelector('a[href="friends.php"]');
    if(friendsTab) friendsTab.style.fontWeight = 'bold';
</script>

</body>
</html>

