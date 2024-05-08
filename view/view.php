<?php

class View
{
    public function showArticleListPage($articleList, $pageId, $totalArticles) {
        $articleListHtml = $this->getArticleListHtml($articleList);
        $page = require 'view_articleList.php';
        print($page);
    }


    public function showArticleDetailPage($article) {
        $page = require 'view_article.php';
        print($page);
    }


    public function showArticleEditPage($article, $accessList) {
        $page = require 'view_articleEdit.php';
        print($page);
    }


    public function getArticleListHtml($articleList) {

        $articleListHtml = '';
        while ($article = $articleList->fetch_assoc())
            $articleListHtml .= $this->getArticleListEntryHtml($article['name'], $article['id']);

        return $articleListHtml;

    }

    private function getArticleListEntryHtml($articleName, $articleId) {

        return '
            <div class="article">
                <span class="article-name">' . htmlspecialchars($articleName) . '</span>
                <div class="article-actions">
                    <a class="article-show" href="../article/' . htmlspecialchars($articleId) . '">Show</a>
                    <a class="article-edit" href="../article-edit/' . htmlspecialchars($articleId) . '">Edit</a>
                    <span class="article-delete" id="delete-' . htmlspecialchars($articleId) . '">Delete</span>
                </div>
            </div>
        ';

    }
}