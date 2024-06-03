<?php
include 'db_connect.php';
include 'thumbnail_generator.php';
include 'layout/header.php';

$sql = "SELECT * FROM articles WHERE category = 'memes' ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}
?>

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
