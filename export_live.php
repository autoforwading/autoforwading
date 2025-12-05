<?php
// LIVE DATABASE CONNECTION
include('inc/conn.php');

$db->set_charset("utf8mb4");

// get all tables
$tables = [];
$res = $db->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

$export = [];

foreach ($tables as $table) {
    $rows = [];
    $r = $db->query("SELECT * FROM `$table`");
    while ($row = $r->fetch_assoc()) {
        $rows[] = $row;
    }
    $export[$table] = $rows;
}

header("Content-Type: application/json");
echo json_encode($export);
