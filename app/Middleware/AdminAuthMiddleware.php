<?php

namespace App\Middleware;

use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

class AdminAuthMiddleware implements MiddlewareInterface
{
    /**
     * Process the request - check if user is authenticated AND is an admin.
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // Get authentication status
        $authStatus = SessionManager::get('is_authenticated');
        // Get user role
        $userRole = SessionManager::get('user_role');

        // If NOT authenticated:
        //       - Use FlashMessage::error() with message: "Please log in to access the admin panel."
        //       - Create a redirect response using the Psr17Factory (same pattern as AuthMiddleware)
        //       - Redirect to 'auth.login' route (same pattern as AuthMiddleware)
        if ($authStatus === null || $authStatus === false) {
            FlashMessage::error("This page cannot be accessed without logging in.");

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $loginUrl = $routeParser->urlFor('auth.login');
            $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            $response = $psr17Factory->createResponse(302);
            return $response->withHeader('Location', $loginUrl);
        }

        // If authenticated but role is NOT 'admin':
        if ($authStatus === true && $userRole != 'admin') {
            FlashMessage::error("Access denied. Admin privileges required.");

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $userUrl = $routeParser->urlFor('user.dashboard');
            $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            $response = $psr17Factory->createResponse(302);
            return $response->withHeader('Location', $userUrl);
        }

        // If authenticated AND admin, continue to admin route
        FlashMessage::success("Admin login successful.");

        return $handler->handle($request);
    }
}
