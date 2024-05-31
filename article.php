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
    $sql = "SELECT * FROM articles WHERE id='$article_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Brak artykułu o podanym ID.";
        exit();
    }


    $sql_images = "SELECT image_url FROM images WHERE article_id='$article_id'";
    $result_images = $conn->query($sql_images);
    $images = [];
    if ($result_images->num_rows > 0) {
        while ($image_row = $result_images->fetch_assoc()) {
            $images[] = $image_row['image_url'];
        }
    }

    if (isset($_GET['full']) && $_GET['full'] == 'true') {
        $full_content = true;
    } else {
        $full_content = false;
    }

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
    <title><?php echo $row["title"]; ?> - Serwis Informacyjny</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .more-content {
            display: none;
        }
        .read-more {
            color: blue;
            cursor: pointer;
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
    </style>
</head>
<body>
<?php include 'header.php'; ?>


<main>

    <article>

        <div class="article-container">
            <section>
                <?php
                if (!empty($images)) {
                    echo '<img src="' . $images[0] . '" alt="Article Image" class="article-image">';
                }
                if ($full_content) {
                    echo nl2br($row["content"]);
                } else {
                    $content = nl2br($row["content"]);
                    $content_lines = explode("<br />", $content);
                    $first_10_lines = array_slice($content_lines, 0, 10);
                    $remaining_lines = array_slice($content_lines, 10);
                    $content_length = strlen($row["content"]);

                    if ($content_length > 400) {
                        $first_quarter_content = substr($row["content"], 0, $content_length / 4);
                        echo nl2br($first_quarter_content);
                        echo '<span class="more-content">' . implode("<br />", $remaining_lines) . '</span>';
                        echo ' <a href="?id='.$article_id.'&full=true">czytaj dalej</a>';
                    } else {
                        echo nl2br($row["content"]);
                    }
                }
                if (!empty($images) && isset($images[1])) {
                    echo '<img src="' . $images[1] . '" alt="Article Image" class="article-image">';
                }
                ?>
            </section>
        </div>
    </article>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
