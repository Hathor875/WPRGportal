<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "1234";
    private $database = "myDB";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error in SQL query: " . $this->conn->error);
        }
        return $result;
    }

    public function clearDatabase() {
        $this->conn->query("SET FOREIGN_KEY_CHECKS = 0");

        $tables = [];
        $result = $this->conn->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        foreach ($tables as $table) {
            if ($table != 'users') {
                $this->conn->query("DROP TABLE IF EXISTS $table");
            }
        }
        $this->conn->query("CREATE TABLE authors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255)
    )");

        $this->conn->query("CREATE TABLE articles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        content TEXT,
        category VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        author_id INT,
        FOREIGN KEY (author_id) REFERENCES authors(id)
    )");

        $this->conn->query("CREATE TABLE images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image_url VARCHAR(255)
    )");

        $this->conn->query("CREATE TABLE articles_images (
        article_id INT,
        image_id INT,
        PRIMARY KEY (article_id, image_id),
        FOREIGN KEY (article_id) REFERENCES articles(id),
        FOREIGN KEY (image_id) REFERENCES images(id)
    )");



        $this->conn->query("SET FOREIGN_KEY_CHECKS = 1");
        return true;
    }

    public function searchComment($commentId) {
        $stmt = $this->conn->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();
        $stmt->close();
        return $comment;
    }

    public function deleteComment($commentId) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function searchArticle($articleId) {
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();
        $stmt->close();
        return $article;
    }

    public function deleteArticle($articleId) {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows > 0;
    }

    public function close() {
        $this->conn->close();
    }
}
?>
