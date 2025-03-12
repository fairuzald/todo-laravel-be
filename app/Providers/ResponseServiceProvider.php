<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->extendResponseFactory();
    }

    /**
     * Extend the Laravel Response Factory.
     *
     * @return void
     */
    protected function extendResponseFactory(): void
    {
        $this->app->make(ResponseFactory::class)->macro('success', function ($data = null, string $message = 'Success', int $statusCode = 200) {
            $response = [
                'success' => true,
                'message' => $message,
            ];

            if ($data !== null) {
                $response['data'] = $data;
            }

            return response()->json($response, $statusCode);
        });

        $this->app->make(ResponseFactory::class)->macro('error', function (string $message = 'Error', int $statusCode = 400, $errors = null) {
            $response = [
                'success' => false,
                'message' => $message,
            ];

            if ($errors !== null) {
                $response['errors'] = $errors;
            }

            return response()->json($response, $statusCode);
        });
    }
}
