<?php
session_start();
include "Connection.php";

$q = $_GET['q'] ?? '';

$stmt = $conn->prepare("SELECT Movie_ID, Title, Poster FROM movie WHERE Title LIKE :q");
$stmt->execute([':q' => "%$q%"]);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="WebStyle.css">

    <style>
        body {
            margin: 0;
            background-color: #0b132b;
            color: white;
            font-family: Arial, sans-serif;
        }

        .page-title {
            text-align: center;
            font-family: "Georgia", serif;
            font-size: 38px;
            color: #fff;
            margin-top: 90px; /* Space under sticky navbar */
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
    </style>
</head>

<body>

<?php include "navbar.php"; ?>

<h2 class="page-title">Results for "<?php echo htmlspecialchars($q); ?>"</h2>

<?php if (empty($results)): ?>
    <h3 style="text-align:center; color:white;">No movies found.</h3>

<?php else: ?>
    <div class="movie-container">
        <?php foreach ($results as $movie): ?>
            <a href="movie.php?id=<?php echo $movie['Movie_ID']; ?>" style="text-decoration:none;">
                <div class="movie-card">
                    <img src="<?php echo $movie['Poster']; ?>" alt="Poster">
                    <h3><?php echo $movie['Title']; ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
