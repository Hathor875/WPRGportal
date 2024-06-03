<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "myDB";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nickname']) && isset($_POST['comment'])) {
        $nickname = $conn->real_escape_string($_POST['nickname']);
        $comment = $conn->real_escape_string($_POST['comment']);
        $sql_insert_comment = "INSERT INTO comments (article_id, nickname, content) VALUES ('$article_id', '$nickname', '$comment')";
        $conn->query($sql_insert_comment);
    }

    $sql = "SELECT * FROM articles WHERE id='$article_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Brak artykułu o podanym ID.";
        exit();
    }

    $images = [];
    if ($row['first_photo_id']) {
        $sql_first_image = "SELECT image_url FROM images WHERE id='" . $row['first_photo_id'] . "'";
        $result_first_image = $conn->query($sql_first_image);
        if ($result_first_image->num_rows > 0) {
            $first_image_row = $result_first_image->fetch_assoc();
            $images[] = $first_image_row['image_url'];
        }
    }

    if ($row['second_photo_id']) {
        $sql_second_image = "SELECT image_url FROM images WHERE id='" . $row['second_photo_id'] . "'";
        $result_second_image = $conn->query($sql_second_image);
        if ($result_second_image->num_rows > 0) {
            $second_image_row = $result_second_image->fetch_assoc();
            $images[] = $second_image_row['image_url'];
        }
    }

    $full_content = isset($_GET['full']) && $_GET['full'] == 'true';

    $sql_comments = "SELECT * FROM comments WHERE article_id='$article_id' ORDER BY created_at DESC";
    $comments_result = $conn->query($sql_comments);

} else {
    echo "Brak ID artykułu w URL.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row["title"]); ?> - Serwis Informacyjny</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .more-content {
            display: none;
        }

        .article-container {
            max-height: 300px;
            overflow: scroll;
        }

        .article-image {
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            height: auto;
        }

        #czytaj-dalej {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .comment-section {
            margin-top: 30px;
        }

        .comment {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .comment-nickname {
            font-weight: bold;
        }

        .comment-content {
            margin: 5px 0;
        }
    </style>
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
                if (isset($images[1])) {
                    echo '<img src="' . htmlspecialchars($images[1]) . '" alt="Article Image" class="article-image">';
                }
            } else {
                $content = nl2br(htmlspecialchars($row["content"]));
                $content_lines = explode("<br />", $content);
                $first_10_lines = array_slice($content_lines, 0, 10);
                $remaining_lines = array_slice($content_lines, 10);
                $content_length = strlen($row["content"]);

                if ($content_length > 400) {
                    $first_quarter_content = substr($row["content"], 0, $content_length / 4);
                    echo nl2br(htmlspecialchars($first_quarter_content));
                    echo '<span class="more-content">' . implode("<br />", $remaining_lines);
                    if (isset($images[1])) {
                        echo '<img src="' . htmlspecialchars($images[1]) . '" alt="Article Image" class="article-image">';
                    }
                    echo '</span>';
                    echo '<button id="czytaj-dalej" onclick="window.location.href=\'?id=' . htmlspecialchars($article_id) . '&full=true\'">czytaj dalej</button>';
                } else {
                    echo nl2br(htmlspecialchars($row["content"]));
                    if (isset($images[1])) {
                        echo '<img src="' . htmlspecialchars($images[1]) . '" alt="Article Image" class="article-image">';
                    }
                }
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
            </form>
        </section>
    </article>
</main>

<?php include 'layout/footer.php'; ?>
</body>
</html>
