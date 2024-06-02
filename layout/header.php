<?php
function getPageDetails($page, $conn) {
    $pages = [
        'index' => ['title' => 'Strona główna', 'header' => 'Witamy na stronie głównej'],
        'news' => ['title' => 'Aktualności', 'header' => 'Najnowsze wiadomości'],
        'failures' => ['title' => 'Awarie', 'header' => 'Informacje o awariach'],
        'memes' => ['title' => 'Memy', 'header' => 'Najlepsze memy'],
        'authors' => ['title' => 'Autorzy', 'header' => 'Nasi autorzy'],
    ];

    if ($page == 'article' && isset($_GET['id'])) {
        $articleId = intval($_GET['id']);
        $article = getArticleDetails($articleId, $conn);

        if ($article) {
            return ['title' => $article['title'], 'header' => $article['title']];
        }
    }

    if (array_key_exists($page, $pages)) {
        return ['title' => $pages[$page]['title'], 'header' => $pages[$page]['title']];
    }

    return ['title' => 'Strona', 'header' => ''];
}

function getArticleDetails($id, $conn) {
    $stmt = $conn->prepare("SELECT title FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

$servername = "localhost";
$username = "root";
$password = "1234";
$database = "myDB";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$page = basename($_SERVER['PHP_SELF'], ".php");
$pageDetails = getPageDetails($page, $conn);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageDetails['title']; ?> - Serwis Informacyjny</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .article-container {
            max-height: 400px;
            overflow: auto;
        }
        .active {
            color: green;
        }
    </style>
</head>
<body>
<header>
    <h1><?php echo $pageDetails['header']; ?></h1>
    <nav>
        <ul>
            <li><a href="../index.php" class="<?php echo $page == 'index' ? 'active' : ''; ?>">Strona główna</a></li>
            <li><a href="../main_pages/news.php" class="<?php echo $page == 'news' ? 'active' : ''; ?>">Aktualności</a></li>
            <li><a href="../main_pages/failures.php" class="<?php echo $page == 'failures' ? 'active' : ''; ?>">Awarie</a></li>
            <li><a href="../main_pages/memes.php" class="<?php echo $page == 'memes' ? 'active' : ''; ?>">Memy</a></li>
            <li><a href="../dynamic_pages/authors.php" class="<?php echo $page == 'authors' ? 'active' : ''; ?>">Autorzy</a></li>
        </ul>
    </nav>
</header>
<main>
    <h2><?php echo $pageDetails['header']; ?></h2>
    <div class="article-container">
