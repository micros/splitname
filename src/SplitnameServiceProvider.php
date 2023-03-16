<?php
// MyVendor\contactform\src\ContactFormServiceProvider.php
namespace micros\splitname;

use Illuminate\Support\ServiceProvider;

class SplitnameServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
    }
    public function register()
    {
        // Pendiente
    }
}
