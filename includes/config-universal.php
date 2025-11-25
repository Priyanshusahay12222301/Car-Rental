<?php 
// Universal database configuration for multiple hosting platforms
// Automatically detects hosting environment and configures accordingly

// Detect hosting platform and set database configuration
if (isset($_ENV['DATABASE_URL'])) {
    // Heroku, Render, Railway - uses DATABASE_URL
    $database_url = $_ENV['DATABASE_URL'];
    $url = parse_url($database_url);
    
    define('DB_HOST', $url['host'] ?? 'localhost');
    define('DB_USER', $url['user'] ?? 'root');
    define('DB_PASS', $url['pass'] ?? '');
    define('DB_NAME', ltrim($url['path'] ?? '', '/') ?: 'carrental');
    $db_port = $url['port'] ?? 3306;
    
    // Determine database type from scheme
    $db_type = $url['scheme'] ?? 'mysql';
    
} elseif (isset($_ENV['MYSQL_URL']) || getenv('MYSQL_URL')) {
    // JawsDB MySQL (Heroku addon)
    $mysql_url = $_ENV['MYSQL_URL'] ?? getenv('MYSQL_URL');
    $url = parse_url($mysql_url);
    
    define('DB_HOST', $url['host']);
    define('DB_USER', $url['user']);
    define('DB_PASS', $url['pass']);
    define('DB_NAME', ltrim($url['path'], '/'));
    $db_port = $url['port'] ?? 3306;
    $db_type = 'mysql';
    
} else {
    // Traditional hosting (InfinityFree, 000webhost, etc.)
    // Use individual environment variables or defaults
    define('DB_HOST', $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost');
    define('DB_USER', $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '');
    define('DB_NAME', $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'carrental');
    $db_port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;
    $db_type = 'mysql';
}

// Create appropriate DSN based on database type
if (isset($db_type) && $db_type === 'pgsql') {
    // PostgreSQL (Heroku, Render)
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . $db_port . ";dbname=" . DB_NAME;
} else {
    // MySQL (default)
    $dsn = "mysql:host=" . DB_HOST . ";port=" . $db_port . ";dbname=" . DB_NAME . ";charset=utf8mb4";
}

// Database connection options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_PERSISTENT => false,
];

// Add MySQL-specific options
if (!isset($db_type) || $db_type === 'mysql') {
    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES 'utf8mb4'";
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

// Establish database connection
try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Environment-aware error handling
    error_log("Database connection error: " . $e->getMessage());
    
    // Show detailed errors in development
    $is_development = (
        isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development'
    ) || (
        isset($_ENV['RAILWAY_ENVIRONMENT']) && $_ENV['RAILWAY_ENVIRONMENT'] === 'development'
    ) || (
        !isset($_ENV['DATABASE_URL']) && !isset($_ENV['MYSQL_URL'])
    );
    
    if ($is_development) {
        die("Database Error: " . $e->getMessage() . "<br>Host: " . DB_HOST . "<br>User: " . DB_USER . "<br>Database: " . DB_NAME);
    } else {
        die("Database connection error. Please try again later.");
    }
}
?>