<?php

namespace Esplora\AutoLogin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class AutoLogin
{
    /**
     * Handle the autologin request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        Auth::loginUsingId($request->input('id'));

        $template = $request->route()->defaults['auto-login-template'] ?? null;

        $link = urldecode($request->input('link'));

        return $template === null
            ? redirect()->to($link)
            : view($template, [
                'link' => $link,
            ]);
    }

    /**
     * Register the autologin route.
     *
     * @param string      $uri
     * @param string|null $view
     *
     * @return void
     */
    public static function routes(string $uri = '/autologin', string $view = null): void
    {
        Route::get($uri, self::class)
            ->middleware('signed')
            ->defaults('auto-login-template', $view)
            ->name('autologin');
    }

    /**
     * Return an autologin link to the given path that will log the user in.
     *
     * @param string          $path
     * @param string|int|null $id
     *
     * @return string
     */
    public static function link(string $path = '/', string|int $id = null): string
    {
        $expiresAt = Carbon::now()->addDay();

        return URL::temporarySignedRoute('autologin', $expiresAt, [
            'id'   => $id ?? Auth::id(),
            'link' => urlencode($path),
        ]);
    }

    /**
     * Alias for `link` method
     *
     * @param string          $path
     * @param string|int|null $id
     *
     * @return string
     */
    public static function to(string $path = '/', string|int $id = null)
    {
        return self::link($path, $id);
    }

    /**
     * @param string          $name
     * @param array           $parameters
     * @param string|int|null $id
     *
     * @return string
     */
    public static function route(string $name, $parameters = [], string|int $id = null): string
    {
        return self::link(route($name, $parameters), $id);
    }
}
