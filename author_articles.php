<?php
include 'DatabaseHandle/Database.php';
include 'thumbnail_generator.php';
include 'layout/header.php';

if (isset($_GET['author_id'])) {
    $author_id = intval($_GET['author_id']);
    $db = new Database();

    $sql = "SELECT * FROM articles WHERE author_id = $author_id ORDER BY created_at DESC";
    $result = $db->query($sql);

    if ($result) {
        ?>
        <main>
            <div class="article-container">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo generateThumbnail($row["title"], $row["content"], $row["id"]);
                    }
                } else {
                    echo "<p>Brak artykułów tego autora.</p>";
                }
                ?>
            </div>
        </main>
        <?php
    } else {
        echo "Error in SQL query: " . $db->conn->error;
    }

    $db->close();
} else {
    echo "Brak ID autora.";
}
include 'layout/footer.php';
?>
