<?php

$content = $article['content'] === null ? "" : $article['content'];

return '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars($article['name']) . '</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../styles.css">
    <script type="text/javascript" src="../scripts/script_article.js"></script>
</head>
<body>
    <div id="page-container">
        <h1 id="name">' . htmlspecialchars($article['name']) . '</h1>
        <div id="content">' . htmlspecialchars($content) . '</div>
        <div id="button-container">
            <button class="button" id="edit-button">Edit</button>
            <button class="button" id="back-button">Back to articles</button>
        </div>
    </div>
</body>
</html>
';