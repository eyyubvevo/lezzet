<?php

namespace App\Providers;
use App\Models\Category;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $categories = Category::orderBy('order','asc')->get();

        view()->share('categories', $categories);
        view()->share('available_locales', locales());

        view()->composer('components.header', function ($view) {
            $view->with('current_locale', app()->getLocale());
            $view->with('available_locales', locales());
        });
    }
}
