<?php
declare(strict_types=1);

namespace App\News;


use PDO;
use PDOStatement;

class NewsService
{
    private PDO $pdo;
    private string $getNewsQuery = "select *,date(created) as createdDate,date(updated) as updatedDate from news";

    /**
     * CounterService constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $query
     * @return bool|PDOStatement
     */
    private function prepare(string $query)
    {
        return $this->pdo->prepare($query);
    }

    public function getNews(): array
    {
        $query = $this->getNewsQuery;
        $statement = $this->prepare($query);
        $statement->execute();

        $items = array();
        while ($entry = $this->getNewsModel($statement)) {
            $items[] = $entry;
        }
        return $items;
    }

    public function getNewsItem(int $id): ?NewsModel
    {
        $query = $this->getNewsQuery . " where id=:id";
        $statement = $this->prepare($query);
        $statement->execute(compact('id'));
        return $this->getNewsModel($statement) ?: null;
    }

    public function createNewsItem(NewsModel $model): NewsModel
    {
        $query = "insert into news (title,content) values (:title,:content);";
        $statement = $this->prepare($query);
        $statement->execute([
            'title' => $model->title,
            'content' => $model->content
        ]);

        $id = (int)$this->pdo->lastInsertId();
        return $this->getNewsItem($id);
    }

    public function updateNewsItem(NewsModel $model): ?NewsModel
    {
        $query = "update news set title=:title,content=:content,updated=current_timestamp where id=:id;";
        $statement = $this->prepare($query);
        $statement->execute([
            'id' => $model->id,
            'title' => $model->title,
            'content' => $model->content
        ]);

        return $this->getNewsItem($model->id);
    }

    public function deleteNewsItem(int $id)
    {
        $query = "delete from news where id=:id";
        $statement = $this->prepare($query);
        $statement->execute(['id' => $id]);
    }

    /**
     * @param PDOStatement $statement
     * @return NewsModel|null
     */
    private function getNewsModel(PDOStatement $statement): ?NewsModel
    {
        /**
         * @var $newsModel NewsModel
         */
        $newsModel = $statement->fetchObject(NewsModel::class);
        if ($newsModel == null) {
            return null;
        }
        $newsModel->preview = substr(strip_tags($newsModel->content), 0, 100);
        return $newsModel;
    }

}
