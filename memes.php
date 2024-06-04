<?php
include 'DatabaseHandle/Database.php';
include 'thumbnail_generator.php';

$db = new Database();

$sql = "SELECT * FROM articles WHERE category = 'memes' ORDER BY created_at DESC";
$result = $db->query($sql);

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

