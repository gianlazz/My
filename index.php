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

if (isset($config['app']['https_only']) && $config['app']['https_only'] &&
    $_SERVER['HTTP_HOST'] == 'my.studentrnd.org' &&
    !(
     (isset($_SERVER['HTTP_HTTPS']) && $_SERVER['HTTP_HTTPS'] === 'on') ||
     (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    )) {
    \CuteControllers\Router::redirect("https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
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

define('TEMPLATE_DIR', ASSETS_DIR . '/tpl');
define('TEMPLATE_URL', ASSETS_URI . '/tpl');

set_include_path(INCLUDES_DIR . PATH_SEPARATOR . get_include_path());

// Include Stripe
require_once(INCLUDES_DIR . '/Stripe/Stripe.php');
Stripe::setApiKey($config['stripe']['secret']);

// Start routing
try {
    \CuteControllers\Router::start('Includes/StudentRND/My/Controllers');
//} catch (\CuteControllers\HttpError $err) {
//    if ($err->getCode() == 401) {
//        \CuteControllers\Router::redirect('/login');
//    } else {
//        Header("Status: " . $err->getCode() . " " . $err->getMessage());
//        $error = "Error: " . $err->getMessage();
//        include(TEMPLATE_DIR . '/Home/error.php');
//    }
} catch (\Exception $ex) {
    $error = "Error:<br />" . nl2br($ex);
    include(TEMPLATE_DIR . '/Home/error.php');
}
