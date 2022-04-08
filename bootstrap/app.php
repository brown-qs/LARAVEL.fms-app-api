<?php


require_once __DIR__ . '/../vendor/autoload.php';

try {
    $host      = (php_sapi_name() === 'cli' || php_sapi_name() === 'cli-server') ? 'cli' : $_SERVER['HTTP_HOST'];
    $urlEnvMap = [
        'staging.api.adventure.trakking.net' => '.trakkingenv',
        'api.adventure.trakking.net'         => '.trakkingenv',

        'api2.fleet.scorpiontrack.com'         => '.scorpionenv',
        'staging.api2.fleet.scorpiontrack.com' => '.scorpionenv',

        'api.fleetcore.scorpiontrack.com' => '.fcrenv',

        'api.oman.scorpiontrack.com' => '.scorpionenv',

        'staging.api.deliverymates.scorpiontrack.com' => '.otlenv',
        'api.deliverymates.scorpiontrack.com'         => '.otlenv',

        'fapi.local' => '.localenv',
        'cli'        => '.localenv',
    ];

    $env = $urlEnvMap[$host] ?? '.scorpionenv';
    (new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(__DIR__ . '/../', $env))->bootstrap();
} catch (Dotenv\Exception\InvalidPathException $e) {
    dd($e);
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();
$app->withEloquent();

$app->configure('app');
$app->configure('auth');
$app->configure('cache');
$app->configure('cors');
$app->configure('database');
$app->configure('queue');
$app->configure('scorpion');
$app->configure('services');
$app->configure('validation');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->bind('bench', function () {
    return new App\Support\Bench();
});

$app->bind('internationalisation', function () {
    return new App\Support\Internationalisation();
});

$app->bind('emailapi', function () {
    return new App\Support\EmailAPI();
});

$app->bind('smsapi', function () {
    return new App\Support\SMSAPI();
});

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    App\Http\Middleware\AcceptJsonMiddleware::class,
    Fruitcake\Cors\HandleCors::class,
    App\Http\Middleware\CorsMiddleware::class
]);

$app->routeMiddleware([
    'api'            => App\Http\Middleware\ApiRequestMiddleware::class,
    'validate'       => App\Http\Middleware\ValidationMiddleware::class,
    'jwt'            => App\Http\Middleware\JsonWebTokenMiddleware::class,
    'auth'           => App\Http\Middleware\Authenticate::class,
    'user'           => App\Http\Middleware\UserMiddleware::class,
    'user.installer' => App\Http\Middleware\InstallerUserMiddleware::class,
    'user.admin'     => App\Http\Middleware\AdminUserMiddleware::class,
    'user.brandadmin'     => App\Http\Middleware\BrandAdminUserMiddleware::class,
    'throttle'       => App\Http\Middleware\ThrottleRequests::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(Fruitcake\Cors\CorsServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Appzcoder\LumenRoutesList\RoutesCommandServiceProvider::class);
$app->register(Barryvdh\DomPDF\ServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace'  => 'App\Http\Controllers',
    'middleware' => ['api', 'validate'],
], function (Laravel\Lumen\Routing\Router $router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
