<?php
include 'DatabaseHandle/Database.php';
include 'thumbnail_generator.php';

$db = new Database();

$sql = "SELECT * FROM articles WHERE category = 'failures' ORDER BY created_at DESC";
$result = $db->query($sql);

if (!$result) {
    die("Error in SQL query: " . $db->conn->error);
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
$db->close();
?>
