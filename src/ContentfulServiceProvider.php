<?php
/**
 * @copyright 2016-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Laravel;

use Contentful\Delivery\Client as DeliveryClient;
use Illuminate\Support\ServiceProvider;

class ContentfulServiceProvider extends ServiceProvider
{
    const VERSION = '2.1.0-dev';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $config_file = realpath(__DIR__ . '/config/contentful.php');

        $this->publishes([
            $config_file => $this->app->make('path.config') . '/contentful.php',
        ]);

        $this->mergeConfigFrom($config_file, 'contentful');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DeliveryClient::class, function ($app) {
            $config = $app['config']['contentful'];

            return (new DeliveryClient(
                $config['delivery.token'],
                $config['delivery.space'],
                $config['delivery.environment'],
                $config['delivery.preview'],
                $config['delivery.defaultLocale'],
                $config['delivery.options']
            ))->setIntegration('contentful.laravel', self::VERSION);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [DeliveryClient::class];
    }
}
