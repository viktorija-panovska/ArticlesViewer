<?php


class Controller
{
    private $url_prefix = '/~62533848/cms/';
    private $model;
    private $view;


    public function __construct($model, $view) {
        $this->model = $model;
        $this->view = $view;
    }


    public function processGetRequest() {

        $url = explode('?', $_SERVER['REQUEST_URI'])[0];

        if ($url === $this->url_prefix || $url === $this->url_prefix . 'articles') {
            header("Location: " . $this->url_prefix . "articles/1");
            exit();
        }

        else if (preg_match('/^' . preg_quote($this->url_prefix, '/') . 'articles\/[0-9]+$/', $url))
            $this->goToArticleListPage($this->getPageOrArticleIndex($url));

        else if (preg_match('/^' . preg_quote($this->url_prefix, '/') . 'article\/[0-9]+$/', $url))
            $this->goToArticleDetailPage($this->getPageOrArticleIndex($url));

        else if (preg_match('/^' . preg_quote($this->url_prefix, '/') . 'article-edit\/[0-9]+$/', $url))
            $this->goToArticleEditPage($this->getPageOrArticleIndex($url));

        else
            self::response_NotFound();
    }

    private function goToArticleListPage($pageId) {
        $articleList = $this->model->getArticleListForPage($pageId);

        if ($articleList === null) {
            self::response_NotFound();
            return;
        }

        $totalArticles = $this->model->getTotalArticleNumber();
        $this->view->showArticleListPage($articleList, $pageId, $totalArticles);
    }

    private function goToArticleDetailPage($articleId) {
        $article = $this->model->getArticle($articleId);

        if ($article === null) {
            self::response_NotFound();
            return;
        }

        $utmSource = filter_input(INPUT_GET, 'utm_source');

        if ($utmSource !== null && $utmSource !== false && preg_match('/[0-9a-z]{1,64}/', $utmSource))
            $this->model->logArticleAccess($articleId, $utmSource);

        $this->view->showArticleDetailPage($article);
    }

    private function goToArticleEditPage($articleId) {
        $article = $this->model->getArticle($articleId);

        if ($article === null) {
            self::response_NotFound();
            return;
        }

        $accessList = $this->model->getAccessListForArticle($articleId);
        $this->view->showArticleEditPage($article, $accessList);
    }

    private function getPageOrArticleIndex($url) {
        $urlParts = explode('/', $url);
        return intval(end($urlParts));
    }

    public function processPostRequest() {

        $action = filter_input(INPUT_POST, 'action');

        if ($action === null || $action === false) {
            self::response_BadRequest("Undefined post action.");
            return;
        }

        $articleName = filter_input(INPUT_POST, 'name');

        if ($articleName === null || $articleName === false) {
            self::reponse_BadRequest("Undefined article name.");
            return;
        }

        if ($action === 'update') {
            $articleId = filter_input(INPUT_POST, 'id');
            $articleContent = filter_input(INPUT_POST, 'content');

            if ($articleId === null || $articleId === false)
                self::response_BadRequest("Undefined article id.");
            else if ($articleContent === null || $articleContent === false)
                self::response_BadRequest("Undefined article content.");
            else
                $this->model->updateArticle($articleId, $articleName, $articleContent);
        }

        else if ($action === 'insert') {
            $articleId = $this->model->insertArticle($articleName);
            echo $articleId;
        }

        else {
            self::response_BadRequest("Unknown post action. Post action needs to be 'update' or 'insert'.");
        }

    }


    public function processDeleteRequest() {

        $articleId = filter_input(INPUT_GET, 'deleteId', FILTER_VALIDATE_INT);

        if ($articleId === null || $articleId === false) {
            self::response_BadRequest("Undefined article id.");
            return;
        }

        $this->model->deleteArticle($articleId);

        $urlElements = explode('/', str_replace('?', '/', $_SERVER['REQUEST_URI']));
        $pageId = $urlElements[count($urlElements) - 2];

        if (!is_numeric($pageId)) {
            self::response_BadRequest("Page id is not a number.");
            return;
        }

        $articleList = $this->model->getArticleListForPage($pageId);

        if ($articleList === null)
            return;

        echo $this->view->getArticleListHtml($articleList);

    }


    public static function response_BadRequest($message) {
        print($message);
        http_response_code(400);
    }

    public static function response_NotFound() {
        http_response_code(404);
    }

    public static function response_InternalServerError($message) {
        print($message);
        http_response_code(500);
    }
}