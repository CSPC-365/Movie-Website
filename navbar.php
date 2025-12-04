<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "Connection.php";

$userID = $_SESSION['UserID'] ?? null;
$pendingRequests = 0;

if ($userID) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM friend_requests WHERE ToUserID = ? AND Status = 'pending'");
    $stmt->execute([$userID]);
    $pendingRequests = (int) $stmt->fetchColumn();
}
$profile_picture = "default.png";

if ($userID) {
    $stmt = $conn->prepare("SELECT profile_picture FROM user WHERE UserID = ?");
    $stmt->execute([$userID]);
    $result = $stmt->fetchColumn();

    if (!empty($result)) {
        $profile_picture = $result;
    }
}
?>

<style>
nav.main-nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 25px;
    background: #007a99;
    border-bottom: 3px solid #004d5c;
    border-radius: 0 0 12px 12px;
    font-family: "Georgia", serif;
}

nav.main-nav a.nav-item {
    color: white;
    font-size: 22px;
    padding: 12px 18px;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.25s ease;
    position: relative;
}
nav.main-nav a.nav-item:hover {
    background: #00cccc;
    color: black;
}

.notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background-color: red;
    color: white;
    font-size: 14px;
    padding: 2px 6px;
    border-radius: 50%;
}

.search-box {
    display: inline-flex;
    align-items: center;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    border: 2px solid #00cccc;
    margin-top: 20px;
}
.search-box input {
    padding: 8px 14px;
    border: none;
    outline: none;
    width: 200px;
    max-width: 200px;
    font-size: 18px;
    font-family: "Georgia", serif;
}
.search-box button {
    background: #00cccc;
    border: none;
    padding: 8px 14px;
    cursor: pointer;
    font-family: "Georgia", serif;
    font-size: 18px;
    transition: 0.2s ease;
}
.search-box button:hover {
    background: #ffffff;
}
.profile-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    border: 3px solid white;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.profile-icon:hover {
    transform: scale(1.08);
    box-shadow: 0 0 10px rgba(255,255,255,0.7);
}

.dropbtn {
    background: none !important;
    border: none !important;
    padding: 0 !important;
}

.dropbtn:hover {
    background: white;
}

.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #007a99;
    min-width: 180px;
    border-radius: 8px;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.25);
}
.dropdown-content a {
    color: white;
    padding: 12px;
    text-decoration: none;
    display: block;
    font-size: 18px;
    border-radius: 6px;
    font-family: "Georgia", serif;
}
.dropdown-content a:hover {
    background-color: #00cccc;
    color: black;
}
.dropdown:hover .dropdown-content {
    display: block;
}
</style>

<nav class="main-nav">
    <div>
        <a class="nav-item" href="home.php" style="font-size:26px;">LOGO</a>
    </div>

    <div style="display:flex; align-items:center; gap:15px;">
        <a class="nav-item" href="friends.php">
    Friends
    <?php if ($pendingRequests > 0): ?>
        <span class="notification-badge"><?= $pendingRequests ?></span>
    <?php endif; ?>
</a>

<a class="nav-item" href="recommended.php">Recommended</a>



        <?php if (!empty($_SESSION['AdminID'])): ?>
            <a class="nav-item" href="admin.php">Admin</a>
        <?php endif; ?>

        <form action="search.php" method="GET" class="search-box">
            <input type="text" name="q" placeholder="Search movies..." required>
            <button type="submit">🔍</button>
        </form>
    </div>

<div class="dropdown">
    <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" 
         alt="Profile Picture"
         class="profile-icon dropbtn">

    <div class="dropdown-content">
        <a href="profile.php?user=<?= $userID ?>">Profile</a>
        <a href="#">Watchlist</a>
        <a href="index.php">Logout</a>
    </div>
</div>


</nav>
