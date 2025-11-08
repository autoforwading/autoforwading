<?php
include('functions.php');

// Check if the enrollment year is set in the request
$vslYear = isset($_GET['vslYear']) ? $_GET['vslYear'] : '';

// Fetch data as an associative array
$data = []; 
for ($i=2018; $i <= date('Y'); $i++) { 
	// echo "$i</br>";
	$data[$i] = vslcountyr($i);
}

$data['vsl_count'] = rawcount("vessels","YEAR(STR_TO_DATE(rcv_date, '%d-%m-%Y')) = '$vslYear'");

// Return data as JSON
echo json_encode($data);
?>
