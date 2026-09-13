<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Decide en qué idioma responde la aplicación, petición por petición.
 *
 * Los textos viven en lang/es/, lang/en/ (reglas de validación) y
 * lang/en.json (mensajes de la API). Este middleware solo elige cuál usa
 * Laravel, llamando a App::setLocale().
 *
 * Orden de prioridad (gana el primero que dé un idioma soportado):
 *
 *   1. ?lang=en en la URL      → elección explícita, manda sobre todo
 *   2. session('locale')       → lo elegido antes (solo rutas web, la API no tiene sesión)
 *   3. Accept-Language         → el idioma que declara el navegador / cliente HTTP
 *   4. config('app.locale')    → APP_LOCALE del .env ('es')
 *
 * No se usa la IP a propósito: Accept-Language es lo que la persona configuró;
 * la IP solo dice dónde está la conexión (VPN, viajes y hostings la engañan) y
 * además exigiría una base GeoIP externa.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['es']);

        $locale = $this->fromQueryParameter($request, $supportedLocales)
            ?? $this->fromSession($request, $supportedLocales)
            ?? $this->fromHeader($request, $supportedLocales)
            ?? config('app.locale', 'es');

        App::setLocale($locale);

        // Se recuerda para las próximas peticiones web. Las rutas /api/* son
        // stateless: sin el hasSession() esta línea reventaría ahí.
        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        return $next($request);
    }

    /**
     * ?lang=en en la URL. Útil para probar ambos idiomas sin tocar el cliente.
     */
    private function fromQueryParameter(Request $request, array $supportedLocales): ?string
    {
        $locale = $request->query('lang');

        return is_string($locale) && in_array($locale, $supportedLocales, true)
            ? $locale
            : null;
    }

    /**
     * Lo que el usuario eligió en una petición anterior (solo web).
     */
    private function fromSession(Request $request, array $supportedLocales): ?string
    {
        if (! $request->hasSession()) {
            return null;
        }

        $locale = $request->session()->get('locale');

        return is_string($locale) && in_array($locale, $supportedLocales, true)
            ? $locale
            : null;
    }

    /**
     * Cabecera Accept-Language, por ejemplo: "es-CL,es;q=0.9,en-US;q=0.8".
     * getPreferredLanguage() ya entiende los pesos q= y las variantes
     * regionales (es-CL cuenta como es), así que no hay que parsear a mano.
     */
    private function fromHeader(Request $request, array $supportedLocales): ?string
    {
        if (! $request->hasHeader('Accept-Language')) {
            return null;
        }

        $locale = $request->getPreferredLanguage($supportedLocales);

        return in_array($locale, $supportedLocales, true) ? $locale : null;
    }
}
