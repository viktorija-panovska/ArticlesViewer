<?php

$buttonsHtml = getButtonsHtml($pageId, $totalArticles);


return '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Articles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../styles.css">
    <script type="text/javascript" src="../scripts/script_articleList.js"></script>
</head>
<body>
    <div id="page-container">
        <h1>Article List</h1>
        <div id="article-list">
        ' . $articleListHtml . '
        </div>
        <div id="button-container">
        ' . $buttonsHtml . '
        </div>
    </div>
    <dialog id="article-create-dialog">
        <form id="article-create-form">
            <div id="name-field">
                <label for="name">Enter Article Name:</label>
                <br>
                <input type="text" class="input-field" id="name-input" name="name" maxlength="32" required>
            </div>
            <div id="button-container">
                <input class="button" id="create-article-button" type="submit" value="CREATE">
                <input class="button" id="back-button" type="button" value="CANCEL">
            </div>
            <input type="hidden" id="action" name="action" value="insert">
        </form>
    </dialog>
</body>
</html>
';


function getButtonsHtml($pageId, $totalArticles) {

    $pageCount = ceil($totalArticles / 10);

    $buttons = '';

    if ($pageId !== 1)
        $buttons .= '            <button class="button" id="previous-button">Previous</button>';

    if ($pageId != $pageCount)
        $buttons .= '            <button class="button" id="next-button">Next</button>';

    return $buttons . '
            <div id="page-number">Page ' . htmlspecialchars($pageId) . ' of ' . htmlspecialchars($pageCount) .'</div>
            <button class="button" id="create-button">Create article</button>';

}
