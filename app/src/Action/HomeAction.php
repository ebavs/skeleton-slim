<?php
namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeAction extends BaseAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        return $response->withBody("Ok");
    }

}
