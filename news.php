<?php
include 'thumbnail_generator.php';

include 'DatabaseHandle/Database.php';

include 'layout/header.php';
$db = new Database();

$sql = "SELECT * FROM articles WHERE category = 'news' ORDER BY created_at DESC LIMIT 25";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo generateThumbnail($row["title"], $row["content"], $row["id"]);
    }
} else {
    echo "<p>Brak wiadomości do wyświetlenia.</p>";
}

$db->close();

include 'layout/footer.php';

