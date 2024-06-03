<?php
// Podłączamy plik z funkcją generującą miniatury
include 'thumbnail_generator.php';

// Dane do połączenia z bazą danych
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "myDB";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php include 'layout/header.php'; ?>

<main>
    <div class="article-container">
        <?php
        if (isset($_GET['author_id'])) {
            $author_id = $_GET['author_id'];
            $sql = "SELECT * FROM articles WHERE author_id = $author_id ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result) {

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo generateThumbnail($row["title"], $row["content"], $row["id"]);
                    }
                } else {

                    echo "<p>Brak artykułów tego autora.</p>";
                }
            } else {

                echo "Error in SQL query: " . $conn->error;
            }

            $conn->close();
        } else {
            echo "Brak ID autora.";
        }
        ?>
    </div>
</main>

<?php include 'layout/footer.php'; ?>
