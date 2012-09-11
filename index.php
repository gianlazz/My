<?php
session_start();

header("Content-type: text/html; charset=UTF-8");

// Initialize the class loader
require_once('Includes/SplClassLoader.php');
$loader = new SplClassLoader(NULL, 'Includes');
$loader->register();

// Load the config
$config = parse_ini_file('local.ini', true);

// Set debug mode options
if (isset($config['app']['debug']) && $config['app']['debug']) {
    ini_set('display_errors', 1);

    // Put Stripe in test mode
    $config['stripe']['secret'] = $config['stripe']['test_secret'];
    $config['stripe']['pub'] = $config['stripe']['test_pub'];
}

// Initialize the database
\TinyDb\Db::set($config['database']['type'] . '://' . $config['database']['username'] . ':' . $config['database']['password'] . '@' .
                $config['database']['host'] . '/' . $config['database']['db']);

// Set some defines
define('WEB_DIR', dirname(__FILE__));
define('WEB_URI', $config['app']['path']);

define('APP_URI', \CuteControllers\Router::get_app_uri());

define('ASSETS_DIR', WEB_DIR . '/assets');
define('ASSETS_URI', WEB_URI . '/assets');

define('UPLOADS_DIR', WEB_DIR . '/uploads');
define('UPLOADS_URI', WEB_URI . '/uploads');

define('INCLUDES_DIR', WEB_DIR . '/Includes');
define('INCLUDES_URI', WEB_URI . '/Includes');

define('TEMPLATE_DIR', INCLUDES_DIR . '/StudentRND/My/Templates');
define('TEMPLATE_URL', INCLUDES_URI . '/StudentRND/My/Templates');

set_include_path(INCLUDES_DIR . PATH_SEPARATOR . get_include_path());

// Start routing
try {
    \CuteControllers\Router::start('Includes/StudentRND/My/Controllers');
} catch (\CuteControllers\HttpError $err) {
    if ($err->getCode() == 401) {
        \CuteControllers\Router::redirect('/login');
    } else {
        Header("Status: " . $err->getCode() . " " . $err->getMessage());
    }
} catch (\Exception $ex) {
    echo "Error: $ex";
}
