<?php

$content = $article['content'] === null ? "" : $article['content'];
$accessListHtml = getAccessListHtml($accessList);

return '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit article</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../styles.css">
    <script type="text/javascript" src="../scripts/script_articleEdit.js"></script>
</head>
<body>
    <div id="page-container">
        <form id="article-form" action="?" method="POST">
            <input type="hidden" id="id" name="id" value="' . htmlspecialchars($article['id']) . '">
            <input type="hidden" id="action" name="action" value="update">
            <div id="name-field">
                <label for="name">Name</label>
                <br>
                <input type="text" class="input-field" id="name-input" name="name" maxlength="32" value="' . htmlspecialchars($article['name']) . '" required>
            </div>
            <div id="content-field">
                <label for="content">Content</label>
                <br>
                <textarea class="input-field" id="content-input" name="content" maxlength="1024">' . htmlspecialchars($content) . '</textarea>
            </div>
            <div id="button-container">
                <input class="button" id="save-button" type="submit" value="SAVE">
                <input class="button" id="back-button" type="button" value="Back to articles">
            </div>
            <div id="access-list">
                <h4>Users that have accessed the article</h4>
                <div class="access-entry">
                    <h5 id="utm-source-title">UTM_SOURCE</h5>
                    <h5 id="access-title">TIMES ACCESSED</h5>
                </div>
                ' . $accessListHtml . '
            </div>
            <div id="generate-referral">
                <h4>Generate Referral Link</h4>
                <label for="utm-input">Input utm_source</label>
                <br>
                <input name="utm-input" class="input-field" id="utm-input" type="text" maxlength="64">
                <a id="referral-link" href="">' . htmlspecialchars($article['name']) . '</a>
            </div>
        </form>
    </div>
</body>
</html>
';


function getAccessListHtml($accessList) {

    $accessListHtml = '';
    while ($entry = $accessList->fetch_assoc())
        $accessListHtml .= getAccessListEntryHtml($entry['utmSource'], $entry['access']);

    return $accessListHtml;

}


function getAccessListEntryHtml($utmSource, $access) {

    return '
        <div class="access-entry">
            <div class="utm-source">' . htmlspecialchars($utmSource) . '</div>
            <div class="access">' . htmlspecialchars($access) . '</div>
        </div>
    ';

}