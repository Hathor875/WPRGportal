<?php
include 'thumbnail_generator.php';
include 'header.php';

$servername = "localhost";
$username = "root";
$password = "1234";
$database = "myDB";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM articles WHERE category = 'news' ORDER BY created_at DESC LIMIT 25";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo generateThumbnail($row["title"], $row["content"], $row["id"]);
    }
} else {
    echo "<p>Brak wiadomości do wyświetlenia.</p>";
}

$conn->close();

include 'footer.php';
?>
