<?php

namespace Esplora\AutoLogin\Tests;

use Esplora\AutoLogin\AutoLogin;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class AutoLoginTest extends TestCase
{
    /**
     * Define routes setup.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function defineRoutes($router)
    {
        Route::swap($router);

        AutoLogin::routes();
    }

    /**
     * @return void
     */
    public function testHttpRedirect(): void
    {
        Auth::shouldReceive('loginUsingId')->once()->andreturn(new User());

        $link = AutoLogin::to('/other-link', 1);

        $this->get($link)
            ->assertRedirect('/other-link');
    }

    /**
     * @return void
     */
    public function testHttpWithUserRedirect(): void
    {
        Auth::shouldReceive('id')->once()->andreturn(1);
        Auth::shouldReceive('loginUsingId')->once()->andreturn(new User());

        $link = AutoLogin::to('/other-link');

        $this->get($link)
            ->assertRedirect('/other-link');
    }
}
