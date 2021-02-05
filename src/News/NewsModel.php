<?php
declare(strict_types=1);

namespace App\News;


class NewsModel
{
    public int $id;
    public string $created;
    public string $title;
    public string $content;
    public string $preview;
}
