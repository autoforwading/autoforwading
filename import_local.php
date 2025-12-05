<?php
include('inc/conn.php');
$db->set_charset("utf8mb4");

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data) {
    die("Invalid data received");
}

foreach ($data as $table => $rows) {
    
    // 1. Clear table
    $db->query("TRUNCATE TABLE `$table`");

    // 2. Insert all rows again
    foreach ($rows as $row) {
        $columns = "`" . implode("`,`", array_keys($row)) . "`";
        $values  = "'" . implode("','", array_map([$db, 'real_escape_string'], $row)) . "'";

        $db->query("INSERT INTO `$table` ($columns) VALUES ($values)");
    }
}

echo "SUCCESS";
