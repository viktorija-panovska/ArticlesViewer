<?php

require_once 'controller.php';

class Model
{
    private $mysqli;

    private function openConnection() {
        require 'db_config.php';
        $this->mysqli = new mysqli($db_config['server'], $db_config['login'], $db_config['password'], $db_config['database']);
        if ($this->mysqli->connect_error) {
            Controller::response_InternalServerError("Failed to connect to database");
            exit();
        }
    }

    private function closeConnection() {
        $this->mysqli->close();
    }


    public function getArticle($articleId) {

        $this->openConnection();
        $stmt = $this->mysqli->prepare("SELECT * FROM articles WHERE id=?");
        $stmt->bind_param('i', $articleId);
        $stmt->execute();
        $article = $stmt->get_result()->fetch_assoc();
        $this->closeConnection();

        if ($article === null || $article === false)
            return null;

        return $article;

    }


    public function getArticleListForPage($pageId) {

        $offset = 10 * ($pageId - 1);

        $this->openConnection();
        $stmt = $this->mysqli->prepare("SELECT id, name FROM articles LIMIT 10 OFFSET ?");
        $stmt->bind_param('i', $offset);
        $stmt->execute();
        $articleList = $stmt->get_result();

        if ($articleList->num_rows === 0)
           return null;

        $this->closeConnection();

        return $articleList;

    }


    public function getTotalArticleNumber() {

        $this->openConnection();
        $stmt = $this->mysqli->query("SELECT COUNT(*) FROM articles");
        $articleNumber= $stmt->fetch_row()[0];
        $this->closeConnection();

        return $articleNumber;

    }


    public function updateArticle($articleId, $articleName, $articleContent) {

        $this->openConnection();
        $stmt = $this->mysqli->prepare("UPDATE articles SET name=?, content=? WHERE id=?");
        $stmt->bind_param('ssi', $articleName, $articleContent, $articleId);
        $stmt->execute();
        $this->closeConnection();

    }


    // returns the id of the article that was just inserted
    public function insertArticle($articleName) {

        $this->openConnection();
        $stmt = $this->mysqli->prepare("INSERT INTO articles (name) VALUES (?)");
        $stmt->bind_param('s', $articleName);
        $stmt->execute();

        $stmt = $this->mysqli->query("SELECT id FROM articles ORDER BY id DESC LIMIT 1");
        $articleId = $stmt->fetch_row()[0];
        $this->closeConnection();

        return $articleId;

    }


    public function deleteArticle($articleId) {

        $this->openConnection();
        $stmt = $this->mysqli->prepare("DELETE FROM articles WHERE id=?");
        $stmt->bind_param('i', $articleId);
        $stmt->execute();
        $this->closeConnection();

    }


    public function logArticleAccess($articleId, $utmSource) {

        $this->openConnection();
        $stmt = $this->mysqli->prepare("SELECT id, access FROM articleAccess WHERE articleId=? AND utmSource=?");
        $stmt->bind_param('is', $articleId, $utmSource);
        $stmt->execute();
        $result = $stmt->get_result();
        $entries = $result->num_rows;

        if ($entries > 0) {
            $entry = $result->fetch_row();
            $id = $entry[0];
            $access = $entry[1] + 1;

            $stmt = $this->mysqli->prepare("UPDATE articleAccess SET access=? WHERE id=?");
            $stmt->bind_param('ii', $access, $id);
            $stmt->execute();
        }
        else {
            $stmt = $this->mysqli->prepare("INSERT INTO articleAccess (articleId, utmSource, access) VALUES (?, ?, 1)");
            $stmt->bind_param('is', $articleId, $utmSource);
            $stmt->execute();
        }

        $this->closeConnection();

    }


    public function getAccessListForArticle($articleId) {

        $this->openConnection();
        $stmt = $this->mysqli->prepare("SELECT utmSource, access FROM articleAccess WHERE articleId=?");
        $stmt->bind_param('i', $articleId);
        $stmt->execute();
        $accessList = $stmt->get_result();

        $this->closeConnection();

        return $accessList;

    }

}
