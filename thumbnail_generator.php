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


function generateAuthorThumbnail($name, $email, $id) {
    return '
<div class="article-thumbnail">
    <h3>' . htmlspecialchars($name) . '</h3>
    <p>Email: ' . htmlspecialchars($email) . '</p>
</div>';
}
?>





<style>

    .article-thumbnail {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        text-decoration: none;
        display: block;
        color: #000;
        border-radius: 5px; 
    }

    .article-title {
        font-weight: bold;
        color: #002147;
        margin-bottom: 5px;
    }

    .article-content {
        font-size: 14px;
        line-height: 1.5;
    }
</style>
