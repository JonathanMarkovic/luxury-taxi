<?php

declare(strict_types=1);

namespace App\Helpers;

use InvalidArgumentException;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;

/**
 * Translation Helper
 *
 * Provides translation functionality using Symfony Translation component.
 * Supports multiple locales with JSON-based translation files.
 */
class TranslationHelper
{
    private Translator $translator;
    private string $currentLocale;
    private string $defaultLocale;
    private string $langPath;
    private array $availableLocales;

    /**
     * Initialize the Translation Helper
     *
     * @param string $langPath Path to language files directory
     * @param string $defaultLocale Default locale (fallback)
     * @param array $availableLocales List of available locales
     */
    public function __construct(
        string $langPath,
        string $defaultLocale = 'en',
        array $availableLocales = ['fr', 'en']
    ) {
        // TODO: Store all constructor parameters in their corresponding class properties
        // Hint: You have 4 properties to initialize (langPath, defaultLocale, currentLocale, availableLocales)
        // Note: currentLocale should start as the same value as defaultLocale
        $this->defaultLocale = $defaultLocale;
        $this->availableLocales = $availableLocales;
        $this->langPath = $langPath;
        $this->currentLocale = $defaultLocale;

        // TODO: Create a new Translator instance and store it in $this->translator
        // Hint: Pass the current locale to the Translator constructor

        $this->translator = new Translator($this->currentLocale);

        // TODO: Configure the translator to fall back to the default locale if a translation is missing
        // Hint: Use the setFallbackLocales() method with an array containing the default locale

        $this->translator->setFallbackLocales([$this->defaultLocale]);

        // TODO: Register the JSON file loader with the translator
        // Hint: Use addLoader() method with 'json' as the format name
        $this->translator->addLoader('json', new JsonFileLoader());

        // TODO: Load all translation files
        // Hint: Call the loadTranslations() method

        $this->loadTranslations();
    }

    /**
     * Load translation files for all available locales
     */
    private function loadTranslations(): void
    {
        // TODO: Loop through each locale in availableLocales array
        foreach ($this->availableLocales as $locale) {
            $path = $this->langPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . "messages.json";
            // dd($path);
            // echo $path . '<br>';

            if (file_exists($path)) {
                // dd($this->currentLocale);
                $this->translator->addResource('json', $path, $locale);
            }
        }
        // dd($this->translator);
        // exit;
        // TODO: For each locale, build the file path to messages.json
        // Hint: Use DIRECTORY_SEPARATOR constant for cross-platform compatibility
        // Example path: lang/en/messages.json

        // TODO: Check if the file exists before trying to load it

        // TODO: If file exists, register it with the translator using addResource()
        // Parameters: format ('json'), file path, locale code, domain ('messages')
    }

    /**
     * Translate a message
     *
     * @param string $key Translation key (supports dot notation: 'home.welcome')
     * @param array $parameters Parameters to replace in translation
     * @param string|null $locale Override current locale for this translation
     * @return string Translated message
     */
    public function trans(string $key, array $parameters = [], ?string $locale = null): string
    {
        // If no locale is provided, use the current locale
        $locale = $locale ?? $this->currentLocale;

        // Use the translator's trans() method to translate the key
        return $this->translator->trans($key, $parameters, "messages", $locale);
    }

    /**
     * Set the current locale
     *
     * @param string $locale Locale code (e.g., 'en', 'fr')
     * @throws \InvalidArgumentException If locale is not available
     */
    public function setLocale(string $locale): void
    {
        // TODO: Validate that the requested locale is available
        // Hint: Check if $locale exists in $this->availableLocales array
        // If not available, throw an InvalidArgumentException with a descriptive message
        // dd($this->availableLocales);
        // dd($locale);
        // dd(!key_exists($locale, $this->availableLocales));
        if (!in_array($locale, $this->availableLocales)) {
            throw new InvalidArgumentException("Not a valid language");
        }


        // TODO: Update the currentLocale property with the new locale

        $this->currentLocale = $locale;
        // dd($locale);

        // TODO: Also update the translator's locale
        // Hint: The translator has its own setLocale() method

        $this->translator->setLocale($locale);
    }

    /**
     * Get the current locale
     *
     * @return string Current locale code
     */
    public function getLocale(): string
    {
        // TODO: Return the current locale property
        return $this->currentLocale;
    }

    /**
     * Get the default locale
     *
     * @return string Default locale code
     */
    public function getDefaultLocale(): string
    {
        // TODO: Return the default locale property
        return $this->defaultLocale;
    }

    /**
     * Get all available locales
     *
     * @return array List of available locale codes
     */
    public function getAvailableLocales(): array
    {
        // TODO: Return the available locales array property
        return $this->availableLocales;
    }

    /**
     * Check if a locale is available
     *
     * @param string $locale Locale code to check
     * @return bool True if locale is available
     */
    public function isLocaleAvailable(string $locale): bool
    {
        // TODO: Check if the given locale exists in the availableLocales array
        // Hint: Use in_array() function
        if (in_array($locale, $this->availableLocales)) {
            return true;
        } else {
            return false;
        }
    }
}
