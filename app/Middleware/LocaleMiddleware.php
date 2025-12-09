<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\SessionManager;
use App\Helpers\TranslationHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Locale Middleware
 *
 * Detects and sets the application locale based on:
 * 1. Query parameter (?lang=fr) - Highest priority
 * 2. Session value - Previously saved preference
 * 3. Default locale - Fallback
 */
class LocaleMiddleware implements MiddlewareInterface
{
    private TranslationHelper $translator;

    /**
     * Initialize the Locale Middleware
     *
     * @param TranslationHelper $translator Translation helper service
     */
    public function __construct(TranslationHelper $translator)
    {
        // TODO: Store the translator parameter in the class property
        $this->translator = $translator;
    }

    /**
     * Process an incoming server request.
     *
     * Detects the user's preferred locale from query parameters or session,
     * and sets it in the translation helper.
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // TODO: Get query parameters from the request
        // Hint: Use $request->getQueryParams()
        // dd($_SESSION);
        $query = $request->getQueryParams();

        // TODO: Extract the 'lang' parameter from query params (if provided)
        // Hint: Use null coalescing operator (??) to default to null

        $lang = $query['lang'] ?? null;

        // Determine which locale to use based on priority:
        // Priority 1: URL parameter (?lang=fr)
        // Priority 2: Session value (retrieve 'locale' key from session using SessionManager)
        // Priority 3: Default locale
        $locale = $lang ?? SessionManager::get('locale') ?? $this->translator->getDefaultLocale();

        // If a NEW locale was explicitly requested via URL parameter AND it's valid:
        // 1. Set it in the translator
        // 2. Save it to the session for future requests
        if ($lang !== null && $this->translator->isLocaleAvailable($lang)) {
            $this->translator->setLocale($lang);
            SessionManager::set('locale', $lang);
        }
        // If no URL parameter was provided, but session has a saved locale AND it's valid:
        // Set the session locale in the translator
        elseif (SessionManager::get('locale') !== null && $this->translator->isLocaleAvailable(SessionManager::get('locale'))) {
            $this->translator->setLocale(SessionManager::get('locale'));
        }

        // Store the current locale in the request as an attribute named 'locale'
        $request = $request->withAttribute('locale', $this->translator->getLocale());

        // TODO: Pass the request to the next middleware/handler and return the response
        $response = $handler->handle($request);

        return $response;
    }
}
