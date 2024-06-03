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

$sql = "SELECT * FROM authors ORDER BY name";
$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}
?>

<?php include 'layout/header.php'; ?>

<main>
    <div class="author-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<a href='author_articles.php?author_id={$row["id"]}'>";
                echo generateAuthorThumbnail($row["name"], $row["email"], $row["id"]);
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
$conn->close();
?>
