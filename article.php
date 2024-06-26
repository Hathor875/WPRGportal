<?php
include 'DatabaseHandle/Database.php';

$db = new Database();

if (isset($_GET['id'])) {
    $article_id = intval($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nickname']) && isset($_POST['comment'])) {
        $nickname = $db->conn->real_escape_string($_POST['nickname']);
        $comment = $db->conn->real_escape_string($_POST['comment']);
        $sql_insert_comment = "INSERT INTO comments (article_id, nickname, content) VALUES ('$article_id', '$nickname', '$comment')";
        $db->query($sql_insert_comment);

        // Redirect to prevent form resubmission
        header("Location: ?id=$article_id");
        exit();
    }

    $sql = "SELECT * FROM articles WHERE id='$article_id'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Brak artykułu o podanym ID.";
        exit();
    }

    $images = [];
    $sql_images = "SELECT images.image_url FROM images 
                   JOIN articles_images ON images.id = articles_images.image_id 
                   WHERE articles_images.article_id='$article_id'
                   ORDER BY images.id";
    $result_images = $db->query($sql_images);
    if ($result_images->num_rows > 0) {
        while ($image_row = $result_images->fetch_assoc()) {
            $images[] = $image_row['image_url'];
        }
    }

    $full_content = isset($_GET['full']) && $_GET['full'] == 'true';

    $sql_comments = "SELECT * FROM comments WHERE article_id='$article_id' ORDER BY created_at DESC";
    $comments_result = $db->query($sql_comments);

} else {
    echo "Brak ID artykułu w URL.";
    exit();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row["title"]); ?> - Serwis Informacyjny</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'layout/header.php'; ?>

<main>
    <article>
        <section>
            <?php
            if (!empty($images)) {
                echo '<img src="' . htmlspecialchars($images[0]) . '" alt="Article Image" class="article-image">';
            }

            if ($full_content) {
                echo nl2br(htmlspecialchars($row["content"]));
            } else {
                $content = nl2br(htmlspecialchars($row["content"]));
                $content_lines = explode("<br />", $content);
                $first_10_lines = array_slice($content_lines, 0, 10);
                $remaining_lines = array_slice($content_lines, 10);
                $content_length = strlen($row["content"]);

                if ($content_length > 400) {
                    $first_quarter_content = substr($row["content"], 0, $content_length / 4);
                    echo nl2br(htmlspecialchars($first_quarter_content));
                    echo '<span class="more-content">' . implode("<br />", $remaining_lines) . '</span>';
                    echo '<button id="czytaj-dalej" onclick="window.location.href=\'?id=' . htmlspecialchars($article_id) . '&full=true\'">czytaj dalej</button>';
                } else {
                    echo nl2br(htmlspecialchars($row["content"]));
                }
            }
            for ($i = 1; $i < count($images); $i++) {
                echo '<img src="' . htmlspecialchars($images[$i]) . '" alt="Article Image" class="article-image">';
            }
            ?>
        </section>

        <section class="comment-section">
            <h2>Komentarze</h2>
            <?php
            if ($comments_result->num_rows > 0) {
                while ($comment_row = $comments_result->fetch_assoc()) {
                    echo '<div class="comment">';
                    echo '<div class="comment-nickname">' . htmlspecialchars($comment_row['nickname']) . '</div>';
                    echo '<div class="comment-content">' . nl2br(htmlspecialchars($comment_row['content'])) . '</div>';
                    echo '<div class="comment-date">' . htmlspecialchars($comment_row['created_at']) . '</div>';
                    echo '</div>';
                }
            } else {
                echo 'Brak komentarzy.';
            }
            ?>

            <form action="?id=<?php echo htmlspecialchars($article_id); ?>" method="post">
                <label for="nickname">Nick:</label>
                <input type="text" id="nickname" name="nickname" required>
                <br>
                <label for="comment">Komentarz:</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
                <br>
                <button type="submit">Dodaj komentarz</button>
                <button type="reset">Resetuj</button>
            </form>
    </article>
</main>

<?php include 'layout/footer.php'; ?>
</body>
</html>
