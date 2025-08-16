<?php
session_start();
/* DATABASE CONFIGURATION */
define('DB_SERVER', 'gnpo-server.mysql.database.azure.com');
define('DB_USERNAME', 'gnpoRoot');
define('DB_PASSWORD', '6P8upHCSiHoHNxL');
define('DB_DATABASE', 'smartcret_web');



// define("BASE_URL", "https://www.smartcret.com/");

// function getDB()
// {
// 	$dbhost=DB_SERVER;
// 	$dbuser=DB_USERNAME;
// 	$dbpass=DB_PASSWORD;
// 	$dbname=DB_DATABASE;
// 	try {
// 	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
// 	$dbConnection->exec("set names utf8");
// 	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 	return $dbConnection;
//     }
//     catch (PDOException $e) {
//     echo 'Connection failed: ' . $e->getMessage();
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

$db = getDB();
?>