<?php
function generateThumbnail($title, $content, $article_id) {
    $title = strip_tags($title);
    $content_lines = explode("\n", wordwrap(strip_tags($content), 100));
    $summary = implode("\n", array_slice($content_lines, 0, 3));

    return "<a href='article.php?id=$article_id' class='article-thumbnail'>
                <h2 class='article-title'>$title</h2>
                <p class='article-content'>$summary</p>
            </a>";
}


function generateAuthorThumbnail($name, $email) {
    return '
<div class="article-thumbnail">
    <h3>' . htmlspecialchars($name) . '</h3>
    <p>Email: ' . htmlspecialchars($email) . '</p>
</div>';
}



