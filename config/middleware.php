<?php

declare(strict_types=1);

use App\Middleware\ExceptionMiddleware;
use App\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    //TODO: Add your middleware here.

    // 1. Parse form data
    $app->addBodyParsingMiddleware();

    // 2. Start session
    $app->add(SessionMiddleware::class);

    // 3. Enable routing
    $app->addRoutingMiddleware();

    //!NOTE: the error handling middleware MUST be added last.
    //!NOTE: You can add override the default error handler with your custom error handler.
    //* For more details, refer to Slim framework's documentation.
    // Add your middleware here.
    // Start the session at the application level.
    //$app->add(SessionStartMiddleware::class);

    // 4. Error handling must be last
    $app->add(ExceptionMiddleware::class);
};
