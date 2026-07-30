<?php

namespace App\Providers;

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
        $this->app->resolving(\Illuminate\Console\Command::class, function ($command, $app) {
            $command->setLaravel($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('mail.mailers.smtp.auth_mode') === 'xoauth2') {
            try {
                $client = new \Google_Client();
                $client->setClientId(env('GOOGLE_CLIENT_ID'));
                $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
                $client->refreshToken(env('GOOGLE_REFRESH_TOKEN'));
                
                $token = $client->getAccessToken();
                if ($token && isset($token['access_token'])) {
                    config(['mail.mailers.smtp.password' => $token['access_token']]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gmail OAuth2 Error: ' . $e->getMessage());
            }
        }
    }
}
