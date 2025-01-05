<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Passport\Passport;
use Spatie\LaravelSettings\Settings;

use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Retrieve the general settings
        $settings = app(GeneralSettings::class); // Using dependency injection

        // Share the settings with all views
        View::share('settings', $settings);

        // Optional: Theme and user sharing
        View::composer('layouts.partials.*', function ($view) {
            $view->with('theme', session('theme', 'light')); // Pass theme globally
            $view->with('user', auth()->user()); // Pass authenticated user globally

            Blade::directive('lang', function ($text) {
                return "<?php echo __('$text'); ?>";
            });
        });
    }}
