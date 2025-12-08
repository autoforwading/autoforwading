<?php
// CORS FIX
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// LOCAL DB CONNECTION
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'autoforwading';

$db = new mysqli($host, $username, $password, $database);
if ($db->connect_error) { die("Local DB connection failed"); }

$db->set_charset("utf8mb4");

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data) { die("Invalid data received"); }

foreach ($data as $table => $rows) {
    $db->query("TRUNCATE TABLE `$table`");
    foreach ($rows as $row) {
        $columns = "`" . implode("`,`", array_keys($row)) . "`";
        $values  = "'" . implode("','", array_map([$db, 'real_escape_string'], $row)) . "'";
        $db->query("INSERT INTO `$table` ($columns) VALUES ($values)");
    }
}

echo "SUCCESS";