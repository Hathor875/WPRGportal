<?php
include 'DatabaseHandle/Database.php';
include 'thumbnail_generator.php';
include 'layout/header.php';

$db = new Database();

$sql = "SELECT * FROM articles ORDER BY created_at DESC";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo generateThumbnail($row["title"], $row["content"], $row["id"]);
    }
} else {
    echo "<p>Brak wiadomości do wyświetlenia.</p>";
}

$db->close();
?>

<?php
include 'layout/footer.php';
?>
