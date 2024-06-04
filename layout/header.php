<?php
include '../DatabaseHandle/Database.php';

$db = new Database();

function getPageDetails($page, $db) {
    $pages = [
        'index' => ['title' => 'Strona główna', 'header' => 'Witamy na stronie głównej'],
        'news' => ['title' => 'Aktualności', 'header' => 'Najnowsze wiadomości'],
        'failures' => ['title' => 'Awarie', 'header' => 'Informacje o awariach'],
        'memes' => ['title' => 'Memy', 'header' => 'Najlepsze memy'],
        'authors' => ['title' => 'Autorzy', 'header' => 'Nasi autorzy'],
    ];

    if ($page == 'article' && isset($_GET['id'])) {
        $articleId = intval($_GET['id']);
        $article = getArticleDetails($articleId, $db);

        if ($article) {
            return ['title' => $article['title'], 'header' => $article['title']];
        }
    }

    if (array_key_exists($page, $pages)) {
        return ['title' => $pages[$page]['title'], 'header' => $pages[$page]['header']];
    }

    return ['title' => 'Strona', 'header' => ''];
}

function getArticleDetails($id, $db) {
    $stmt = $db->conn->prepare("SELECT title FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

$page = basename($_SERVER['PHP_SELF'], ".php");
$pageDetails = getPageDetails($page, $db);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageDetails['title']; ?> - Serwis Informacyjny</title>
    <link rel="stylesheet" href="/portal/css/style.css">
</head>
<body>
<header>
    <h1><?php echo $pageDetails['header']; ?></h1>
    <nav>
        <ul>
            <li><a href="/portal/index.php" class="<?php echo $page == 'index' ? 'active' : ''; ?>">Strona główna</a></li>
            <li><a href="/portal/news.php" class="<?php echo $page == 'news' ? 'active' : ''; ?>">Aktualności</a></li>
            <li><a href="/portal/failures.php" class="<?php echo $page == 'failures' ? 'active' : ''; ?>">Awarie</a></li>
            <li><a href="/portal/memes.php" class="<?php echo $page == 'memes' ? 'active' : ''; ?>">Memy</a></li>
            <li><a href="/portal/authors.php" class="<?php echo $page == 'authors' ? 'active' : ''; ?>">Autorzy</a></li>
        </ul>
    </nav>
</header>
<main>
    <h2><?php echo $pageDetails['header']; ?></h2>
    <div class="article-container">
    </div>
</main>
</body>
</html>
