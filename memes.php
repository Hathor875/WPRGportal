<?php
include 'thumbnail_generator.php';

$servername = "localhost";
$username = "root";
$password = "1234";
$database = "myDB";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM articles WHERE category = 'memes' ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}
?>

<?php include 'layout/header.php'; ?>

<div class="article-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo generateThumbnail($row["title"], $row["content"], $row["id"]);
        }
    } else {
        echo "<p>Brak wiadomości do wyświetlenia.</p>";
    }
    ?>
</div>

<?php include 'layout/footer.php'; ?>

<?php
$conn->close();
?>
