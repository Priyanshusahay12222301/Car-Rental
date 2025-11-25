<?php 
// Railway-optimized database configuration
// Railway provides DATABASE_URL and individual environment variables

// Try Railway's DATABASE_URL first, then fall back to individual variables
$database_url = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');

if ($database_url) {
    // Parse Railway's DATABASE_URL format: mysql://user:pass@host:port/dbname
    $url = parse_url($database_url);
    define('DB_HOST', $url['host'] ?? 'localhost');
    define('DB_USER', $url['user'] ?? 'root');
    define('DB_PASS', $url['pass'] ?? '');
    define('DB_NAME', ltrim($url['path'] ?? '', '/') ?: 'railway');
    $db_port = $url['port'] ?? 3306;
} else {
    // Fallback to individual environment variables
    define('DB_HOST', $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost');
    define('DB_USER', $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '');
    define('DB_NAME', $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'railway');
    $db_port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;
}

// Establish database connection with Railway optimizations
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . $db_port . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => false, // Railway works better without persistent connections
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false // Railway MySQL SSL compatibility
    ];
    
    $dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Railway-friendly error logging
    error_log("Database connection error: " . $e->getMessage());
    
    // For development, show detailed errors
    if (($_ENV['RAILWAY_ENVIRONMENT'] ?? getenv('RAILWAY_ENVIRONMENT')) === 'development') {
        exit("Database Error: " . $e->getMessage());
    } else {
        exit("Database connection error. Please try again later.");
    }
}
?>