<?php

namespace App\Middleware;

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Process the request - check if user is authenticated.
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // Check if user is authenticated using SessionManager::get('is_authenticated')
        $isAuthenticated = SessionManager::get('is_authenticated');

        // If NOT authenticated:
        if (!$isAuthenticated) {
            FlashMessage::error("Please log in to access this page.");
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $loginUrl = $routeParser->urlFor('auth.login');
            // Create a response object using the Psr17Factory:
            $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            $response = $psr17Factory->createResponse(302);

            // Redirect to the login page:
            return $response->withHeader('Location', $loginUrl)->withStatus(302);
        }

        // If authenticated, continue to the next middleware/route handler
        return $handler->handle($request);
    }
}
