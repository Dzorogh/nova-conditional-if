<?php

namespace IziDev\ConditionalField;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use IziDev\ConditionalField\Http\Middleware\Authorize;
use Laravel\Nova\Nova;

class ConditionalFieldProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        $this->app->booted(function () {
            Field::macro('if', function (array $fields, $callback) {
                $this->condition = $callback;

                return $this->withMeta(['dependsOn' => $fields]);
            });
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-sortable', __DIR__ . '/../dist/js/tool.js');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/conditional-field')
                ->namespace('IziDev\ConditionalField\Http\Controllers')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
