<?php 
// DB credentials for Vercel deployment
// These will be set as environment variables in Vercel dashboard

$db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
$db_pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
$db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'carrental';

define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);

// Establish database connection with better error handling
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
} catch (PDOException $e) {
    // In production, log this error instead of displaying it
    error_log("Database connection error: " . $e->getMessage());
    
    // For development, you might want to see the error
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        exit("Error: " . $e->getMessage());
    } else {
        exit("Database connection error. Please try again later.");
    }
}
?>