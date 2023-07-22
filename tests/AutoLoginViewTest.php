<?php

namespace Esplora\AutoLogin\Tests;

use Esplora\AutoLogin\AutoLogin;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class AutoLoginViewTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        tap($app->make('config'), function (Repository $config) {
            $config->set('view.paths', [__DIR__]);
        });
    }


    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    protected function defineRoutes($router)
    {
        Route::swap($router);

        AutoLogin::routes('/other-autologin', 'redirect');
    }

    /**
     * @return void
     */
    public function testViewRedirect(): void
    {
        Auth::shouldReceive('loginUsingId')->once()->andreturn(new User());

        $link = AutoLogin::to('/view-link', 1);

        $this->assertStringContainsString('other-autologin', $link);

        $this->get($link)
            ->assertOk()
            ->assertViewIs('redirect')
            ->assertSee('window.location.href = "/view-link";', false);
    }
}
