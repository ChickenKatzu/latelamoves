<?php

namespace App\Helpers;

use Illuminate\Support\HtmlString;

class ViteHelper
{
    public static function assets()
    {
        $manifestPath = public_path('build/manifest.json');

        if (!file_exists($manifestPath)) {
            return new HtmlString('<!-- Vite manifest not found -->');
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $html = '';

        // CSS
        if (isset($manifest['resources/css/app.css'])) {
            $cssFile = $manifest['resources/css/app.css']['file'];
            $html .= '<link rel="stylesheet" href="' . asset($cssFile) . '">' . "\n";
        }

        // JS
        if (isset($manifest['resources/js/app.js'])) {
            $jsFile = $manifest['resources/js/app.js']['file'];
            $html .= '<script src="' . asset($jsFile) . '" defer></script>' . "\n";
        }

        return new HtmlString($html);
    }
}
