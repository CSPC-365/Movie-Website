<?php
include 'Connection.php';

if (isset($_POST['submit'])) {

    $target_directory = "uploads/"; 

    if (!is_dir($target_directory)) {
        mkdir($target_directory, 0777, true);
    }

    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_directory . $file_name;

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Upload the file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

        echo "Image uploaded successfully!<br>";
        echo "<img src='$target_file' width='300'>";
    } else {
        echo "Error uploading file.";
    }
}
?>
