<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './DatabaseHandle/Database.php';

if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header("Location: /portal/login.php");
    exit;
}

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['backup'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="database_backup.csv"');
        $output = fopen('php://output', 'w');
        if (!$output) {
            die("Unable to open output stream.");
        }
        $tables = [];
        $result = $db->conn->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        foreach ($tables as $table) {
            $result = $db->conn->query("SELECT * FROM $table");
            $fields = $result->fetch_fields();
            $header = [];
            foreach ($fields as $field) {
                $header[] = $field->name;
            }
            fputcsv($output, [$table]);
            fputcsv($output, $header);
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, $row);
            }
            fputcsv($output, []);
        }
        fclose($output);
        exit;
    }

    if (isset($_POST['clear'])) {
        $db->clearDatabase();
        echo "Database cleared except for the users table.";
    }

    if (isset($_POST['search_comments'])) {
        $commentId = intval($_POST['comment_id']);
        $stmt = $db->conn->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $comment = $result->fetch_assoc();
            echo "Comment found: <br>";
            echo "Author: " . htmlspecialchars($comment['author']) . "<br>";
            echo "Date: " . htmlspecialchars($comment['date']) . "<br>";
            echo "Content: " . htmlspecialchars($comment['content']);
        } else {
            echo "No comment found with ID $commentId.";
        }

        $stmt->close();
    }

    if (isset($_POST['delete_comment'])) {
        $commentId = intval($_POST['comment_id']);
        $stmt = $db->conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Comment with ID $commentId deleted.";
        } else {
            echo "No comment found with ID $commentId.";
        }

        $stmt->close();
    }

    if (isset($_POST['search_articles'])) {
        $articleId = intval($_POST['article_id']);
        $stmt = $db->conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $article = $result->fetch_assoc();
            echo "Article found: <br>";
            echo "Title: " . htmlspecialchars($article['title']) . "<br>";
            echo "<a href='/portal/article.php?id=" . $articleId . "' target='_blank'>Open Article</a>";
        } else {
            echo "No article found with ID $articleId.";
        }

        $stmt->close();
    }

    if (isset($_POST['delete_article'])) {
        $articleId = intval($_POST['article_id']);
        $stmt = $db->conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Article with ID $articleId deleted.";
        } else {
            echo "No article found with ID $articleId.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../portal/css/style.css">
</head>
<body>
<?php include './layout/header.php'; ?>
<main>
    <h2>Admin Panel</h2>

    <form method="post">
        <button type="submit" name="backup">Backup Database</button>
    </form>

    <form method="post">
        <button type="submit" name="clear">Clear Database (except users)</button>
    </form>

    <h3>Search and Delete Comments</h3>
    <form method="post">
        <label>Comment ID: <input type="number" name="comment_id" required></label>
        <button type="submit" name="search_comments">Search Comment</button>
        <button type="submit" name="delete_comment">Delete Comment</button>
    </form>

    <h3>Search and Delete Articles</h3>
    <form method="post">
        <label>Article ID: <input type="number" name="article_id" required></label>
        <button type="submit" name="search_articles">Search Article</button>
        <button type="submit" name="delete_article">Delete Article</button>
    </form>
</main>
<?php include './layout/footer.php'; ?>
</body>
</html>
