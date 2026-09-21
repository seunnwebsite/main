<?php
// -----------------------------------------------------------------------------
// LOAD .ENV
// -----------------------------------------------------------------------------

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// -----------------------------------------------------------------------------
// DATABASE CONFIG FROM ENV
// -----------------------------------------------------------------------------

$DB_SERVER   = $_ENV['DB_SERVER']   ?? '127.0.0.1';
$DB_USERNAME = $_ENV['DB_USERNAME'] ?? 'root';
$DB_PASSWORD = $_ENV['DB_PASSWORD'] ?? '';
$DB_NAME     = $_ENV['DB_NAME']     ?? 'test';
$DB_PORT     = (int)($_ENV['DB_PORT'] ?? 3306);

// -----------------------------------------------------------------------------
// CONNECT TO DATABASE
// -----------------------------------------------------------------------------

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_PORT);
    mysqli_set_charset($link, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// -----------------------------------------------------------------------------
// FETCH GLOBAL SETTINGS
// -----------------------------------------------------------------------------

// Fetch the first row from the settings table
$settings_query = "SELECT * FROM settings LIMIT 1";
$settings_result = mysqli_query($link, $settings_query);

if ($settings_result && mysqli_num_rows($settings_result) > 0) {
    $site = mysqli_fetch_assoc($settings_result);
    
    // Assign to variables for easy access
    $sitename                  = $site['sitename'];
    $siteurl                   = $site['siteurl'];
    $site_email                = $site['site_email'];
    $site_phone                = $site['site_phone'];
    $enable_email_verification = $site['enable_email_verification'];
    // You can access others similarly, or just use the $site array
} else {
    // Fallback if no settings exist in DB
    $sitename = "Fichain Capital";
}

// -----------------------------------------------------------------------------
// READY
// -----------------------------------------------------------------------------
?>