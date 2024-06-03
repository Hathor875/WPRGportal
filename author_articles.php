<?php
include 'db_connect.php';
include 'thumbnail_generator.php';
include 'layout/header.php';

if (isset($_GET['author_id'])) {
    $author_id = $_GET['author_id'];
    $sql = "SELECT * FROM articles WHERE author_id = $author_id ORDER BY created_at DESC";
    $result = $conn->query($sql);

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
        echo "Error in SQL query: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Brak ID autora.";
}
include 'layout/footer.php';
?>
