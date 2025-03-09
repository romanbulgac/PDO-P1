<?php
class Article
{
    private PDO $conn;
    public int $id;
    public string $title;
    public string $subtitle;
    public string $article;
    public string $category;
    public string $author;
    public string $image;
    public string $date;
    private function generateUniqueId(): int
    {
        $sql = "CREATE PROCEDURE IF NOT EXISTS generate_unique_article_id()
                BEGIN
                    DECLARE new_id INT;
                    REPEAT
                        SET new_id = FLOOR(10000 + RAND() * 90000);
                    UNTIL NOT EXISTS (SELECT 1 FROM article WHERE id = new_id) END REPEAT;
                    SELECT new_id;
                END";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            

            $stmt = $this->conn->query("CALL generate_unique_article_id()");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Ошибка генерации уникального ID: " . $e->getMessage());
        }
    }

    public function __construct(PDO $conn, int $id)
    {
        $this->conn = $conn;
        $this->id = $id;
        $this->loadArticle();
    }

    private function loadArticle(): void
    {
        $sql1 = "CREATE PROCEDURE IF NOT EXISTS get_article_by_id(IN article_id INT)
                BEGIN
                    SELECT * FROM article WHERE id = article_id;
                END";
        try {
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute();


            $stmt = $this->conn->prepare("CALL get_article_by_id(:id)");
            $stmt->execute(['id' => $this->id]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Статья не найдена");
            }

            $this->title = $row['title'];
            $this->subtitle = $row['subtitle'];
            $this->article = $this->readFile($this->id);
            $this->category = $row['category'];
            $this->author = $row['author'];
            $this->image = $row['image'];
            $this->date = $row['date'];
        } catch (PDOException $e) {
            throw new Exception("Ошибка загрузки статьи: " . $e->getMessage());
        }
    }

    private static function writeFile(string $data, int $id): string
    {
        $filePath = "./assets/articles/{$id}.txt";
        file_put_contents($filePath, $data);
        return $filePath;
    }

    private static function readFile(int $dataId): string
    {
        $filePath = "./assets/articles/{$dataId}.txt";
        return file_exists($filePath) ? file_get_contents($filePath) : '';
    }

    public static function upload(
        PDO $conn,
        string $title,
        string $subtitle,
        string $article,
        string $category,
        string $image,
        string $author
    ): int {

        $sql1 = "DROP PROCEDURE IF EXISTS insert_article";
        $sql2 = "CREATE PROCEDURE insert_article(
                IN p_id INT, 
                IN p_title VARCHAR(255), 
                IN p_subtitle VARCHAR(255), 
                IN p_article_path VARCHAR(255), 
                IN p_category VARCHAR(100), 
                IN p_image VARCHAR(255), 
                IN p_author VARCHAR(100)
            )
            BEGIN
                INSERT INTO article (
                    id, title, subtitle, article, category, image, author, date
                ) VALUES (
                    p_id, p_title, p_subtitle, p_article_path, 
                    p_category, p_image, p_author, CURDATE()
                );
            END";
        try {

            $stmt1 = $conn->prepare($sql1);
            $stmt2 = $conn->prepare($sql2);
            $stmt1->execute();
            $stmt2->execute();


            $id = (new self($conn, 0))->generateUniqueId();

            // Запись файла
            $articlePath = self::writeFile($article, $id);

            $stmt = $conn->prepare("CALL insert_article(
                :id, :title, :subtitle, :article_path, 
                :category, :image, :author
            )");

            $stmt->execute([
                'id' => $id,
                'title' => $title,
                'subtitle' => $subtitle,
                'article_path' => $articlePath,
                'category' => $category,
                'image' => $image,
                'author' => $author
            ]);

            return $id;
        } catch (PDOException $e) {
            throw new Exception("Ошибка загрузки статьи: " . $e->getMessage());
        }
    }

    public function edit(
        string $title,
        string $subtitle,
        string $article,
        string $category,
        string $image,
        string $author
    ): void {

        $sql = "
            CREATE PROCEDURE IF NOT EXISTS update_article(
                    IN p_id INT,
                    IN p_title VARCHAR(255), 
                    IN p_subtitle VARCHAR(255), 
                    IN p_article_path VARCHAR(255), 
                    IN p_category VARCHAR(100), 
                    IN p_image VARCHAR(255), 
                    IN p_author VARCHAR(100)
                )
                BEGIN
                    UPDATE article SET 
                        title = p_title, 
                        subtitle = p_subtitle, 
                        article = p_article_path, 
                        category = p_category, 
                        image = p_image, 
                        author = p_author, 
                        date = CURDATE() 
                    WHERE id = p_id;
                END";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $articlePath = self::writeFile($article, $this->id);

            $stmt = $this->conn->prepare("CALL update_article(
                :id, :title, :subtitle, :article_path, 
                :category, :image, :author
            )");

            $stmt->execute([
                'id' => $this->id,
                'title' => $title,
                'subtitle' => $subtitle,
                'article_path' => $articlePath,
                'category' => $category,
                'image' => $image,
                'author' => $author
            ]);

            $this->title = $title;
            $this->subtitle = $subtitle;
            $this->article = $article;
            $this->category = $category;
            $this->image = $image;
            $this->author = $author;
        } catch (PDOException $e) {
            throw new Exception("Ошибка при редактировании статьи: " . $e->getMessage());
        }
    }

    public static function exists(int $id, PDO $conn): bool
    {

        $sql = "
                CREATE PROCEDURE IF NOT EXISTS check_article_exists(IN p_id INT)
                BEGIN
                    SELECT COUNT(*) FROM article WHERE id = p_id;
                END";
        try {

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $stmt = $conn->prepare("CALL check_article_exists(:id)");
            $stmt->execute(['id' => $id]);

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Ошибка проверки существования статьи: " . $e->getMessage());
        }
    }


    public function delete(): void
    {

        $sql = "
            CREATE PROCEDURE IF NOT EXISTS delete_article(IN p_id INT)
                BEGIN
                    DELETE FROM article WHERE id = p_id;
                END";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $filePath = "./assets/articles/{$this->id}.txt";
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $stmt = $this->conn->prepare("CALL delete_article(:id)");
            $stmt->execute(['id' => $this->id]);
        } catch (PDOException $e) {
            throw new Exception("Ошибка при удалении статьи: " . $e->getMessage());
        }
    }
}
