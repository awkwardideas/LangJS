<?php namespace AwkwardIdeas\LangJS;

use Illuminate\Support\ServiceProvider;

class LangJSServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/langJS.php';

        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/langJS.php', 'langJS');
        $this->app->singleton('command.langjs.build', function ($app) {
            return $app['AwkwardIdeas\LangJS\Commands\LangJSBuild'];
        });
        $this->commands(
            'command.langjs.build'
        );
    }

    /**
     * Get argument array from argument string.
     *
     * @param string $argumentString
     *
     * @return array
     */
    private function getArguments($argumentString)
    {
        return explode(', ', str_replace(['(', ')'], '', $argumentString));
    }

    private function getConfigPath()
    {
        return config_path('langJS.php');
    }

}