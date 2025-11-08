<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// require_once 'conn.php'; // your DB connection

// if (isset($_POST['vsl_num'])) {
//     $vsl_num = intval($_POST['vsl_num']);

//     // ✅ Debug log: write input to file
//     file_put_contents("debug_log.txt", "Received vsl_num: $vsl_num\n", FILE_APPEND);

//     // Run query
//     $query = "SELECT cargo_name FROM vessels_bl WHERE vsl_num = $vsl_num LIMIT 1";
//     $result = mysqli_query($db, $query);

//     if ($result && mysqli_num_rows($result) > 0) {
//         $row = mysqli_fetch_assoc($result);

//         // ✅ Log result to debug file
//         file_put_contents("debug_log.txt", "Returned cargo_name: {$row['cargo_name']}\n", FILE_APPEND);

//         echo $row['cargo_name'];
//     } else {
//         // ✅ Log no result found
//         file_put_contents("debug_log.txt", "No cargo_name found for vsl_num: $vsl_num\n", FILE_APPEND);
//         echo '';
//     }
// } else {
//     // ✅ Log error if POST value missing
//     file_put_contents("debug_log.txt", "POST 'vsl_num' not set\n", FILE_APPEND);
// }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'conn.php'; // adjust path

    $logFile = 'debug_log.txt';
    file_put_contents($logFile, "-----\n" . date("Y-m-d H:i:s") . " - Incoming Request to get_bl_field_value.php\n", FILE_APPEND);

    if (isset($_POST['vsl_num']) && isset($_POST['field'])) {
        $vsl_num = intval($_POST['vsl_num']);
        $field = $_POST['field'];

        file_put_contents($logFile, "Received vsl_num: $vsl_num\nRequested field: $field\n", FILE_APPEND);

        // ✅ Whitelist valid DB columns
        $allowedFields = ['cargo_name', 'shipper_name', 'shipper_address'];

        if (!in_array($field, $allowedFields)) {
            file_put_contents($logFile, "Rejected field: $field\n", FILE_APPEND);
            echo json_encode([$field => ""]);
            exit;
        }

        // ✅ Query the desired field
        $query = "SELECT `$field` FROM vessels_bl WHERE vsl_num = $vsl_num ORDER BY id DESC LIMIT 1";
        file_put_contents($logFile, "Running query: $query\n", FILE_APPEND);

        $result = mysqli_query($db, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $value = $row[$field];
            file_put_contents($logFile, "Success: $field = $value\n", FILE_APPEND);
            echo json_encode([$field => $value]);
        } else {
            file_put_contents($logFile, "No result for vsl_num: $vsl_num\n", FILE_APPEND);
            echo json_encode([$field => ""]);
        }
    } else {
        file_put_contents($logFile, "Missing POST: vsl_num or field\n", FILE_APPEND);
        echo json_encode(["error" => "Invalid request"]);
    }
?>

