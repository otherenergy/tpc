<?php
/* DATABASE CONFIGURATION */
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_DATABASE', 'cms_openai');
define('DB_SERVER', 'gnpo-server.mysql.database.azure.com');
define('DB_USERNAME', 'gnpoRoot');
define('DB_PASSWORD', '6P8upHCSiHoHNxL');
define('DB_DATABASE', 'smartcret_web');
define("BASE_URL", "http://localhost/smartcret/"); // Eg. http://yourwebsite.com

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$conn->set_charset("utf8");

// function getDB() {
// 	$dbhost=DB_SERVER;
// 	$dbuser=DB_USERNAME;
// 	$dbpass=DB_PASSWORD;
// 	$dbname=DB_DATABASE;
// 	try {
// 		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
// 		$dbConnection->exec("set names utf8");
// 		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 		return $dbConnection;
// 	} catch (PDOException $e) {
// 		echo 'Connection failed: ' . $e->getMessage();
// 	}
// }

function getDB() {
    $dbhost=DB_SERVER;
    $dbuser=DB_USERNAME;
    $dbpass=DB_PASSWORD;
    $dbname=DB_DATABASE;
    try {
        // Nota el 'charset=utf8mb4' en el DSN
        $dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass); 
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
?>