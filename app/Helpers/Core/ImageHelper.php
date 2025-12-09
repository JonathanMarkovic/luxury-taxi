<?php
namespace App\Helpers;

class ImageHelper
{
    /**
     * Normalize image path for the browser
     *
     * @param string $path Full server path or filename
     * @return string Relative path usable in <img src="">
     */
    public static function normalize(string $path): string
    {
        // If path contains Windows backslashes, replace with forward slashes
        $path = str_replace('\\', '/', $path);

        // Extract just the filename if full path given
        if (strpos($path, 'uploads/images/') !== false) {
            $parts = explode('uploads/images/', $path);
            $filename = end($parts);
        } else {
            $filename = basename($path);
        }

        // Return relative path
        return 'uploads/images/' . $filename;
    }
}
