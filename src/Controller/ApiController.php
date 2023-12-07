<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController extends Controller
{
	public function search(Request $request, Response $response) 
    {
        $albums = json_decode(file_get_contents(__DIR__.'/../../data/albums.json'), true);

        $queryParams = $request->getQueryParams();
        $query = $queryParams['q'] ?? null;

        if($query === ''){
            return $response->withStatus(400)->withJson(['error' => 'Invalid request']);
        }

        if ($query) {
            $albums = array_values(array_filter($albums, function($album) use ($query) {
                return strpos($album['title'], $query) !== false or
                    strpos($album['artist'], $query) !== false;
            }));
        }

        $response->getBody()->write(json_encode($albums));
    return $response->withHeader('Content-Type', 'application/json');
    }

}