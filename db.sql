SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS articles_images;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE authors (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         name VARCHAR(255),
                         email VARCHAR(255)
);


CREATE TABLE articles (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          title VARCHAR(255),
                          content TEXT,
                          category VARCHAR(50),
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          author_id INT,
                          FOREIGN KEY (author_id) REFERENCES authors(id)
);


CREATE TABLE images (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        image_url VARCHAR(255)
);


CREATE TABLE comments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          article_id INT,
                          nickname VARCHAR(255),
                          content TEXT,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (article_id) REFERENCES articles(id)
);


CREATE TABLE articles_images (
                                 article_id INT,
                                 image_id INT,
                                 PRIMARY KEY (article_id, image_id),
                                 FOREIGN KEY (article_id) REFERENCES articles(id),
                                 FOREIGN KEY (image_id) REFERENCES images(id)
);


CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL
);
