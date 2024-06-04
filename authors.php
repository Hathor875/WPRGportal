<?php
include 'DatabaseHandle/Database.php';
include 'thumbnail_generator.php';

$db = new Database();

$sql = "SELECT * FROM authors ORDER BY name";
$result = $db->query($sql);

if (!$result) {
    die("Error in SQL query: " . $db->conn->error);
}
?>

<?php include 'layout/header.php'; ?>

<main>
    <div class="author-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<a href='author_articles.php?author_id=" . htmlspecialchars($row["id"]) . "'>";
                echo generateAuthorThumbnail($row["name"], $row["email"]);
                echo "</a>";
            }
        } else {
            echo "<p>Brak autorów do wyświetlenia.</p>";
        }
        ?>
    </div>
</main>

<?php include 'layout/footer.php'; ?>

<?php
$db->close();
?>
