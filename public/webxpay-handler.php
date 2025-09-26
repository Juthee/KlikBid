<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Bootstrap Laravel properly
    require_once __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';

    // Boot the application
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Create and bind the request
    $request = Illuminate\Http\Request::createFromGlobals();
    $app->instance('request', $request);

    // Set up facades
    Illuminate\Support\Facades\Facade::setFacadeApplication($app);

    // Boot Laravel services
    $app->boot();

    // Now instantiate the controller
    $controller = new App\Http\Controllers\WebXPayController();

    // Call the method
    $response = $controller->handleCallback($request);

    // Handle response
    if ($response instanceof Illuminate\Http\RedirectResponse) {
        header('Location: ' . $response->getTargetUrl());
        exit;
    } else {
        echo $response->getContent();
    }

} catch (Exception $e) {
    error_log("WebXPay handler error: " . $e->getMessage());
    echo "Payment processing error: " . $e->getMessage();
}
?>
