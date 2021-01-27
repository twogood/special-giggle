<?php
declare(strict_types=1);

namespace App\News;


use App\ErrorModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

class NewsApi
{
    /**
     * @var NewsService
     */
    private NewsService $newsService;


    /**
     * CounterApi constructor.
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function setup(Group $group)
    {
        $group->get('', function (Request $request, Response $response, $args) {
            $response->getBody()->write(json_encode($this->newsService->getNews()));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->post('', function (Request $request, Response $response, $args) {
            $model = $this->getNewsModelFromRequest();
            $returnModel = $this->newsService->createNewsItem($model);
            $response->getBody()->write(json_encode($returnModel));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->get('/{id}', function (Request $request, Response $response, $args) {
            $newsModel = $this->newsService->getNewsItem((int)$args['id']);
            if (!$newsModel) {
                $response->getBody()->write(json_encode(new ErrorModel("Counter not found")));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
            $response->getBody()->write(json_encode($newsModel));
            return $response->withHeader('Content-Type', 'application/json');
        });
        $group->put('/{id}', function (Request $request, Response $response, $args) {
            $model = $this->getNewsModelFromRequest();
            $model->id = (int)$args['id'];
            $returnModel = $this->newsService->updateNewsItem($model);
            $response->getBody()->write(json_encode($returnModel));
            return $response->withHeader('Content-Type', 'application/json');
        });
    }

    /**
     * @return NewsModel
     */
    private function getNewsModelFromRequest(): NewsModel
    {
        $input = json_decode(file_get_contents('php://input'));
        $title = $input->title;
        $content = $input->content;
        return new NewsModel($title, $content);
    }
}
