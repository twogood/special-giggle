<?php
declare(strict_types=1);

namespace App\News;


class NewsModel
{
    public int $id;
    public string $created;
    public string $title;
    public string $content;

    /**
     * NewsModel constructor.
     * @param string $title
     * @param string $content
     */
    public function __construct(string $title = null, string $content = null)
    {
        if (isset($title)) {
            $this->title = $title;
        }
        if (isset($content)) {
            $this->content = $content;
        }
    }


}
