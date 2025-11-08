<?php
	include_once('conn.php');

	// For phpword
	require_once 'vendor/autoload.php';
	use PhpOffice\PhpWord\PhpWord;
	use PhpOffice\PhpWord\SimpleType\Jc;

	// FOR DATABASE BACKUP
	include_once('Mysqldump.php');

	// for my data
	include_once('infostore.php');

	function isLeapYear($year){
		if(($year%4==0&&$year%100!=0)||($year%400==0)){return true;}else{return false;}
	}

	// sanitize filename
	function sanitize_filename($filename) {
	    return preg_replace('/[\/\\\\:*?"<>|]/', '_', $filename);
	}

	// Err message
	function alertMsg($msg = "Error Message Here", $type = "success"){
		$val = "<div class=\"alert alert-$type\" role=\"alert\">$msg</div>"; return $val;
	}

	// functions
	function allData($tableName = "users", $id = 1, $data = "name"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM $tableName WHERE id = $id ORDER BY id LIMIT 1";
		$run = mysqli_query($db, $sql); 
		if (mysqli_num_rows($run)>0) { $row = mysqli_fetch_assoc($run); $output = $row[$data]; }
		else{$output = "";} return "$output";
	}

	function allDataUpdated($tableName = "users", $fieldName = "id", $fieldValue = 1, $data = "name"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		$sql="SELECT*FROM $tableName WHERE $fieldName = $fieldValue ORDER BY id LIMIT 1";
		$run = mysqli_query($db, $sql); 
		if (mysqli_num_rows($run)>0){ $row = mysqli_fetch_assoc($run); $output = $row[$data]; }
		else{$output = "";} return "$output";
	}

	function lastData($tableName = "users", $data = "name"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT $data FROM $tableName ORDER BY id DESC LIMIT 1";
		$run = mysqli_query($db, $sql); $row = mysqli_fetch_assoc($run);
		$output = $row[$data]; return "$output";
	}

	function getdata($tableName = "users", $query = "id = 1", $data = "username"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		$sql="SELECT * FROM $tableName WHERE ".$query." ORDER BY id LIMIT 1";
		$run = mysqli_query($db, $sql); 
		if (mysqli_num_rows($run)>0){ $row = mysqli_fetch_assoc($run); return $row[$data];}
		else{return "Empty";}
	}

	// GET TOTAL CARGO QUANTITY FROM vessels_cargo
	function gettotal($tableName = "vessels_cargo", $fieldName = "vsl_num", $fieldValue = 111, $data = "quantity"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT $data FROM $tableName WHERE $fieldName = $fieldValue";
		$run = mysqli_query($db, $sql); $output = 0;
		if(mysqli_num_rows($run)>0){
			while($row=mysqli_fetch_assoc($run)){$output=$output+$row[$data];}
		} else{$output = "";} return "$output";
	}

	function rawcount($tableName = "users", $query = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 if (empty($query)) { $sql = "SELECT * FROM $tableName"; }
		else{$sql="SELECT * FROM $tableName WHERE ".$query." ";}
		$run = mysqli_query($db, $sql); $num = mysqli_num_rows($run); return (int)$num;
	}

	// delete the vessel folder
    function deleteIfEmpty($folderPath) {
        // Check if folder exists
        if (!is_dir($folderPath)) {return false;}

        // Scan the folder for files and filter out . and ..
        $files = array_diff(scandir($folderPath), ['.', '..']);

        if (empty($files)) {
            // Folder is empty, attempt to delete it
            if (rmdir($folderPath)) {return true;} 
            else {return false;}
        } else {return false;}
    }

	// download localfiles
	function downloadfile($file) {
		if (file_exists($file)) {
		    // Set headers to force download
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));

		    // Read the file and send it to the output buffer
		    if (readfile($file)) {unlink($file);exit;}
		    else{echo alertMsg("Couldn't Download", "danger");}
		}
		else {header("location: index.php");} 
	}

	function createzip($folderPath = "bl/", $name = "zip_downloaded"){
	    // Get all files in the folder (excluding subdirectories)
	    $files = glob($folderPath . '/*');

	    // Check if there are any files in the folder
	    if (!empty($files)) {
	        // Create a zip file
	        $zip = new ZipArchive();
	        $zipFileName = $folderPath . '/'.$name.'.zip';  // The name of the zip file

	        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
	            // Add files to the zip archive
	            foreach ($files as $file) {
	                if (is_file($file)) {
	                    $zip->addFile($file, basename($file));  // Add file to zip (basename is used to store file without path)
	                }
	            }
	            // Close the zip archive
	            $zip->close();

	            // Now, delete the files from the folder
	            foreach($files as $file){if(is_file($file)){unlink($file);}}

	            echo "Files have been zipped and deleted successfully.";
	        } else {echo "Failed to create the zip file.";}
	    } else {echo "No files found in the folder.";}
	}

	// create folder if not exists
	function createpath($path){
		if (!is_dir($path)) {
		    if (mkdir($path, 0755, true)) { echo "Folder '$path' created successfully!"; } 
		    else { echo "Failed to create the folder."; }
		} else {  echo "The folder already exists."; }
	}

	// Filter zero from numbers (Remove decimal part if all digits are zero)
	function filterzero($number) {
	    // Check if the number has a decimal point
	    if (strpos($number, '.') !== false) {
	        // Separate integer and decimal parts
	        $parts = explode('.', $number);
	        $integerPart = $parts[0];
	        $decimalPart = $parts[1];

	        // If the decimal part is all zeroes, return only the integer part
	        if ((int)$decimalPart === 0) {
	            return $integerPart;
	        }
	    }
	    // Return the number as is if no zero decimal part
	    return $number;
	}

	// Helper function to convert numbers less than 1000
    function convertToWords($num, $ones, $tens) {
        $str = "";
        if ($num > 99) {
            $str .= $ones[intval($num / 100)] . " hundred ";
            $num = $num % 100;
        }
        if ($num > 19) {
            $str .= $tens[intval($num / 10)] . " ";
            $num = $num % 10;
        }
        if ($num > 0) { $str .= $ones[$num] . " "; }
        return $str;
    }
	// Convert number to words
	function numberToWords($num) {
		$num = filterzero($num);
	    $ones = array(
	        "", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", 
	        "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", 
	        "seventeen", "eighteen", "nineteen"
	    );  
	    $tens = array(
	        "", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"
	    );
	    $hundreds = array( "hundred", "thousand", "lac", "crore" );
	    if ($num == 0) { return "zero"; }
	    
	    // Split integer and decimal parts
	    $parts = explode('.', (string)$num);
	    $integerPart = $parts[0];
	    $decimalPart = isset($parts[1]) ? $parts[1] : null;

	    // Convert integer part to words
	    $length = strlen($integerPart); 
	    $output = "";

	    // Process the crore place if applicable
	    if ($length > 7) {
	        $output .= convertToWords(intval(substr($integerPart, 0, -7)), $ones, $tens) . "crore ";
	        $integerPart = substr($integerPart, -7);
	        $length = strlen($integerPart);
	    }
	    // Process the lakh place if applicable
	    if ($length > 5) {
	        $output .= convertToWords(intval(substr($integerPart, 0, -5)), $ones, $tens) . "lac ";
	        $integerPart = substr($integerPart, -5);
	        $length = strlen($integerPart);
	    }
	    // Process the thousand place if applicable
	    if ($length > 3) {
	        $output .= convertToWords(intval(substr($integerPart, 0, -3)), $ones, $tens) . "thousand ";
	        $integerPart = substr($integerPart, -3);
	    }
	    // Process the rest (hundreds and below)
	    $output .= convertToWords(intval($integerPart), $ones, $tens);

	    // Convert decimal part (if any) to words
	    if ($decimalPart !== null) {
	        $output .= "point ";
	        for ($i = 0; $i < strlen($decimalPart); $i++) {
	            $digit = intval($decimalPart[$i]);
	            $output .= ($digit == 0 ? "zero" : $ones[$digit]) . " ";
	        }
	    }

	    // return ucfirst(trim($output)) . " only";
	    return ucfirst(trim($output));
	}

	// convert to indian number formats
	function formatIndianNumberNew($number) {
	    // Convert the number to a string if it is not already
	    $numberStr = filterzero((string)$number);
	    
	    // Check if the number contains a decimal point
	    if (strpos($numberStr, '.') !== false) {
	        // Split into integer and fractional parts
	        list($integerPart, $fractionalPart) = explode('.', $numberStr);
	    } else {
	        // No decimal point, only the integer part
	        $integerPart = $numberStr;
	        $fractionalPart = '';
	    }

	    // Get the length of the integer part
	    $length = strlen($integerPart);
	    
	    // If the length is less than or equal to 3, return the integer part as is
	    if ($length <= 3) {
	        return $integerPart . ($fractionalPart ? '.' . str_pad($fractionalPart, 3, '0') : '');
	    }
	    
	    // Split the integer part into parts
	    $lastThree = substr($integerPart, -3);
	    $remaining = substr($integerPart, 0, $length - 3);
	    
	    // Add commas to the remaining part of the integer
	    $remaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
	    
	    // Combine the remaining part with the last three digits
	    $formattedNumber = $remaining . ',' . $lastThree;

	    // Ensure the fractional part has 3 digits
	    $formattedFraction = $fractionalPart ? str_pad($fractionalPart, 3, '0') : '';

	    // Return the formatted number with the fractional part, if it exists
	    return $formattedNumber . ($formattedFraction ? '.' . $formattedFraction : '');
	}
	function formatIndianNumber($number) {
	    // Convert the number to a string if it is not already
	    $numberStr = filterzero((string)$number);
	    
	    // Check if the number contains a decimal point
	    if (strpos($numberStr, '.') !== false) {
	        // Split into integer and fractional parts
	        list($integerPart, $fractionalPart) = explode('.', $numberStr);
	    } else {
	        // No decimal point, only the integer part
	        $integerPart = $numberStr;
	        $fractionalPart = '';
	    }

	    // Get the length of the integer part
	    $length = strlen($integerPart);
	    
	    // If the length is less than or equal to 3, return the integer part as is
	    if ($length <= 3) {
	        return $integerPart . ($fractionalPart ? '.' . $fractionalPart : '');
	    }
	    
	    // Split the integer part into parts
	    $lastThree = substr($integerPart, -3);
	    $remaining = substr($integerPart, 0, $length - 3);
	    
	    // Add commas to the remaining part of the integer
	    $remaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
	    
	    // Combine the remaining part with the last three digits
	    $formattedNumber = $remaining . ',' . $lastThree;

	    // Return the formatted number with the fractional part, if it exists
	    return $formattedNumber . ($fractionalPart ? '.' . $fractionalPart : '');
	}

	function formatInternationalNumber($number, $decimalPlaces = 2) {
	    // Remove leading/trailing spaces and filter custom zero function
	    $numberStr = filterzero((string)$number);

	    // Format using number_format: (number, decimal_places, decimal_separator, thousand_separator)
	    $formatted = number_format((float)$numberStr, $decimalPlaces, '.', ',');

	    return $formatted;
	}

	// RESTORE DB
	function restoreMysqlDB($filePath){ 
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = ''; $error = '';
		if (file_exists($filePath)) {
			$lines = file($filePath);
			foreach ($lines as $line) {
				// Ignoring comments from the SQL script
				if (substr($line, 0, 2) == '--' || $line == '') { continue; }
				$sql .= $line;
				if (substr(trim($line), - 1, 1) == ';') {
					$result = mysqli_query($db, $sql);
					if (! $result) { $error .= mysqli_error($db) . "\n"; } $sql = '';
				}
			} // end foreach
			if ($error) { $response = array( "type" => "error", "message" => $error ); } 
			else{$response = array("type"=>"success","message"=>"Restored Successfully.");}
		} // end if file exists
		return $response;
	}

	// convert db time into useable time
	function dbtime($time = "2024-09-26", $format = "d-m-Y"){
		$response = date($format, strtotime($time)); return $response;
	}
	// convert db time into useable time
	function dbtimefotmat($from = "d/m/Y", $time = "10/03/2025", $format = "d-m-Y"){
		$date = DateTime::createFromFormat($from, $time);
		// Now, format it to the desired output
		$time = $date->format($format); return $time;
	}

	// get pagename
	function pagename(){
		$pagename = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		return $pagename;
	}
	// get pagename
	function page(){
		$pagename = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		// from index.php to index
		return substr($pagename, 0,-4);
	}
	// get urlval
	function pageurl(){
		$urlval = "";
		if (strpos($_SERVER['REQUEST_URI'], "?") !== false) {
			$urlval = substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["REQUEST_URI"],"?"));
		} return $urlval;
	}

	// get total vessel number in a specific year
	function vslcountyr($year = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $id = $_SESSION['id']; $companyid = allData('users', $id, 'companyid');
		$query = "YEAR(STR_TO_DATE(rcv_date, '%d-%m-%Y')) = '$year'";
		if (empty($year)) { $sql = "SELECT * FROM $vessels WHERE companyid = '$companyid' "; }
		else{$sql="SELECT * FROM vessels WHERE companyid = '$companyid' AND ".$query." ";}
		$run = mysqli_query($db, $sql); $num = mysqli_num_rows($run); return (int)$num;
	}

	// check if these files exist in the folder
	function checkfileexist($folder, $requiredFiles) {
		$result = true;
		if (is_dir($folder)) {
		    // Get all files in the folder (ignores directories)
		    $allFiles = array_filter(scandir($folder), function ($file) use ($folder) {
		        return is_file($folder . DIRECTORY_SEPARATOR . $file);
		    });

		    // Extract just the base names (filenames without extension)
		    $baseNames = array_map(function($file) {
		        return pathinfo($file, PATHINFO_FILENAME);
		    }, $allFiles);

		    // Check if every required file is in the list of base names
		    foreach ($requiredFiles as $required) {
		        if (!in_array($required, $baseNames)) {
		            $result = false; // At least one required file is missing
		        }
		    }
		}else{$result = false;}
	    return $result; // All required files found
	}

	// Step 1: Create a function to count rows for a given year
	// function vslcountyr($year) {
	//     // Step 2: Establish a database connection
	//     GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];


	//     // Step 3: Create a SQL query to get the count of rows for the input year
	//     // Handle both %Y-%m-%d and %d-%m-%Y date formats using STR_TO_DATE
	//     $sql = "
	//         SELECT COUNT(*) AS row_count 
	//         FROM vessels 
	//         WHERE 
	//             (YEAR(STR_TO_DATE(arrived, '%Y-%m-%d')) = ?)
	//             OR
	//             (YEAR(STR_TO_DATE(arrived, '%d-%m-%Y')) = ?)
	//     ";

	//     // Prepare the statement to avoid SQL injection
	//     if ($stmt = $db->prepare($sql)) {
	//         // Bind the input parameter to the prepared statement (both places for the year)
	//         $stmt->bind_param("ii", $year, $year); // "i" for integers
	        
	//         // Step 4: Execute the statement
	//         $stmt->execute();
	        
	//         // Step 5: Get the result
	//         $stmt->bind_result($row_count);
	//         $stmt->fetch();
	        
	//         // Debugging: Echo the query and row count
	//         echo "Query executed for year $year: $sql\n";
	//         echo "Row count: $row_count\n";
	        
	//         // Step 6: Close the prepared statement and connection
	//         $stmt->close();
	//         $db->close();
	        
	//         // Return the row count
	//         return $row_count;
	//     } else {
	//         // In case the query preparation fails
	//         echo "Error in preparing the query: " . $db->error;
	//         $db->close();
	//         return 0;
	//     }
	// }

	// // GET TOTAL CARGO QUANTITY FROM vessels_cargo
	// function ttlcargoqty($msl_num = 111, $type = "total"){
	// 	GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
	 // $sql = "SELECT quantity FROM vessels_cargo WHERE msl_num = '$msl_num' ";
	// 	$run = mysqli_query($db, $sql); $output = 0; 
	// 	if(mysqli_num_rows($run)>0){
	// 		if ($type == "ctg") {
	// 			while($row=mysqli_fetch_assoc($run)){$output=$output+$row['quantity'];}
	// 		}elseif ($type == "retention") {
	// 			$output = allDataUpdated('vessels', 'msl_num', $msl_num, 'retention_qty');
	// 		}else{
	// 			while($row=mysqli_fetch_assoc($run)){ $output=$output+$row['quantity']; }
	// 			$output = $output + (float)allDataUpdated('vessels', 'msl_num', $msl_num, 'retention_qty');
	// 		}
	// 	}else{$output = "";} 
	// 	return $output;
	// }

	// function forwadingcrgodesc($vsl_num){
	// 	GLOBAL $db,$my;
	// 	// Query to sum cargo quantities grouped by cargokeyId and cargo_name
	// 	$sql = "SELECT cargo_name, SUM(cargo_qty) AS total_qty FROM vessels_bl WHERE vsl_num = '$vsl_num' GROUP BY cargokeyId, cargo_name"; $result = $db->query($sql);

	// 	// Array to store results
	// 	$cargoData = [];

	// 	// Fetch results and store them
	// 	while ($row = $result->fetch_assoc()) {$cargoData[] = $row;}

	// 	// Initialize variables for total sum and output parts
	// 	$totalSum = 0; $outputParts = [];

	// 	// Calculate total sum of all cargo quantities and prepare the output parts
	// 	foreach ($cargoData as $cargo) {
	// 		$totalSum += $cargo['total_qty'];  // Adding the total quantity
	// 		// Format: cargo_name (total_qty MT)
	// 		$outputParts[] = $cargo['cargo_name'] . " (" . $cargo['total_qty'] . " MT)";
	// 	}

	// 	// Build final output
	// 	if (count($cargoData) > 1) {
	// 		// For multiple cargo types, show total sum followed by cargo names in parentheses
	// 		$finalOutput = $totalSum . " MT " . implode(" And ", $outputParts);
	// 	} else {
	// 		// For a single cargo type, just show its total quantity without parentheses
	// 		$finalOutput = $totalSum . " MT " . $cargoData[0]['cargo_name'];
	// 	}
	// 	return $finalOutput;
	// }

	function exportlogs($vsl_num = 124, $filename = "default"){
		GLOBAL $db, $my;
		$run = mysqli_query($db, "
			INSERT INTO export_logs(companyid, userid, vsl_num, exported, timedate)
			VALUES({$my['companyid']}, {$my['id']}, {$vsl_num}, '{$filename}', NOW())
		"); if(!$run){echo "Failed</br>".mysqli_error($db);}
	}

	// task for autoforwading employees
	function exportlog_companies(){
		GLOBAL $db,$my; $myid = $my['id']; $mycompanyid = $my['companyid'];
		$sql = "SELECT * FROM companies ";
		$run = mysqli_query($db, $sql); while ($row = mysqli_fetch_assoc($run)) {
			$companyid = $row['id']; $company_name = $row['companyname']; $com_email = $row['email'];
			$sqlvsl = "SELECT * FROM vessels WHERE workstatus = 'notdone' AND companyid = '$companyid' ";
			$runvsl = mysqli_query($db, $sqlvsl); $pending = mysqli_num_rows($runvsl);
			// if (!$pending) {continue;}

			echo "
				<tr>
					<th scope=\"row\">".$companyid."</th>
					<td><a href=\"task.php?page=company&&companyid=$companyid\">".$company_name."</a></td>
					<td>".$com_email."</td>
					<td>$pending</td>
				</tr>
			";
		}
	}


	function forwadingcrgodesc($vsl_num, $type = "full") {
	    GLOBAL $db, $my;

	    // Query to sum cargo quantities grouped by cargokeyId and cargo_name
	    $sql = "SELECT cargo_name, SUM(cargo_qty) AS total_qty 
	            FROM vessels_bl 
	            WHERE vsl_num = '$vsl_num' 
	            GROUP BY cargokeyId, cargo_name";
	    $result = $db->query($sql);

	    // Array to store results
	    $cargoData = [];

	    // Fetch results and store them
	    while ($row = $result->fetch_assoc()) {
	        $cargoData[] = $row;
	    }

	    // If no data found, return empty string or default
	    if (empty($cargoData)) {
	        return strtoupper("");  // Or return "" or NULL
	    }

	    // Initialize variable for output parts
	    $outputParts = [];

	    if ($type === "onlyname") {
	        foreach ($cargoData as $cargo) {
	            $outputParts[] = $cargo['cargo_name'];
	        }

	        // Build the name-only string
	        if (count($cargoData) > 1) {
	            $finalOutput = implode(" and ", $outputParts);
	        } else {
	            $finalOutput = $outputParts[0];
	        }
	    } else {
	        $totalSum = 0;
	        foreach ($cargoData as $cargo) {
	            $totalSum += $cargo['total_qty'];
	            $outputParts[] = $cargo['cargo_name'] . " (" . $cargo['total_qty'] . " MT)";
	        }

	        if (count($cargoData) > 1) {
	            $finalOutput = formatIndianNumber($totalSum) . " MT " . implode(" And ", $outputParts);
	        } else {
	            $finalOutput = formatIndianNumber($totalSum) . " MT " . $cargoData[0]['cargo_name'];
	        }
	    }

	    return strtoupper($finalOutput);
	}


	// GET TOTAL CARGO QUANTITY FROM vessels_cargo
	function ttlcargoqty($vsl_num = 111, $type = "total"){
		GLOBAL $db,$my, $company; $myid = $my['id']; $companyid = $my['companyid'];

		$run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
		$run2 = mysqli_query($db, "SELECT quantity FROM vessels_cargo WHERE vsl_num = '$vsl_num' "); 
		$output = 0; 
		if(mysqli_num_rows($run)>0){
			while($row=mysqli_fetch_assoc($run)){
				if ($type == "ctg") {
					if ($row['desc_port'] == $company['port']) {
						$output = $output+$row['cargo_qty'];
					}
				}elseif ($type == "retention") {
					if ($row['desc_port'] != $company['port']) {
						$output = $output+$row['cargo_qty'];
					}
				}else{$output = $output+$row['cargo_qty'];}
			}
		}elseif (mysqli_num_rows($run2)>0) {
			// echo "<h1>It is working</h1>";
			while($row2=mysqli_fetch_assoc($run2)){
				// $output=$output+$row2['quantity'];
				if ($type == "ctg") {
					while($row2=mysqli_fetch_assoc($run2)){$output=$output+$row2['quantity'];}
				}elseif ($type == "retention") {
					$output = allDataUpdated('vessels', 'id', $vsl_num, 'retention_qty');
				}else{
					while($row2=mysqli_fetch_assoc($run2)){ $output=$output+$row2['quantity']; }
					$output = $output + (float)allDataUpdated('vessels', 'id', $vsl_num, 'retention_qty');
				}
			}
		}
		else{$output = "";} return $output;
	}

	// vessel deperture date
	function deperture_date($vsl_num = 205, $data = "deperture_date"){
		GLOBAL $db;
		$row3=mysqli_fetch_assoc(mysqli_query($db,"SELECT * 
		FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
	    $dep_date=$row3['issue_date'];  $last_portid = $row3['load_port'];
	    $dep_day = dbtime($dep_date, "d"); $dep_month = dbtime($dep_date, "m"); $dep_year = dbtime($dep_date, "y"); 
	    if ($data = "") {
	    	$value = $dep_month."/".$dep_day."/".$dep_year;
	    } 
		elseif($data == "day"){ $value = $dep_day;}
		elseif($data == "month"){ $value = $dep_month;}
		elseif($data == "year"){ $value = $dep_year;}
	    else{$value = $dep_month."/".$dep_day."/".$dep_year;}
	    return $value;
	}

	// vessel last port
	function deperture_info($vsl_num = 205, $data = "deperture_date"){
		GLOBAL $db;
		$row3=mysqli_fetch_assoc(mysqli_query($db,"SELECT * 
		FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
		if ($data == "deperture_date") {
			$dep_date=$row3['issue_date'];  $last_portid = $row3['load_port'];
		    $dep_day = dbtime($dep_date, "d"); $dep_month = dbtime($dep_date, "m"); $dep_year = dbtime($dep_date, "y"); 
		    $value = $dep_month."/".$dep_day."/".$dep_year;
		}
		elseif ($data == "last_portid") {
			$value = $row3['load_port'];
		}elseif($data == "last_portcode"){
			$value = allData('loadport', $row3['load_port'], 'port_code');
		}elseif($data == "last_port"){
			$value = allData('loadport', $row3['load_port'], 'port_name');
		}else{$value = "";}
	    return $value;
	}

	// GET TOTAL CARGO QUANTITY FROM vessels_cargo
	function ttlcargodescription($vsl_num = 111, $type = "total"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];

		$run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
		$run2 = mysqli_query($db, "SELECT quantity, cargo_bl_name FROM vessels_cargo WHERE vsl_num = '$vsl_num' "); 
		$total_qty = 0; 
		if(mysqli_num_rows($run)>0){
			$cargonamefusion = "";
			while($row=mysqli_fetch_assoc($run)){
				if ($type == "ctg") {
					if ($row['desc_port'] == 65) {
						$cargonamefusion = $cargonamefusion.$row['cargo_bl_name'];
						$total_qty = $total_qty+$row['cargo_qty'];
					}
				}elseif ($type == "retention") {
					if ($row['desc_port'] != 65) {
						$total_qty = $total_qty+$row['cargo_qty'];
					}
				}else{$total_qty = $total_qty+$row['cargo_qty'];}
			}
			$output = $total_qty;
		}elseif (mysqli_num_rows($run2)>0) {
			// echo "<h1>It is working</h1>";
			while($row2=mysqli_fetch_assoc($run2)){
				// $output=$output+$row2['quantity'];
				if ($type == "ctg") {
					while($row2=mysqli_fetch_assoc($run2)){$total_qty=$total_qty+$row2['quantity'];}
				}elseif ($type == "retention") {
					$total_qty = allDataUpdated('vessels', 'id', $vsl_num, 'retention_qty');
				}else{
					while($row2=mysqli_fetch_assoc($run2)){ $total_qty=$total_qty+$row2['quantity']; }
					$total_qty = $total_qty + (float)allDataUpdated('vessels', 'id', $vsl_num, 'retention_qty');
				}
			}
			$output = $total_qty;
		}
		else{$output = "";} return $output;
	}

	// COUNT PDF PAGES
	function countPages($path) { 
		$pdf=file_get_contents($path);$number=preg_match_all("/\/Page\W/",$pdf,$dummy); 
		return $number; 
	}

	// checkLogin
	function checkLogin(){
		// force user to logout if status is offline
		$myid = $_SESSION['id']; $status = allData('users', $myid, 'status');
		if ($status == 'offline') {
			session_destroy(); 
			unset($_SESSION['companyid']);
			unset($_SESSION['email']); 
			unset($_SESSION['id']);
			header('location: login.php');
		}
		// redirect to login page if session is not set
		if(!isset($_SESSION['id'])){header("location: login.php");}
	}

	// select options
	function selectOptions($tablename = "users", $fieldName = "name", $thiscompany=""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thiscompany == "all") {$run = mysqli_query($db, "SELECT * FROM $tablename ");}
		else{$run = mysqli_query($db, "SELECT * FROM $tablename WHERE companyid = '$companyid' ");}
		
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $value = $row[$fieldName];
			echo"<option value=\"$id\">$value</option>";
		}
	}

	function dayCount($from=0, $to = 0){
		$begin = strtotime($from); $end = strtotime($to);
		$value = round(($end-$begin) / (60 * 60 * 24) + 1);
		return $value;
	}

	function exist($tableName = "users", $query = "id = 1"){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		$sql="SELECT * FROM $tableName WHERE ".$query." ORDER BY id LIMIT 1";
		$run = mysqli_query($db, $sql); if (mysqli_num_rows($run)>0){ return true; }
		else{return false;}
	}



	function percentage($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $msl = $name = $arrived = $rcv_date = $com_date = $sailing_date = $stevedore = $representative = $rotation = $anchor = $custom_survey = $consignee_survey = $received_by = $sailed_by = $outer_qty = $kutubdia_qty = $retention_qty = $custom_load = $custom_light = $consignee_load = $consignee_light = $supplier_load = $supplier_light = $pni_load = $pni_light = $chattrer_load = $chattrer_light = $owner_load = $owner_light = 0; 

		$run = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
		$row = mysqli_fetch_assoc($run); 

		$outer = floatval($row['outer_qty']); $kutubdia = floatval($row['kutubdia_qty']);
		$retention = floatval($row['retention_qty']);

		$total_qty = floatval(ttlcargoqty($vsl_num)); // total vessels cargo qty

		$ttlctgqty = $outer + $kutubdia; $ttlqtyplused = $outer + $kutubdia + $retention;


		// static item count start
		if(!empty($row['msl_num'])){$msl = 1;}
		if(!empty($row['vessel_name'])){$name = 1;}
		if(!empty($row['arrived'])){$arrived=1;}
		if(!empty($row['rcv_date'])){$rcv_date=1;}
		if(!empty($row['com_date'])){$com_date=1;}
		if(!empty($row['sailing_date'])){$sailing_date=1;}
		if(!empty($row['outer_qty']) || $total_qty == $ttlctgqty){$outer_qty=1;}
		if($row['stevedore'] != 0){$stevedore=1;} 
		if(!empty($row['rotation'])){$rotation=1;}
		if($row['representative'] != 0){$representative=1;}
		if(!empty($row['anchor'])){$anchor=1;}
		if($row['received_by']!=0){$received_by=1;} 
		if($row['sailed_by']!=0){$sailed_by=1;}
		if($row['survey_custom']!=0){$custom_survey=1;}
		if($row['survey_consignee']!=0){$consignee_survey=1;}

		// count surveyors and survey companies
		if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose = 'Load Draft' AND surveyor != 0 ")==1) { $custom_load = 1;}
		if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_custom' AND survey_purpose != 'Load Draft' AND surveyor != 0 ")==1) { $custom_light = 1;}
		if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose = 'Load Draft' AND surveyor != 0 ")==1){ $consignee_load = 1;}
		if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_consignee' AND survey_purpose != 'Load Draft' AND surveyor != 0 ")==1){ $consignee_light = 1;}
		// static item count end

		// total static item count]
		$itemcount = 19;

		// In "if(`$ttlqtyplused` < `$total_qty`)" the `` is needed for folating precision check

		// checks if outer qty is smaller then total cargo qty(from vessels_cargo).
		if(strval($ttlqtyplused) != strval($total_qty)){
			$con1 = $outer + $kutubdia; $con2 = $outer + $retention;
			if ($con1 < $total_qty) { $itemcount++; 
				if(!empty($row['retention_qty'])&&$con1==$total_qty){$retention_qty=1;}
			} if ($con2 < $total_qty) { $itemcount++; 
				if(!empty($row['kutubdia_qty'])&&$con2==$total_qty){$kutubdia_qty=1;}
			}
		}


		if ($row['survey_supplier'] != 0) {
			$itemcount = $itemcount + 2;
			if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND survey_purpose = 'Load Draft' AND surveyor != 0 ")==1){
				$supplier_load = 1;
			}if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_supplier' AND survey_purpose != 'Load Draft' AND surveyor != 0 ")==1){
				$supplier_light = 1;
			}
		}if ($row['survey_pni'] != 0) {
			$itemcount = $itemcount + 2;
			if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND survey_purpose = 'Load Draft' AND surveyor != 0 ")==1){
				$pni_load = 1;
			}if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_pni' AND survey_purpose != 'Load Draft' AND surveyor != 0 ")==1){
				$pni_light = 1;
			}
		}if ($row['survey_chattrer'] != 0) {
			$itemcount = $itemcount + 2;
			if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND survey_purpose = 'Load Draft' AND surveyor != 0 ")==1){
				$chattrer_load = 1;
			}if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_chattrer' AND survey_purpose != 'Load Draft' AND surveyor != 0 ")==1){
				$chattrer_light = 1;
			}
		}if ($row['survey_owner'] != 0) {
			$itemcount = $itemcount + 2;
			if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND survey_purpose = 'Load Draft' AND surveyor != 0 ")==1){
				$owner_load = 1;
			}if (exist("vessels_surveyor","vsl_num = ".$vsl_num." AND survey_party = 'survey_owner' AND survey_purpose != 'Load Draft' AND surveyor != 0 ")==1){
				$owner_light = 1;
			}
		}

		// // count cnf from inporter
		// $run_cnf = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
		// while ($row_cnf = mysqli_fetch_assoc($run_cnf)) {
		// 	$checkCnf = $row_cnf['cnf']; $itemcount++; if ($checkCnf != 0) { $cnf_count++; }
		// }
		

		$filled = $msl + $name + $arrived + $rcv_date + $com_date + $sailing_date + $stevedore + $representative + $rotation + $anchor + $custom_survey + $consignee_survey + $received_by + $sailed_by + $outer_qty + $kutubdia_qty + $retention_qty + $custom_load + $custom_light + $consignee_load + $consignee_light + $supplier_load + $supplier_light + $pni_load + $pni_light + $chattrer_load + $chattrer_light + $owner_load + $owner_light;
		// $filled = $percentage;

		// 15 items, so $division = 15 / 100 = 0.15; $filled / 0.15 = result
		$division = $itemcount / 100;
		$percentage = round($filled / $division);

		return $percentage;
	}


	// arrival date of generalsegment
	function gmarrivaldate($vsl_num){
		// make generalsegment xml
	    $deperture_date = dbtime(deperture_date($vsl_num), "d-m-Y");

		// make arrival date
		// condition 1: 6 Month ahead from current date.
		$currentDate = new DateTime(); 
		$currentDate->modify('+6 months');
		$arrival_date = $currentDate->format('d-m-Y'); // use
		

		// condition 2: minimum 7 day ahead from deperture date
		$deperture_day = dbtime($deperture_date, "d");
		$deperture_month = dbtime($deperture_date, "m");
		$deperture_year = dbtime($deperture_date, "Y");
		$arrival_day = dbtime($currentDate->format('m-d-y'), "d");

		$mdfyarrival_date = $arrival_day."-".$deperture_month."-".$deperture_year;
		$mdfycurrent_date = date("d")."-".$deperture_month."-".$deperture_year;

		
		// ✅ Step 2: Convert to DateTime objects
		$d1 = DateTime::createFromFormat('d-m-Y', $deperture_date);
		$d2 = DateTime::createFromFormat('d-m-Y', $mdfyarrival_date);

		// ✅ Step 3: Calculate day difference (absolute)
		$diff = $d1->diff($d2)->days; // always positive number

		// ✅ Step 4: If arrival is within 8 days of departure, shift arrival to +8 days
		if ($diff < 8 || $d1 > $d2) {
		    // make sure arrival is at least 8 days after departure
		    $d2 = clone $d1;          // copy departure date
		    $d2->modify('+8 days');   // move arrival 8 days ahead
		}

		$arrival_date = $d2->format('d')."-".$currentDate->format('m')."-".$currentDate->format('Y');

		$today = DateTime::createFromFormat('d-m-Y', $currentDate->format('d-m-Y'));
		$d4 = DateTime::createFromFormat('d-m-Y', $arrival_date);
		if ($diff < 8 || $d4 > $today) {
			// make sure arrival is at least 8 days after departure
		    $d4 = clone $today;          // copy departure date
		    $d4->modify('+8 days');   // move arrival 8 days ahead
		}

		$arrival_date = $currentDate->format('m')."/".$d4->format('d')."/".$currentDate->format('Y');
		return $arrival_date;
	}



	function vesselsCnfTag($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];

		$run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
		$cnfName = array();
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $importer = $row['importer'];$cnf = $row['cnf']; 
			$importer = allData('bins', $importer, 'name'); if ($cnf != 0) {
				$cnfNm = allData('cnf', $cnf, 'name');
				// Check if the value exists in the array
				if (!in_array($cnfNm, $cnfName)) {
				    // If not, add the value to the array
				    $cnfName[] = $cnfNm;
				}
			}
		}
		// extract and convert array values to string
		$output = implode(", ", $cnfName) . ",";
		return $output;
	}

	// 
	function vesselsImporterTag($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
		$importerName = array(); while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $importerId = $row['importer']; 
			if ($importerId != 0) { $importerNm = allData('bins', $importerId, 'name');
				// Check if the value exists in the array
				if (!in_array($importerNm, $importerName)) { $importerName[] = $importerNm; }
			}else{}
		} $output = implode(", ", $importerName) . ","; return $output;
	}

	// 
	function vesselsCargoTag($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $run = mysqli_query($db, "SELECT * FROM vessels_cargo WHERE vsl_num = '$vsl_num' ");
		$cargoKeyTag = array(); $loadPortTag = array(); $portCodeTag = array(); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $cargoKeyId = $row['cargo_key']; $loadPortId = $row['loadport'];
			if ($cargoKeyId != 0 && $loadPortId != 0) { 
				$cargoKeyNm = allData('cargokeys', $cargoKeyId, 'name');
				$loadPortNm = allData('loadport', $loadPortId, 'port_name');
				$portCodeNm = allData('loadport', $loadPortId, 'port_code');
				// Check if the value exists in the array
				if (!in_array($cargoKeyNm, $cargoKeyTag)) { $cargoKeyTag[] = $cargoKeyNm; }
				if (!in_array($loadPortNm, $loadPortTag)) { $loadPortTag[] = $loadPortNm; }
				if (!in_array($portCodeNm, $portCodeTag)) { $portCodeTag[] = $portCodeNm; }
			}else{}
		} $output = implode(", ", $cargoKeyTag) . "," . implode(", ", $loadPortTag) . "," . implode(", ", $portCodeTag) . ","; 
		return $output;
	}

	function exportblxml($id = 1){
		GLOBAL $db;
		$vsl_num = allData('vessels_bl', $id, 'vsl_num');
		$vessels = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	    $ship_perticular = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");

	    $vessel_row = mysqli_fetch_assoc($vessels);
	    // Prepare dynamic variables
	    $msl_num = $vessel_row['msl_num'];
	    $vessel = $vessel_row['vessel_name'];

		
		$deperture_date = deperture_info($vsl_num, "deperture_date");

		$vessel_details_row = mysqli_fetch_assoc($ship_perticular);
		if (empty($vessel_details_row['vsl_nrt'])) {$packages_codes = "VR";}
	    else{$packages_codes = $vessel_details_row['packages_codes'];}


		$vessels_bl = mysqli_query($db, "SELECT * FROM vessels_bl WHERE id = '$id' ");

	    // Loop through BOL data
	    $bl_row = mysqli_fetch_assoc($vessels_bl);
        // $line_num = $line_num + 1;
        $line_num = $bl_row['line_num'];
        $bl_num = $bl_row['bl_num'];
        $cargo_name = $bl_row['cargo_name'];
        $cargo_qty = $bl_row['cargo_qty'];
        $qty = $cargo_qty * 1000;
        $shipper_name = $bl_row['shipper_name'];
        $shipper_address = $bl_row['shipper_address'];
        $importerid = $bl_row['receiver_name'];
        $importer_bin = allData('bins', $importerid, 'bin');
        $bankid = $bl_row['bank_name'];
        $bank_bin = allData('bins', $bankid, 'bin');
        $load_portid = $bl_row['load_port'];
        $load_portcode = allData('loadport', $load_portid, 'port_code');

        $desc_portId = $bl_row['desc_port'];
        $desc_portcode = allData('loadport', $desc_portId, 'port_code');

        // if ($desc_portcode != "BDCGP") {continue;}
        // $line_num = $line_num + 1;

        // Start XML creation
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="no"?><AsycudaWorld_Manifest/>');
        $xml->addAttribute('id', '27977244');

        // Identification Segment
        $identification_segment = $xml->addChild('Identification_segment');
        $identification_segment->addChild("Voyage_number", "MSL/$msl_num/".date('Y'));
        $identification_segment->addChild('Date_of_departure', $deperture_date);
        $identification_segment->addChild('Bol_reference', $bl_num);

        // Customs Office Segment
        $customs_office_segment = $identification_segment->addChild('Customs_office_segment');
        $customs_office_segment->addChild('Code', '301');
        // $customs_office_segment->addChild('Name', 'Custom House, Chattogram');

        // BOL specific Segment
        $Bol_specific_segment = $xml->addChild('Bol_specific_segment');

        // Add the BOL data to the XML here
        $Bol_specific_segment->addChild('Line_number', $line_num);
        $Bol_specific_segment->addChild('Sub_line_number', '');
        $Bol_specific_segment->addChild('Status', 'HSE');
        $Bol_specific_segment->addChild('Previous_document_reference')->addChild('null');
        $Bol_specific_segment->addChild('Bol_Nature', '23');
        $Bol_specific_segment->addChild('Unique_carrier_reference')->addChild('null');
        $Bol_specific_segment->addChild('Total_number_of_containers', '0');
        $Bol_specific_segment->addChild('Total_gross_mass_manifested', $qty);
        $Bol_specific_segment->addChild('Volume_in_cubic_meters');
        $Bol_specific_segment->addChild('Number_of_sub_bols', '0');

        $Bol_type_segment = $Bol_specific_segment->addChild('Bol_type_segment');
        $Bol_type_segment->addChild('Code', 'HSB');

        $Exporter_segment = $Bol_specific_segment->addChild('Exporter_segment');
        $Exporter_segment->addChild('Code')->addChild('null');
        $Exporter_segment->addChild('Name', $shipper_name);
        $Exporter_segment->addChild('Address', $shipper_address);

        $Consignee_segment = $Bol_specific_segment->addChild('Consignee_segment');
        $Consignee_segment->addChild('Code', $bank_bin);

        $Notify_segment = $Bol_specific_segment->addChild('Notify_segment');
        $Notify_segment->addChild('Code', $importer_bin);

        $Place_of_loading_segment = $Bol_specific_segment->addChild('Place_of_loading_segment');
        $Place_of_loading_segment->addChild('Code', $load_portcode);

        $Place_of_unloading_segment = $Bol_specific_segment->addChild('Place_of_unloading_segment');
        $Place_of_unloading_segment->addChild('Code', 'BDCGP');

        $Packages_segment = $Bol_specific_segment->addChild('Packages_segment');
        $Packages_segment->addChild('Package_type_code', $packages_codes);
        $Packages_segment->addChild('Number_of_packages', '1');

        $Shipping_segment = $Bol_specific_segment->addChild('Shipping_segment');
        $Shipping_segment->addChild('Shipping_marks', '-');

        $Goods_segment = $Bol_specific_segment->addChild('Goods_segment');
        $Goods_segment->addChild('Goods_description', $cargo_name);

        // Add the SCI segment inside Goods_segment
        $SCI_segment = $Goods_segment->addChild('SCI');
        $SCI_segment->addChild('code')->addChild('null');
        $SCI_segment->addChild('description')->addChild('null');

        // Add the Freight_segment
        $Freight_segment = $Bol_specific_segment->addChild('Freight_segment');
        $Freight_segment->addChild('Value');
        $Currency = $Freight_segment->addChild('Currency');
        $Currency->addChild('null');

        // Add the Indicator_segment inside Freight_segment
        $Indicator_segment = $Freight_segment->addChild('Indicator_segment');
        $Indicator_segment->addChild('Code')->addChild('null');
        $Indicator_segment->addChild('Name')->addChild('null');

        // Add the Customs_segment
        $Customs_segment = $Bol_specific_segment->addChild('Customs_segment');
        $Customs_segment->addChild('Value');
        $Currency = $Customs_segment->addChild('Currency');
        $Currency->addChild('null');

        // Add the Transport_segment
        $Transport_segment = $Bol_specific_segment->addChild('Transport_segment');
        $Transport_segment->addChild('Value');
        $Currency = $Transport_segment->addChild('Currency');
        $Currency->addChild('null');

        // Add the Insurance_segment
        $Insurance_segment = $Bol_specific_segment->addChild('Insurance_segment');
        $Insurance_segment->addChild('Value');
        $Currency = $Insurance_segment->addChild('Currency');
        $Currency->addChild('null');

        // Add the Seals_segment
        $Seals_segment = $Bol_specific_segment->addChild('Seals_segment');
        $Seals_segment->addChild('Number_of_seals');
        $Marks_of_seals = $Seals_segment->addChild('Marks_of_seals');
        $Marks_of_seals->addChild('null');
        $Seals_segment->addChild('Sealing_party_code')->addChild('null');
        $Seals_segment->addChild('Sealing_party_name')->addChild('null');

        // Add the Information_segment
        $Information_segment = $Bol_specific_segment->addChild('Information_segment');
        $Information_segment->addChild('Information_part_a')->addChild('null');

        // Add the Operations_segment
        $Operations_segment = $Bol_specific_segment->addChild('Operations_segment');
        $Operations_segment->addChild('Packages_remaining');
        $Operations_segment->addChild('Gross_mass_remaining');

        // Add the Location_segment inside Operations_segment
        $Location_segment = $Operations_segment->addChild('Location_segment');
        $Location_segment->addChild('Code')->addChild('null');
        $Location_segment->addChild('Name')->addChild('null');
        $Location_segment->addChild('Information')->addChild('null');

        // Add the Onward_transport_segment inside Operations_segment
        $Onward_transport_segment = $Operations_segment->addChild('Onward_transport_segment');

        // Add the Transit_segment inside Onward_transport_segment
        $Transit_segment = $Onward_transport_segment->addChild('Transit_segment');
        $Transit_segment->addChild('Customs_office_code')->addChild('null');
        $Transit_segment->addChild('Customs_office_name')->addChild('null');
        $Transit_segment->addChild('Document_reference')->addChild('null');

        // Add the Transhipment_segment inside Onward_transport_segment
        $Transhipment_segment = $Onward_transport_segment->addChild('Transhipment_segment');
        $Transhipment_segment->addChild('Transipment_location_code')->addChild('null');
        $Transhipment_segment->addChild('Transhipment_location_name')->addChild('null');
        $Transhipment_segment->addChild('Document_reference')->addChild('null');

        // Add the Onward_carrier_segment inside Onward_transport_segment
        $Onward_carrier_segment = $Onward_transport_segment->addChild('Onward_carrier_segment');
        $Onward_carrier_segment->addChild('Code')->addChild('null');
        $Onward_carrier_segment->addChild('Name')->addChild('null');

        // Create a DOM document for indentation
        $xml_string = $xml->asXML();  // Get the raw XML string
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml_string);

        // Save the XML file
        // $xml_file = $path . $bl_num . '.xml';  // Absolute path
        $path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/";
        createpath($path);
        $xml_file = $path . sanitize_filename($bl_num) . '.xml';
        $dom->save($xml_file);
        echo "XML file '$bl_num.xml' generated successfully.</br>";
        header("location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	}

	
	function allVessels($key = "all", $query = ""){
		$myid = $_SESSION['id']; $companyid = allData('users', $myid, 'companyid');
		$rcvrnm=$repnm=$stvdrnm=$slnm=$consigneeName="";
		$survey_consignee=$survey_custom=$survey_supplier=$survey_pni=$survey_chattrer=$survey_owner="";
		$remarksName = ""; GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];

		if ($key == "upcoming") {
			$dynamicsql = "SELECT * FROM vessels WHERE rcv_date = '' AND companyid = '$companyid' ORDER BY id DESC ";
		}elseif ($key == "online") {
			$dynamicsql = "SELECT * FROM vessels WHERE rcv_date != '' AND sailing_date = '' AND companyid = '$companyid' ORDER BY id DESC ";
		}elseif ($key == "completed") {
			$dynamicsql = "SELECT * FROM vessels WHERE rcv_date != '' AND sailing_date != '' AND companyid = '$companyid' ORDER BY id DESC ";
		}elseif ($key != "upcoming" && $key != "online" && $key != "completed" && $key !="query" && $key != "all" && $key != "default") {
			$dynamicsql = "SELECT * FROM vessels WHERE YEAR(STR_TO_DATE(rcv_date, '%d-%m-%Y')) = '$key' AND companyid = '$companyid' ";
		}elseif ($key == "all") {
			$dynamicsql = "SELECT * FROM vessels WHERE companyid = '$companyid' ";
		}elseif ($key == "default") {
			$dynamicsql = "SELECT * FROM vessels WHERE companyid = '$companyid' ORDER BY id DESC LIMIT 20 ";
		}
		// filter
		elseif ($key == "query" && isset($query) && !empty($query)) {

			// set the array value empty
			$mslnums_importer = $mslnums_portcode = $mslnums_cargo = $mslnums_cnf = $mslnums_surveyor = $mslnums_surveycompany = array();
			$dynamicsql = "SELECT * FROM vessels WHERE msl_num != '' AND companyid = '$companyid' ";

			// gather the datas received from server and store in arrays.
			// filter representative
			if (isset($query['fltrrepresentative']) && !empty($query['fltrrepresentative'])) { // 
				$rep = $query['fltrrepresentative']; $dynamicsql .= "AND representative = '$rep' ";
			} // filter importer
			if (isset($query['fltrimporter']) && !empty($query['fltrimporter'])) {
				$importer_ids_str = implode(',', $query['fltrimporter']); 
				// $getvsl = "SELECT * FROM vessels_importer WHERE importer IN ($importer_ids_str)";
				$getvsl = "SELECT id, importer AS name, vsl_num FROM vessels_importer WHERE importer IN ($importer_ids_str) UNION ALL SELECT id, receiver_name AS name, vsl_num FROM vessels_bl WHERE receiver_name IN ($importer_ids_str)";
				$r = mysqli_query($db, $getvsl); while ($rw = mysqli_fetch_assoc($r)) {
					$mslnums_importer[] = $rw['vsl_num'];
				}
			} //filter load port
			if (isset($query['fltrportcode']) && !empty($query['fltrportcode'])) {
				// convert portcode id to string like {port_ids_str = "1,2,4,7";}
				$port_ids_str = implode(',', $query['fltrportcode']); 
				// select from vessels_cargo which has these port codes
				// $getvsl = "SELECT * FROM vessels_cargo WHERE loadport IN ($port_ids_str)";
				$getvsl = "SELECT vsl_num FROM vessels_cargo WHERE loadport IN ($port_ids_str) UNION ALL SELECT vsl_num FROM vessels_bl WHERE load_port IN ($port_ids_str)";
				$r = mysqli_query($db, $getvsl); 
				// checks if found any vessel
				if(mysqli_num_rows($r) > 0){
					while ($rw = mysqli_fetch_assoc($r)) {
						// store the vsl_num in an array named $mslnums_portcode[]
						$mslnums_portcode[] = $rw['vsl_num'];
					}
				}
				// if didn't find any vessel, stores zero in the array
				else{$mslnums_portcode[] = 0;}
				
			} //filter cargo
			if (isset($query['fltrcargo']) && !empty($query['fltrcargo'])) {
				$cargo_ids_str = implode(',', $query['fltrcargo']); 
				// $getvsl = "SELECT * FROM vessels_cargo WHERE cargo_key IN ($cargo_ids_str)";
				$getvsl = "SELECT vsl_num FROM vessels_cargo WHERE cargo_key IN ($cargo_ids_str) UNION ALL SELECT vsl_num FROM vessels_bl WHERE cargokeyId IN ($cargo_ids_str)";

				$r = mysqli_query($db, $getvsl); while ($rw = mysqli_fetch_assoc($r)) {
					$mslnums_cargo[] = $rw['vsl_num'];
				}
			} //filter cnf
			if (isset($query['fltrcnf']) && !empty($query['fltrcnf'])) {
				$cnf_ids_str = $query['fltrcnf']; 
				// $getvsl = "SELECT * FROM vessels_importer WHERE cnf IN ($cnf_ids_str)";

				$getvsl = "SELECT vsl_num FROM vessels_importer WHERE cnf IN ($cnf_ids_str) UNION ALL SELECT vsl_num FROM vessels_bl WHERE cnf_name IN ($cnf_ids_str)";

				$r = mysqli_query($db, $getvsl); while ($rw = mysqli_fetch_assoc($r)) {
					$mslnums_cnf[] = $rw['vsl_num'];
				}
			} //filter surveyor
			if (isset($query['fltrsurveyor']) && !empty($query['fltrsurveyor'])) {
				$surveyor_ids_str = $query['fltrsurveyor']; 
				$getvsl = "SELECT * FROM vessels_surveyor WHERE surveyor IN ($surveyor_ids_str)";
				$r = mysqli_query($db, $getvsl); if (mysqli_num_rows($r) > 0) {
					while($rw=mysqli_fetch_assoc($r)){$mslnums_surveyor[]=$rw['vsl_num'];}
				}else{$msl_str_surveyor = 0;} 
			} //filter survey company
			if (isset($query['fltrsurveycompany']) && !empty($query['fltrsurveycompany'])) {
				$surveycompany_ids_str = $query['fltrsurveycompany']; 
				$getvsl = "SELECT * FROM vessels_surveyor WHERE survey_company IN ($surveycompany_ids_str)";
				$r = mysqli_query($db, $getvsl); if (mysqli_num_rows($r) > 0) {
					while($rw=mysqli_fetch_assoc($r)){$mslnums_surveycompany[]=$rw['vsl_num'];}
				}else{$msl_str_surveycompany = 0;} 
			} // filter stevedore
			if (isset($query['fltrstevedore']) && !empty($query['fltrstevedore'])) {
				$stevedore = $query['fltrstevedore']; $dynamicsql .= "AND stevedore = '$stevedore' ";
			} // filter vsl_opa
			if (isset($query['fltropa']) && !empty($query['fltropa'])) {
				$vsl_opa = $query['fltropa']; $dynamicsql .= "AND vsl_opa = '$vsl_opa' ";
			} // filter kutubdia
			if (isset($query['fltrkutubdia']) && !empty($query['fltrkutubdia'])) {
				$kutubdia = $query['fltrkutubdia']; $dynamicsql .= "AND anchor = 'Kutubdia' ";
			} // filter outer
			if (isset($query['fltrouter']) && !empty($query['fltrouter'])) {
				$outer = $query['fltrouter']; $dynamicsql .= "AND anchor = 'Outer' ";
			} // filter 78 permission granted
			if (isset($query['fltrseventyeight']) && !empty($query['fltrseventyeight'])) {
				$seventyeight = $query['fltrseventyeight']; $dynamicsql .= "AND seventyeight_qty != '' ";
			}// filter custom_visit
			if (isset($query['fltrcustom']) && !empty($query['fltrcustom'])) {
				$custom_visited = $query['fltrcustom']; $dynamicsql .= "AND custom_visited = '1' ";
			}// filter qurentine_visited
			if (isset($query['fltrqurentine']) && !empty($query['fltrqurentine'])) {
				$qurentine_visited = $query['fltrqurentine']; $dynamicsql .= "AND qurentine_visited = '1' ";
			}// filter psc_visited
			if (isset($query['fltrpsc']) && !empty($query['fltrpsc'])) {
				$psc_visited = $query['fltrpsc']; $dynamicsql .= "AND psc_visited = '1' ";
			}// filter multiple_lightdues
			if (isset($query['fltrlightdues']) && !empty($query['fltrlightdues'])) {
				$multiple_lightdues = $query['fltrlightdues']; $dynamicsql .= "AND multiple_lightdues = '1' ";
			}// filter crew_change
			if (isset($query['fltrcrew']) && !empty($query['fltrcrew'])) {
				$crew_change = $query['fltrcrew']; $dynamicsql .= "AND crew_change = '1' ";
			}// filter has_grab
			if (isset($query['fltrgrab']) && !empty($query['fltrgrab'])) {
				$has_grab = $query['fltrgrab']; $dynamicsql .= "AND has_grab = '1' ";
			}// filter fender
			if (isset($query['fltrfender']) && !empty($query['fltrfender'])) {
				$fender = $query['fltrfender']; $dynamicsql .= "AND fender = '1' ";
			}// filter fresh_water
			if (isset($query['fltrwater']) && !empty($query['fltrwater'])) {
				$fresh_water = $query['fltrwater']; $dynamicsql .= "AND fresh_water = '1' ";
			}// filter piloting
			if (isset($query['fltrpiloting']) && !empty($query['fltrpiloting'])) {
				$piloting = $query['fltrpiloting']; $dynamicsql .= "AND piloting = '1' ";
			}// filter sscec
			if (isset($query['fltrsscec']) && !empty($query['fltrsscec'])) {
				$sscec = $query['fltrsscec']; $dynamicsql .= "AND sscec = '1' ";
			}// filter egm
			if (isset($query['fltregm']) && !empty($query['fltregm'])) {
				$egm = $query['fltregm']; $dynamicsql .= "AND egm = '0' ";
			}// filter grab
			if (isset($query['fltrgrab']) && !empty($query['fltrgrab'])) {
				$grab = $query['fltrgrab']; $dynamicsql .= "AND has_grab = '1' ";
			}


			// Create an array containing all five arrays
			$arrays = array($mslnums_importer, $mslnums_portcode, $mslnums_cargo, $mslnums_cnf, $mslnums_surveyor, $mslnums_surveycompany);

			// Filter out empty arrays
			$non_empty_arrays = array_filter($arrays, function($arr) { return !empty($arr); });

			// Check if there are at least two non-empty arrays
			if (count($non_empty_arrays) >= 2) {
			    // Find common values among non-empty arrays
			    $common_vessels = call_user_func_array('array_intersect', $non_empty_arrays);
			    // convert array to string using implode
			    $msl_str_common = implode(',', $common_vessels);
			    $dynamicsql .= "AND id IN ($msl_str_common)";
			}elseif (count($non_empty_arrays) === 1) {
			    // if has only one array, it makes it usable to implode
			    $common_vessels = reset($non_empty_arrays); 
			    $msl_str_common = implode(',', $common_vessels);
			    $dynamicsql .= "AND id IN ($msl_str_common)";
			} else { $msl_str_common = 0; }


			if (isset($query['dates']) && !empty($query['dates'])) {
				$dates = $query['dates']; $from = $dates[0]; $to = $dates[1];
				$dynamicsql .= "AND STR_TO_DATE(rcv_date, '%d-%m-%Y') BETWEEN '$from' AND '$to'";
			}
		}else{$dynamicsql = "SELECT * FROM vessels WHERE companyid = '$companyid' ORDER BY id DESC LIMIT 20 ";}
		// show dynamicsql
		// echo "<p>".$dynamicsql."</p>";
		$run = mysqli_query($db, $dynamicsql); 
		
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; //
			$vsl_num = $row['id'];
			$msl_num = $row['msl_num']; //
			$vessel_name = $row['vessel_name']; //
			if(!empty($row['arrived'])){$arrived=date('d-m-Y',strtotime($row['arrived']));}
			else{$arrived="";}
			if(!empty($row['rcv_date'])){$rcv_date=date('d-m-Y',strtotime($row['rcv_date']));}
			else{$rcv_date="";}
			// $rcv_date = date('d-m-Y', strtotime($row['rcv_date'])); //
			// $arrived = $row['arrived']; //
			// $rcv_date = $row['rcv_date']; //
			$sailing_date = $row['sailing_date']; //
			$remarksName = $row['remarks'];
			$anchor = $row['anchor'];

			
			
			$runsurvey = mysqli_query($db, "SELECT * FROM surveycompany WHERE id = ".$row['id']." ");
			$survey_consignee = allData('surveycompany', $row['survey_consignee'], 'company_name');
			$survey_custom = allData('surveycompany', $row['survey_custom'], 'company_name');
			$survey_supplier = allData('surveycompany', $row['survey_supplier'], 'company_name');
			$survey_pni = allData('surveycompany', $row['survey_pni'], 'company_name');
			$survey_chattrer = allData('surveycompany', $row['survey_chattrer'], 'company_name');
			$survey_owner = allData('surveycompany', $row['survey_owner'], 'company_name');

			$cargonm = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_key');
			$loadport = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'loadport');
			if(!is_bool($loadport)&&$loadport!=0){
				$port_code=allData('loadport',$cargonm,'port_code');
				$loadportnm = allData('loadport',$cargonm,'port_name');
			}else{$loadportnm = $port_code = "";}
			
			if(!is_bool($cargonm)&&$cargonm!=0){$cargo_short_name=allData('cargokeys',$cargonm,'name');}
			else{
				// $cargo_short_name="";
				$getcargo = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' GROUP BY cargokeyId "); $cnm = array();
				if (mysqli_num_rows($getcargo) > 0) {
					while ($cargorow = mysqli_fetch_assoc($getcargo)) {
						$cargoid = $cargorow['cargokeyId'];
						$cnm[] = allData('cargokeys', $cargoid, 'name');
					}$cargo_short_name = implode(',', $cnm);
				}
				else{$cargo_short_name = "";}
			}
			$cargo_bl_name = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_bl_name');

			if (exist("vessels_bl", "vsl_num = '$vsl_num' ")) {
				$qty = formatIndianNumber(gettotal('vessels_bl', 'vsl_num', $vsl_num, 'cargo_qty'));
			}else{$qty = formatIndianNumber(gettotal('vessels_cargo', 'vsl_num', $vsl_num, 'quantity'));}

			$stevedore = $row['stevedore']; // 
			if(!is_bool($stevedore)){$stvdrnm = allData('stevedore', $stevedore, 'name'); }
			
			$received_by = $row['received_by'];  //
			if(!is_bool($received_by) && $received_by != 0){$rcvrnm = allData('users', $received_by, 'name');}

			$sailed_by = $row['sailed_by']; //
			if(!is_bool($sailed_by) && $sailed_by != 0){$slnm = allData('users', $sailed_by, 'name'); }
			
			$representative = $row['representative']; //
			if(!is_bool($representative) && $representative != 0){$repnm = allData('users', $representative, 'name'); }
			$status = $row['status']; //
			
			$consignee = allDataUpdated('vessels_importer', 'vsl_num', $vsl_num, 'importer'); //
			if(!is_bool($consignee) && $consignee != 0){$consigneeName = allData('bins', $consignee, 'name'); }
			// 							table name 		field name 	field value  data
			// $remarks = allDataUpdated('vessels_remarks', 'msl_num', $msl_num, 'remarks'); //
			// if(!is_bool($remarks) && $remarks != 0){$remarksName = allData('remarks', $remarks, 'name'); }
			

						echo "
				<tr>
					<th scope=\"row\">$msl_num</th>
					<td>
						<a href=\"vessel_details.php?edit=$vsl_num\">
							MV.$vessel_name 
						</a>
					</td>
					<td>$cargo_short_name</td>
					<td>".formatIndianNumberNew(ttlcargoqty($vsl_num))." MT</td>

					<!-- SEARCH KEYS -->
					<td style=\"text-align:center;\">
						$repnm 
					</td>

					<td style=\"text-align:center;\">
						".percentage($vsl_num)." % 
					</td>

					<td class= style=\"display:inline-block;\">
						".$arrived."
						<span style=\"display:none;\">
							$id, $stvdrnm, $remarksName, $rcv_date, $rcvrnm, $slnm, $anchor, $survey_consignee, $survey_custom, $survey_supplier, $survey_pni, $survey_chattrer, $survey_owner, ".vesselsImporterTag($vsl_num).vesselsCnfTag($vsl_num).vesselsCargoTag($vsl_num)."
						</span>
						<!--a href=\"vessel_details.php?vsl_num=$vsl_num\" class=\"btn btn-success btn-sm\">
							<i class=\"bi bi-file-earmark-break\"></i>
						</a>
						<a href=\"vessel_details.php?edit=$vsl_num\" class=\"btn btn-warning btn-sm\">
							<i class=\"bi bi-pencil\" style=\"color: white;\"></i>
						</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"index.php?del_msl_num=$vsl_num\" 
							class=\"btn btn-danger btn-sm\"
						><i class=\"bi bi-trash\"></i></a-->
					</td>
                </tr>
			";
			$repnm = "";
		}
	}

	// task companywise
	function taskcompanywise($companyid){
		if (!empty($companyid)) {
			$myid = $_SESSION['id']; $companyid = allData('users', $myid, 'companyid');
			$rcvrnm=$repnm=$stvdrnm=$slnm=$consigneeName="";
			$survey_consignee=$survey_custom=$survey_supplier=$survey_pni=$survey_chattrer=$survey_owner="";
			$remarksName = ""; GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];

			$sql = "SELECT * FROM vessels WHERE companyid = '$companyid' AND workstatus = 'notdone' ";
			$run = mysqli_query($db, $sql); 
			
			while ($row = mysqli_fetch_assoc($run)) {
				$id = $row['id']; //
				$vsl_num = $row['id'];
				$msl_num = $row['msl_num']; //
				$vessel_name = $row['vessel_name']; //
				if(!empty($row['arrived'])){$arrived=date('d-m-Y',strtotime($row['arrived']));}
				else{$arrived="";}
				if(!empty($row['rcv_date'])){$rcv_date=date('d-m-Y',strtotime($row['rcv_date']));}
				else{$rcv_date="";}
				// $rcv_date = date('d-m-Y', strtotime($row['rcv_date'])); //
				// $arrived = $row['arrived']; //
				// $rcv_date = $row['rcv_date']; //
				$sailing_date = $row['sailing_date']; //
				$remarksName = $row['remarks'];
				$anchor = $row['anchor'];

				
				
				$runsurvey = mysqli_query($db, "SELECT * FROM surveycompany WHERE id = ".$row['id']." ");
				$survey_consignee = allData('surveycompany', $row['survey_consignee'], 'company_name');
				$survey_custom = allData('surveycompany', $row['survey_custom'], 'company_name');
				$survey_supplier = allData('surveycompany', $row['survey_supplier'], 'company_name');
				$survey_pni = allData('surveycompany', $row['survey_pni'], 'company_name');
				$survey_chattrer = allData('surveycompany', $row['survey_chattrer'], 'company_name');
				$survey_owner = allData('surveycompany', $row['survey_owner'], 'company_name');

				$cargonm = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_key');
				$loadport = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'loadport');
				if(!is_bool($loadport)&&$loadport!=0){
					$port_code=allData('loadport',$cargonm,'port_code');
					$loadportnm = allData('loadport',$cargonm,'port_name');
				}else{$loadportnm = $port_code = "";}
				
				if(!is_bool($cargonm)&&$cargonm!=0){$cargo_short_name=allData('cargokeys',$cargonm,'name');}
				else{
					// $cargo_short_name="";
					$getcargo = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' GROUP BY cargokeyId "); $cnm = array();
					if (mysqli_num_rows($getcargo) > 0) {
						while ($cargorow = mysqli_fetch_assoc($getcargo)) {
							$cargoid = $cargorow['cargokeyId'];
							$cnm[] = allData('cargokeys', $cargoid, 'name');
						}$cargo_short_name = implode(',', $cnm);
					}
					else{$cargo_short_name = "";}
				}
				$cargo_bl_name = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_bl_name');

				if (exist("vessels_bl", "vsl_num = '$vsl_num' ")) {
					$qty = formatIndianNumber(gettotal('vessels_bl', 'vsl_num', $vsl_num, 'cargo_qty'));
				}else{$qty = formatIndianNumber(gettotal('vessels_cargo', 'vsl_num', $vsl_num, 'quantity'));}

				$stevedore = $row['stevedore']; // 
				if(!is_bool($stevedore)){$stvdrnm = allData('stevedore', $stevedore, 'name'); }
				
				$received_by = $row['received_by'];  //
				if(!is_bool($received_by) && $received_by != 0){$rcvrnm = allData('users', $received_by, 'name');}

				$sailed_by = $row['sailed_by']; //
				if(!is_bool($sailed_by) && $sailed_by != 0){$slnm = allData('users', $sailed_by, 'name'); }
				
				$representative = $row['representative']; //
				if(!is_bool($representative) && $representative != 0){$repnm = allData('users', $representative, 'name'); }
				$status = $row['status']; //
				
				$consignee = allDataUpdated('vessels_importer', 'vsl_num', $vsl_num, 'importer'); //
				if(!is_bool($consignee) && $consignee != 0){$consigneeName = allData('bins', $consignee, 'name'); }
				// 							table name 		field name 	field value  data
				// $remarks = allDataUpdated('vessels_remarks', 'msl_num', $msl_num, 'remarks'); //
				// if(!is_bool($remarks) && $remarks != 0){$remarksName = allData('remarks', $remarks, 'name'); }
				

							echo "
					<tr>
						<th scope=\"row\">$msl_num</th>
						<td>
							<a href=\"vessel_details.php?ship_perticular=$vsl_num\">
								MV.$vessel_name 
							</a>
						</td>
						<td>$cargo_short_name</td>
						<td>$qty MT</td>

						<!-- SEARCH KEYS -->
						<td style=\"text-align:center;\">
							$repnm 
						</td>

						<td style=\"text-align:center;\">
							".percentage($vsl_num)." % 
						</td>

						<td class= style=\"display:inline-block;\">
							".$arrived."
							<span style=\"display:none;\">
								$id, $stvdrnm, $remarksName, $rcv_date, $rcvrnm, $slnm, $anchor, $survey_consignee, $survey_custom, $survey_supplier, $survey_pni, $survey_chattrer, $survey_owner, ".vesselsImporterTag($vsl_num).vesselsCnfTag($vsl_num).vesselsCargoTag($vsl_num)."
							</span>
							<!--a href=\"vessel_details.php?vsl_num=$vsl_num\" class=\"btn btn-success btn-sm\">
								<i class=\"bi bi-file-earmark-break\"></i>
							</a>
							<a href=\"vessel_details.php?edit=$vsl_num\" class=\"btn btn-warning btn-sm\">
								<i class=\"bi bi-pencil\" style=\"color: white;\"></i>
							</a>
							<a 
								onClick=\"javascript: return confirm('Please confirm deletion');\" 
								href=\"index.php?del_msl_num=$vsl_num\" 
								class=\"btn btn-danger btn-sm\"
							><i class=\"bi bi-trash\"></i></a-->
						</td>
	                </tr>
				";
				$repnm = "";
			}
		}	
	}

	function vesselSurveyors($vsl_num = 111, $type = "load"){
		if ($type == "load") {$type = "AND survey_purpose = \"Load Draft\"";}
		elseif($type=="all"){$type = "";}
		else{$type = "AND survey_purpose != \"Load Draft\"";}
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $run = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' ".$type." ");
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $survey_party = $row['survey_party'];
			$survey_company = $row['survey_company']; $survey_purpose = $row['survey_purpose'];
			$company_name = allData('surveycompany', $survey_company, 'company_name');
			$surveyorId = $row['surveyor']; $surveyor = allData('surveyors', $surveyorId, 'surveyor_name');
			echo "
				<tr>
					<td scope=\"col\">$survey_party</td>
					<td scope=\"col\">$company_name</td>
					<td scope=\"col\">$survey_purpose</td>
					<td scope=\"col\">$surveyor</td>
					<td scope=\"col\">
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editVesselSurveyors$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i> Edit</span>
						</a>
						<!--a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"vessel_details.php?edit=$vsl_num&delVesselSurveyors=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a-->
					</td>
				</tr>
			";
		}
	}

	function vesselSurveyorsUpdated($vsl_num = 111, $type = "load"){
		if ($type == "load") {$type = "AND survey_purpose = \"Load Draft\"";}
		elseif($type=="all"){$type = "";}
		else{$type = "AND survey_purpose != \"Load Draft\"";}
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];


		$runvsl = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
		$rowvsl = mysqli_fetch_assoc($runvsl);
		$consignee = $rowvsl['survey_consignee']; $custom = $rowvsl['survey_custom'];
		$supplier = $rowvsl['survey_supplier']; $pni = $rowvsl['survey_pni'];
		$chattrer = $rowvsl['survey_chattrer']; $owner = $rowvsl['survey_owner'];

		$run = mysqli_query($db, "SELECT * FROM vessels_surveyor WHERE vsl_num = '$vsl_num' ".$type." ");
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $survey_party = $row['survey_party'];
			$survey_company = $row['survey_company']; $survey_purpose = $row['survey_purpose'];
			$company_name = allData('surveycompany', $survey_company, 'company_name');
			$surveyorId = $row['surveyor']; $surveyor = allData('surveyors', $surveyorId, 'surveyor_name');
			echo "
				<tr>
					<td scope=\"col\">$survey_party</td>
					<td scope=\"col\">$company_name</td>
					<td scope=\"col\">$survey_purpose</td>
					<td scope=\"col\">$surveyor</td>
					<td scope=\"col\">
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editVesselSurveyors$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i></span>
						</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"vessel_details.php?edit=$vsl_num&delVesselSurveyors=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a>
					</td>
				</tr>
			";
		}
	}

	function vesselCargo($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];


		// if bl exist, cargo will come from bl, or else will come from manual cargo add
		if (!exist("vessels_bl","vsl_num = '$vsl_num' ")) {
			$run = mysqli_query($db, "SELECT * FROM vessels_cargo WHERE vsl_num = '$vsl_num' ");
			if (mysqli_num_rows($run) > 0) {
				while ($row = mysqli_fetch_assoc($run)) {
					$id = $row['id']; $cargo_key = $row['cargo_key']; $loadport = $row['loadport']; 
					$quantity = $row['quantity']; $cargo_bl_name = $row['cargo_bl_name']; 
					$cargo = allData('cargokeys', $cargo_key, 'name');
					$loadportnm = allData('loadport', $loadport, 'port_name');
					echo "
						<tr>
							<td scope=\"col\">$cargo</td>
							<td scope=\"col\">$loadportnm</td>
							<td scope=\"col\">$quantity</td>
							<td scope=\"col\">$cargo_bl_name</td>
							<td scope=\"col\">
								<a 
									href=\"#\" 
									style=\"text-decoration: none; padding: 5px;\"
									data-toggle=\"modal\" data-target=\"#editVesselCargo$id\"
								>
									<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i></span>
								</a>
								<a 
									onClick=\"javascript: return confirm('Please confirm deletion');\"
									href=\"vessel_details.php?edit=$vsl_num&delVesselCargoCon=$id\" 
									style=\"text-decoration: none; padding: 5px;\"
								>
									<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
								</a>
							</td>
						</tr>
					";
				}
			}
		}
		else{
			$total_qty = $retention_qty = $totalctgqty = 0;
			$run1 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' GROUP BY cargokeyId ");
			while ($row1 = mysqli_fetch_assoc($run1)) {
				$cargokeyId = $row1['cargokeyId'];
				$run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND cargokeyId = '$cargokeyId' ");
				$totalctgqty = 0;
				while ($row = mysqli_fetch_assoc($run)) {
					$id = $row['id']; 
					$cargokeyId = $row['cargokeyId']; 
					$loadport = $row['load_port']; 
					$desc_portId = $row['desc_port'];

					// $totalctgqty = $totalctgqty + $quantity = $row['cargo_qty']; 
					$totalctgqty = $totalctgqty + $quantity = $row['cargo_qty']; 

					$cargo_bl_name = $row['cargo_name']; 
					$cargokey = allData('cargokeys', $cargokeyId, 'name');
					$loadportnm = allData('loadport', $loadport, 'port_name');

					if ($desc_portId == 65) {$retentioncargo = "";}
					else{$retentioncargo = "Retention: ";}
				}
				echo "
					<tr>
						<td scope=\"col\">$cargokey</td>
						<td scope=\"col\">$loadportnm</td>
						<td scope=\"col\">$totalctgqty</td>
						<td scope=\"col\">$retentioncargo $cargo_bl_name</td>
						<td scope=\"col\">
							<!--a 
								href=\"#\" 
								style=\"text-decoration: none; padding: 5px;\"
								data-toggle=\"modal\" data-target=\"#editVesselCargo$id\"
							>
								<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i></span>
							</a>
							<a 
								onClick=\"javascript: return confirm('Please confirm deletion');\"
								href=\"vessel_details.php?edit=$vsl_num&delVesselCargoCon=$id\" 
								style=\"text-decoration: none; padding: 5px;\"
							>
								<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
							</a-->
						</td>
					</tr>
				";
			}


			// $run2 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' "); 
			// $ctgqty = 0; $retention_qty = 0; $total = 0;
			// while ($row2 = mysqli_fetch_assoc($run2)) {
			// 	$id = $row2['id']; //
			// 	$line_num = $row2['line_num']; //
			// 	$bl_num = $row2['bl_num']; //
			// 	$cargo_name = $row2['cargo_name']; //
			// 	$cargo_qty = $row2['cargo_qty']; //
			// 	$loadPortId = $row2['load_port'];
			// 	$desc_portId = $row2['desc_port'];
			// 	$load_port = allData('loadport', $loadPortId, 'port_name');
			// 	$port_code = allData('loadport', $loadPortId, 'port_code');
			// 	if ($desc_portId == 65) {$ctgqty = $ctgqty + $cargo_qty;}
			// 	else{$retention_qty = $retention_qty + $cargo_qty;}
			// 	$total = $total+$cargo_qty;
			// }
			// 
			// $total_qty = $totalctgqty + $retention_qty;
			echo "
				<tr>
					<td scope=\"col\" colspan=\"5\">
						Ctg Qty: &nbsp;".formatIndianNumberNew(ttlcargoqty($vsl_num, "ctg"))." MT &nbsp;&nbsp;&nbsp;&nbsp;
						Retention Qty: &nbsp;".formatIndianNumber(ttlcargoqty($vsl_num, "retention"))." MT &nbsp;&nbsp;&nbsp;&nbsp;
						Total Qty: &nbsp;".formatIndianNumberNew(ttlcargoqty($vsl_num))." MT 
					</td>
				</tr>
			";
		}	
	}

	function vesselsCnfOld($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $importer = $row['importer'];$cnf = $row['cnf']; 
			$importer = allData('bins', $importer, 'name'); if ($cnf != 0) {
				$cnfName = allData('cnf', $cnf, 'name');
			}else{$cnfName = "";}
			echo "
				<tr>
					<td scope=\"col\">$importer</td>
					<td scope=\"col\">$cnfName</td>
					<td scope=\"col\">
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editVesselsCnf$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i> Edit</span>
						</a>
						<!--a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"vessel_details.php?edit=$vsl_num&delVesselsCnf=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a-->
					</td>
				</tr>
			";
		}

		// $run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE msl_num = '$msl_num' ");
		// while ($row = mysqli_fetch_assoc($run)) {
		// 	$importer = $row['importer']; $cnf
		// }
	}

	function vesselsCnf($vsl_num = 111){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];

		// $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE msl_num = '$msl_num' GROUP BY receiver_name ");
		$run = mysqli_query($db, "SELECT id, receiver_name, cnf_name FROM vessels_bl WHERE vsl_num = '$vsl_num' GROUP BY receiver_name UNION ALL SELECT id, importer AS receiver_name, cnf FROM vessels_importer WHERE vsl_num = '$vsl_num' GROUP BY importer");
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; 
			$importerId = $row['receiver_name'];
			$cnfId = $row['cnf_name']; 

			 
			if ($importerId != 0) {
				$importer = allData('bins', $importerId, 'name');
			}else{$importer = "";}

			if ($cnfId != 0) {
				$cnfName = allData('cnf', $cnfId, 'name');
			}else{$cnfName = "";}
			echo "
				<tr>
					<td scope=\"col\">$importer</td>
					<td scope=\"col\">$cnfName</td>
					<td scope=\"col\">
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editVesselsCnf$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i> Edit</span>
						</a>
						<!--a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"vessel_details.php?edit=$vsl_num&delVesselsCnf=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a-->
					</td>
				</tr>
			";
		}

		// $run = mysqli_query($db, "SELECT * FROM vessels_importer WHERE msl_num = '$msl_num' ");
		// while ($row = mysqli_fetch_assoc($run)) {
		// 	$importer = $row['importer']; $cnf
		// }
	}




	function cargoAndConsigneeWise(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];


		// $sql = ;
		$run = mysqli_query($db, "SELECT * FROM users WHERE office_position = 'Representative' ORDER BY id DESC "); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; //
			
			$representative = $row['id']; //
			$repnm = $row['name'];
			$status = $row['activation']; //
			$getNum = mysqli_query($db,"SELECT * FROM vessels WHERE representative = '$representative' ");
			$num = mysqli_num_rows($getNum);
			

			echo "
				<tr>
					<th scope=\"row\">$representative</th>
					<td>
						<img src=\"img/userimg/".allData('users', $id, 'image')."\" alt=\"...\" class=\"img-fluid rounded-circle\" width=\"40\">
					</td>
					<td>$repnm</td>
					<td>$num</td>

					<!-- SEARCH KEYS -->
					<td style=\"text-align:center;\">
						 frm dt
					</td>

					<td class= style=\"display:inline-block;\">
						to-dt
						<span style=\"display:none;\">
							$id
						</span>
					</td>
                </tr>
			";
			$repnm = "";
		}
	}


	function vesselDetails($vsl_num){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM vessels WHERE id = '$vsl_num' ";
		$run = mysqli_query($db, $sql); 
		$row = mysqli_fetch_assoc($run);
		// $msl_num = $row['msl_num']; 
		$stevedore = $row['stevedore']; $stvdrnm = allData('stevedore', $stevedore, 'name');
		$received_by = $row['received_by']; 
		if ($received_by != 0) {
			$rcvrnm = allData('users', $received_by, 'name'); //err
		}else{$rcvrnm = "";}
		$sailed_by = $row['sailed_by']; 
		if ($sailed_by != 0) {
			$slrnm = allData('users', $sailed_by, 'name'); //err
		}else{$slrnm = "";}
		$representative = $row['representative']; $repnm = allData('users', $representative, 'name');
		// $remarks = $row['remarks'];

		$vessel_name = $row['vessel_name'];
		$cargo_short_name = $row['cargo_short_name'];
		$cargo_bl_name = $row['cargo_bl_name'];
		$total_qty = $row['total_qty'];
		$rcv_date = $row['rcv_date'];
		$sailing_date = $row['sailing_date'];

		$remarksId = allDataUpdated('vessels_remarks', 'vsl_num', $vsl_num, 'remarks');

		// check if remarksId returns any boolean value
		if(is_bool($remarksId)){$remarks = allData('remarks', $remarksId, 'name');}

		echo "
			<th>
				MSL: <span style=\"color: #FF6C6C;\">$msl_num</span><br/>
				VESSELS NAME: <span style=\"color: #FF6C6C;\">MV.$vessel_name</span> <br/>
				
				
		";
			// if consignee count is more then one then collaple option would be on
			$run1 = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
			$num = mysqli_num_rows($run1);
			if ($num > 1) {
				echo "
					<p data-bs-toggle=\"collapse\" href=\"#CONSIGNEE\" role=\"button\" aria-expanded=\"false\" aria-controls=\"CONSIGNEE\">
					CONSIGNEE: <span style=\"color: #FF6C6C;\">CLICK TO SEE</span>
					</p>	
					<div class=\"collapse\" id=\"CONSIGNEE\">
						<div class=\"card card-body\">
				";
				while ($row1 = mysqli_fetch_assoc($run1)) {
					$id = $row1['id']; $consigneeId = $row1['consignee']; $vesselId = $row1['vsl_num'];
					$consigneeName = allDataUpdated("bins", "id", $consigneeId, "name"); 
					echo "
					<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
						<span style=\"color: #FF6C6C;\">$consigneeName</span>  &nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"hidden\" name=\"consigneeId\" value=\"$id\" >
						<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
						<button type=\"submit\" name=\"delConsigneetovessel\" class=\"btn btn-danger btn-sm\">
							<i class=\"bi bi-trash\"></i>
						</button>
					</form>
					<br/>
					";
				}
				echo"
					</div>
				</div>
				";
			}
			else{
				echo "
				<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
					CONSIGNEE: ";
				while ($row1 = mysqli_fetch_assoc($run1)) {
					$id = $row1['id']; $consigneeId = $row1['consignee']; $vesselId = $row1['vsl_num'];
					$consigneeName = allDataUpdated("bins", "id", $consigneeId, "name"); 
					// echo $vesselId."<br/>";
					echo "
						<span style=\"color: #FF6C6C;\">$consigneeName</span>  &nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"hidden\" name=\"consigneeId\" value=\"$id\" >
						<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
						<button type=\"submit\" name=\"delConsigneetovessel\" class=\"btn btn-danger btn-sm\">
							<i class=\"bi bi-trash\"></i>
						</button>
					
					<br/>
					";
				}
				echo "</form>";
			}

			// if cnf count is more then one, collaple option would be on
			$run2 = mysqli_query($db, "SELECT * FROM vessels_cnf WHERE vsl_num = '$vsl_num' ");
			$num1 = mysqli_num_rows($run2);
			if ($num1 > 1) {
				echo "
					<p data-bs-toggle=\"collapse\" href=\"#CNF\" role=\"button\" aria-expanded=\"false\" aria-controls=\"CNF\">
					CNF: <span style=\"color: #FF6C6C;\">CLICK TO SEE</span>
					</p>	
					<div class=\"collapse\" id=\"CNF\">
						<div class=\"card card-body\">
				";
				while ($row2 = mysqli_fetch_assoc($run2)) {
					$id = $row2['id']; $cnfId = $row2['cnf']; $vesselId = $row2['vsl_num'];
					$cnfName = allDataUpdated("cnf", "id", $cnfId, "name"); 
					// echo $cnfId."<br/>";
					echo "
					<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
						<span style=\"color: #FF6C6C;\">$cnfName</span>  &nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"hidden\" name=\"cnfId\" value=\"$id\" >
						<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
						<button type=\"submit\" name=\"delCnftovessel\" class=\"btn btn-danger btn-sm\">
							<i class=\"bi bi-trash\"></i>
						</button>
					</form>
					<br/>
					";
				}
				echo"
					</div>
				</div>
				";
			}
			else{
				echo "<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
					CNF: ";
				while ($row2 = mysqli_fetch_assoc($run2)) {
					$id = $row2['id']; $cnfId = $row2['cnf']; $vesselId = $row2['vsl_num'];
					$cnfName = allDataUpdated("cnf", "id", $cnfId, "name"); 
				}if(empty($cnfName)){$cnfName = ""; $id = ""; $hide = "display:none";} else{$hide = "";}
				// echo $vesselId."<br/>";
				echo "
						<span style=\"color: #FF6C6C;\">$cnfName</span>  &nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"hidden\" name=\"cnfId\" value=\"$id\" >
						<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
						<button type=\"submit\" style=\"$hide\" name=\"delCnftovessel\" class=\"btn btn-danger btn-sm\">
							<i class=\"bi bi-trash\"></i>
						</button>
					</form>
				";
			}
			
		echo"
				
				STEVEDORE: <span style=\"color: #FF6C6C;\">$stvdrnm</span> <br/>
				CARGO AS PER BL: <span style=\"color: #FF6C6C;\">$cargo_bl_name</span><br/>
				CARGO QUANTITY: <span style=\"color: #FF6C6C;\">$total_qty MT</span><br/>
			</th>

			<th>
				ARRIVAL DATE: <span style=\"color: #FF6C6C;\">$rcv_date</span> <br/>
				SAILING DATE: <span style=\"color: #FF6C6C;\">$sailing_date</span> <br/>
				RECEIVED BY: <span style=\"color: #FF6C6C;\">$rcvrnm</span> <br/>
				SAILED BY: <span style=\"color: #FF6C6C;\">$slrnm</span> <br/>
				REPRESENTATIVE: <span style=\"color: #FF6C6C;\">$repnm</span> <br/>
				CARGO: <span style=\"color: #FF6C6C;\">$cargo_short_name</span>
				
		";
			// if remarks count is more then one then collaple option would be on
			$run1 = mysqli_query($db, "SELECT * FROM vessels_remarks WHERE vsl_num = '$vsl_num' ");
			$num = mysqli_num_rows($run1);
			if ($num > 1) {
				echo "
					<p data-bs-toggle=\"collapse\" href=\"#REMARKS\" role=\"button\" aria-expanded=\"false\" aria-controls=\"REMARKS\">
					REMARKS: <span style=\"color: #FF6C6C;\">CLICK TO SEE</span>
					</p>	
					<div class=\"collapse\" id=\"REMARKS\">
						<div class=\"card card-body\">
				";
				while ($row1 = mysqli_fetch_assoc($run1)) {
					$id = $row1['id']; $remarksId = $row1['remarks']; $vesselId = $row1['vsl_num'];
					$remarks = allDataUpdated("remarks", "id", $remarksId, "name"); 
					echo "
					<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
						<span style=\"color: #FF6C6C;\">$remarks</span>  &nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"hidden\" name=\"remarksId\" value=\"$id\" >
						<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
						<button type=\"submit\" name=\"delRemarkstovessel\" class=\"btn btn-danger btn-sm\">
							<i class=\"bi bi-trash\"></i>
						</button>
					</form>
					<br/>
					";
				}
				echo"
					</div>
				</div>
				";
			}
			else{
				echo "
				<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
					REMARKS: ";
				while ($row1 = mysqli_fetch_assoc($run1)) {
					$id = $row1['id']; $remarksId = $row1['remarks']; $vesselId = $row1['vsl_num'];
					$remarks = allDataUpdated("remarks", "id", $remarksId, "name"); 
					// echo $vesselId."<br/>";
					echo "
						<span style=\"color: #FF6C6C;\">$remarks</span>  &nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"hidden\" name=\"remarksId\" value=\"$id\" >
						<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
						<button type=\"submit\" name=\"delRemarkstovessel\" class=\"btn btn-danger btn-sm\">
							<i class=\"bi bi-trash\"></i>
						</button>
					
					<br/>
					";
				}
				echo "</form><br/>";
			}
				echo"
			</th>
		";
	}

	function vesselDetailsNew($vsl_num){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $myid = $_SESSION['id']; $companyid = allData('users', $myid, 'companyid');
		$sql = "SELECT * FROM vessels WHERE id = '$vsl_num' AND companyid = '$companyid' ";
		// $id = allDataUpdated('vessels', 'msl_num', $msl_num, 'id');
		$run = mysqli_query($db, $sql); $row = mysqli_fetch_assoc($run);
		$id = $row['id']; $vsl_num = $id; $msl_num = $row['msl_num']; $vessel_name = $row['vessel_name'];
		$remarks = $row['remarks'];
		// $msl_num = $row['msl_num']; 
		$stevedore = $row['stevedore']; $stvdrnm = allData('stevedore', $stevedore, 'name');
		$received_by = $row['received_by']; 
		if ($received_by != 0) { $rcvrnm = allData('users', $received_by, 'name');}
		else{$rcvrnm = "";} $sailed_by = $row['sailed_by']; 
		if ($sailed_by != 0) { $slrnm = allData('users', $sailed_by, 'name');}
		else{$slrnm = "";} $representative = $row['representative']; $rotation = $row['rotation'];
		$repnm = allData('users', $representative, 'name');
		// $remarks = $row['remarks'];


		$cargonm = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_key');
		if(!is_bool($cargonm)&&$cargonm!=0){$cargo_short_name=allData('cargokeys',$cargonm,'name');}
			else{$cargo_short_name="";}
		$cargo_bl_name = allDataUpdated('vessels_cargo', 'vsl_num', $vsl_num, 'cargo_bl_name');
		$total_qty = gettotal('vessels_cargo', 'vsl_num', $vsl_num, 'quantity');

		if (allData('vessels', $id, 'rcv_date')) {
			$rcv_date = date('d-m-Y', strtotime(allData('vessels', $id, 'rcv_date')));
		}else{$rcv_date = "";}if (allData('vessels', $id, 'sailing_date')) {
			$sailing_date = date('d-m-Y', strtotime(allData('vessels', $id, 'sailing_date')));
		}else{$sailing_date = "";}
		
		
		$anchor = allData('vessels', $id, 'anchor');

		if (!empty($rcv_date)&&!empty($sailing_date)){$day=dayCount($rcv_date, $sailing_date);}
		else{$day = "";}
		// $remarksId = allData('vessels_remarks', 'msl_num', $msl_num, 'remarks');

		echo "
			<tr>
				<td>$msl_num</td>
				<td colspan=\"2\">$vessel_name</td>
				<td>$rotation</td>
				<td>$rcv_date</td>
				<td>$sailing_date</td>
				<!--td>$stvdrnm</td-->
				<td>$cargo_short_name</td>
				<td>$total_qty MT</td>
			</tr>
			<tr style=\"color: white; border: 1px solid white;\">
				<td colspan=\"4\">Cargo Bl Name</td>
				<td colspan=\"4\">Stevedore</td>
			</tr>
			<tr>
				<td colspan=\"4\">";
					$total = 0;
					$get = mysqli_query($db, "SELECT * FROM vessels_cargo WHERE vsl_num = '$vsl_num' ");
						while ($got = mysqli_fetch_assoc($get)) {
							$cargo_bl_name = $got['cargo_bl_name']; $quantity = $got['quantity'];
							//$port_name = allData('loadport', $loadport, 'port_name');
							echo "$cargo_bl_name : $quantity MT</br>"; $total = $total + $quantity;
						}echo "Total: ".$total." MT";
				echo"
				</td>
				<td colspan=\"4\">$stvdrnm</td>
			</tr>
			<tr style=\"color: white; border: 1px solid white;\">
				<td>Anchor</td>
				<td colspan=\"2\">Load Port</td>
				<td>Representative</td>
				<td colspan=\"2\">Rcved By</td>
				<td>Sailed By</td>
				<td>Day</td>
			</tr>
			<tr>
				<td>$anchor</td>
				<td colspan=\"2\">
		";
			$get = mysqli_query($db, "SELECT * FROM vessels_cargo WHERE vsl_num = '$vsl_num' ");
			while ($got = mysqli_fetch_assoc($get)) {
				$loadport = $got['loadport'];
				$port_name = allData('loadport', $loadport, 'port_name');
				echo "$port_name</br>";
			}
			echo"
			</td>
			<td>$repnm</td>
			<td colspan=\"2\">$rcvrnm</td>
			<td>$slrnm</td>
			<td>$day</td>
		</tr>

		<tr style=\"color: white; border: 1px solid white;\">
			<td colspan=\"4\">Consignee</td>
			<td colspan=\"4\">Cnf</td>
		</tr>";
		$getCon = mysqli_query($db, "SELECT * FROM vessels_importer WHERE vsl_num = '$vsl_num' ");
		while ($gotCon = mysqli_fetch_assoc($getCon)) {
			$conId = $gotCon['importer']; $cnf = $gotCon['cnf'];
			$conName = allData('bins', $conId, 'name');
			if ($cnf != 0) { $cnfName = allData('cnf', $cnf, 'name'); }
			else{$cnfName = "";}
			echo"
				<tr>
					<td colspan=\"4\">$conName</td>
					<td colspan=\"4\">$cnfName</td>
				</tr>
			";
		}
		echo"
		<tr style=\"color: white; border: 1px solid white;\">
			<td colspan=\"8\">Remarks</td>
		</tr>";
		// $getRemarks = mysqli_query($db, "SELECT * FROM vessels_remarks WHERE msl_num = '$msl_num' ");
		// while ($gotRemarks = mysqli_fetch_assoc($getRemarks)) {
		// 	$remarksId = $gotRemarks['remarks']; $remarks = allData('remarks', $remarksId, 'name');
		// 	echo"
		// 		<tr>
		// 			<td colspan=\"8\">$remarks</td>
		// 		</tr>
		// 	";
		// }
		echo"
			<tr style=\"border: 1px solid white;\">
				<td colspan=\"8\">$remarks</td>
			</tr>
		";
	}

	function allConsignee($val = 'addBtn', $thisconsignee = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thisconsignee == "all") {$sql = "SELECT * FROM bins WHERE type = 'IMPORTER' ";}
		else{$sql = "SELECT * FROM bins WHERE type = 'IMPORTER' AND companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $binnumber = $row['bin'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td><a href=\"3rd_parties.php?consigneeview=$id\">$name</a></td>
					<td>$binnumber</td>

					<td>
			";
			if ($val == "editBtn") {
				echo"
					<a 
						href=\"#\" 
						class=\"btn btn-outline-secondary\"
						data-toggle=\"modal\" data-target=\"#editConsignee$id\"
					>Edit</a>
					<a 
						onClick=\"javascript: return confirm('Please confirm deletion');\"
						href=\"3rd_parties.php?page=consignee&delConsignee=$id\" 
						class=\"btn btn-outline-danger\"
					>Delete</a>
				";
			}
			elseif($val == "addBtn"){
				if (isset($_GET['vsl_num'])) {$msl_num = $_GET['vsl_num'];}
				echo"
				<form method=\"post\" action=\"vessel_details.php?vsl_num=$vsl_num\">
					<input type=\"hidden\" name=\"consigneeId\" value=\"$id\" >
					<input type=\"hidden\" name=\"vesselId\" value=\"$vsl_num\" >
					<button type=\"submit\" name=\"addConsigneetovessel\" class=\"btn btn-outline-success\">+ADD</button>
				</form>
				";
			}
			echo"
					</td>
				</tr>
			";
		}
	}

	function allCnf(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM cnf WHERE companyid = '$companyid' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $email = $row['email'];
			$contact = allDataUpdated("cnf_contacts", "company", $id, "name");
			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td><a href=\"3rd_parties.php?cnfview=$id\">$name</a></td>
					<td><a href=\"3rd_parties.php?cnfview=$id\">$email</a></td>
					<td>
			
					<a href=\"#\" 
						class=\"btn btn-outline-secondary\"
						data-toggle=\"modal\" data-target=\"#editCnf$id\"
					>Edit</a>
					<a 
						onClick=\"javascript: return confirm('Please confirm deletion');\"
						href=\"3rd_parties.php?page=consignee&delCnf=$id\" 
						class=\"btn btn-outline-danger\"
					>Delete</a>
				
					<p style=\"display: none;\">$contact</p>
					</td>
				</tr>
			";
		}
	}

	function cnfapproval(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM cnf WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $email = $row['email'];
			$contact = allDataUpdated("cnf_contacts", "company", $id, "name");
			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td><a href=\"3rd_parties.php?cnfview=$id\">$name</a></td>
					<td><a href=\"3rd_parties.php?cnfview=$id\">$email</a></td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Cnf Being Approved!');\"
							href=\"usercontrols.php?page=cnfapproval&&cnfaprvid=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>
					<a href=\"#\" 
						class=\"btn btn-outline-secondary\"
						data-toggle=\"modal\" data-target=\"#editCnf$id\"
					>Edit</a>
					<a 
						onClick=\"javascript: return confirm('Please confirm deletion');\"
						href=\"3rd_parties.php?page=consignee&delCnf=$id\" 
						class=\"btn btn-outline-danger\"
					>Delete</a>
				
					<p style=\"display: none;\">$contact</p>
					</td>
				</tr>
			";
		}
	}


	function totalusers(){
		GLOBAL $db,$my; $myid = $my['id'];
		$sql = "SELECT * FROM users WHERE activation != 'delete' ";
		$run = mysqli_query($db, $sql); while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $email = $row['email']; $balance = $row['balance'];
			$contact = $row['contact']; $office_position = $row['office_position'];
			$img = $row['image'];
			$activation = $row['activation']; if ($activation == "off") {
				$btnnm = "ON"; $btnval = "on"; $btnclr = "success";
			}else{$btnnm = "OFF"; $btnval = "off"; $btnclr = "warning";}

			echo "
				<tr>
					<th scope=\"row\">
						<img src=\"img/userimg/$img\" alt=\"...\" height=\"40\" style=\"border-radius: 50%;\">
					</th>
					<td><a href=\"profile.php?userid=$id\">$name</a></td>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#addbalance$id\"
						>
							$balance
						</a>
					</td>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#edituserprofile$id\"
						>
							".allData('useraccess', $office_position, 'designation')."
						</a>
					</td>
					<td>$email</td>
					<!--td>$contact</td-->
					<td>
					";
					if($id != $myid){
					echo"
					
						<a onClick=\"javascript: return confirm('Please Activation / Deactivation');\"
							href=\"users.php?useraction=$btnval&userid=$id\" 
							class=\"btn btn-outline-$btnclr\"
						>$btnnm</a>

						<a onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"users.php?useraction=delete&userid=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
						<p style=\"display: none\">".allData('useraccess', $office_position, 'designation')."</p>
					
					";
					}else{echo"<a href=\"profile.php?userid=$myid\">Profile</a>";}
					echo"
					</td>
				</tr>
			";
		}
	}


	function allUsers(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
// 		$sql = "SELECT * FROM users WHERE activation != 'delete' AND companyid = '$companyid' ";
        $sql = "SELECT * FROM users WHERE activation != 'delete' ";
		$run = mysqli_query($db, $sql); while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $email = $row['email'];
			$contact = $row['contact']; $office_position = $row['office_position'];
			$img = $row['image'];
			$activation = $row['activation']; if ($activation == "off") {
				$btnnm = "ON"; $btnval = "on"; $btnclr = "success";
			}else{$btnnm = "OFF"; $btnval = "off"; $btnclr = "warning";}

			echo "
				<tr>
					<th scope=\"row\">
						<img src=\"img/userimg/$img\" alt=\"...\" height=\"40\" style=\"border-radius: 50%;\">
					</th>
					<td><a href=\"profile.php?userid=$id\">$name</a></td>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#edituserprofile$id\"
						>
							".allData('useraccess', $office_position, 'designation')."
						</a>
					</td>
					<td>$email</td>
					<!--td>$contact</td-->
					<td>
					";
					if($id != $myid){
					echo"
					
						<a onClick=\"javascript: return confirm('Please Activation / Deactivation');\"
							href=\"users.php?useraction=$btnval&userid=$id\" 
							class=\"btn btn-outline-$btnclr\"
						>$btnnm</a>

						<a onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"users.php?useraction=delete&userid=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
						<p style=\"display: none\">".allData('useraccess', $office_position, 'designation')."</p>
					
					";
					}else{echo"<a href=\"profile.php?userid=$myid\">Profile</a>";}
					echo"
					</td>
				</tr>
			";
		}
	}



	// task for autoforwading employees
	function alltask(){
		GLOBAL $db,$my; $myid = $my['id']; $mycompanyid = $my['companyid'];
		$sql = "SELECT * FROM companies ";
		$run = mysqli_query($db, $sql); while ($row = mysqli_fetch_assoc($run)) {
			$companyid = $row['id']; $company_name = $row['companyname']; $com_email = $row['email'];
			$sqlvsl = "SELECT * FROM vessels WHERE workstatus = 'notdone' AND companyid = '$companyid' ";
			$runvsl = mysqli_query($db, $sqlvsl); $pending = mysqli_num_rows($runvsl);
			// if (!$pending) {continue;}

			echo "
				<tr>
					<th scope=\"row\">".$companyid."</th>
					<td><a href=\"task.php?page=company&&companyid=$companyid\">".$company_name."</a></td>
					<td>".$com_email."</td>
					<td>$pending</td>
				</tr>
			";
		}
	}


	// cnf contact persons
	function cnfContacts($cnf){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM cnf_contacts WHERE company = '$cnf' ";

		$run = mysqli_query($db, $sql); $num = 0;
		// $num = mysqli_num_rows($run);
		while ($row = mysqli_fetch_assoc($run)) { $num++;
			$id = $row['id']; $name = $row['name']; $contact_2 = $row['contact_2']; $contact = $row['contact'];
			$company = $row['company']; $company_name = allData('cnf', $company, 'name');
			echo "
				<tr>
					<th scope=\"row\">$num</th>
					<td>$name</td>
					<td>$contact_2</td>
					<td>$contact</td>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editCnfContact$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i></span>
						</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?cnfview=$cnf&delCnfContact=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a>
					</td>
				</tr>
			";
		}
	}



	// consignee contact persons
	function consigneeContacts($consignee){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM consignee_contacts WHERE company = '$consignee' ";
		$run = mysqli_query($db, $sql); $num = 0;
		// $num = mysqli_num_rows($run);
		while ($row = mysqli_fetch_assoc($run)) { $num++;
			$id = $row['id']; $name = $row['name']; $contact = $row['contact'];
			$company = $row['company']; $company_name = allData('consignee', $company, 'name');
			echo "
				<tr>
					<th scope=\"row\">$num</th>
					<td>$name</td>
					<td>$contact</td>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editConsigneeContact$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i></span>
						</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?consigneeview=$consignee&delConsigneeContact=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a>
					</td>
				</tr>
			";
		}
	}



	// stevedore contact persons
	function stevedoreContacts($stevedore){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM stevedore_contacts WHERE company = '$stevedore' ";
		$run = mysqli_query($db, $sql); $num = 0;
		// $num = mysqli_num_rows($run);
		while ($row = mysqli_fetch_assoc($run)) { $num++;
			$id = $row['id']; $name = $row['name']; $contact = $row['contact'];
			$company = $row['company']; $company_name = allData('stevedore', $company, 'name');
			echo "
				<tr>
					<th scope=\"row\">$num</th>
					<td>$name</td>
					<td>$contact</td>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editStevedoreContact$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i></span>
						</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?stevedoreview=$stevedore&delStevedoreContact=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i></span>
						</a>
					</td>
				</tr>
			";
		}
	}




	// Surveyors
	function allSurveyors($thissurveyor = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thissurveyor == "all") {$sql = "SELECT * FROM surveyors";}
		else{$sql = "SELECT * FROM surveyors WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $surveyor_name = $row['surveyor_name'];
			$contact_1 = $row['contact_1'];
			$contact_2 = $row['contact_2'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$surveyor_name</td>
					<td>$contact_1</td>
					<td>$contact_2</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editSurveyor$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=surveyors&delSurveyor=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}




	// Surveyors
	function surveyorapproval(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM surveyors WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $surveyor_name = $row['surveyor_name'];
			$contact_1 = $row['contact_1'];
			$contact_2 = $row['contact_2'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$surveyor_name</td>
					<td>$contact_1</td>
					<td>$contact_2</td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"usercontrols.php?page=surveyorapproval&&aprvserveyor=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editSurveyor$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=surveyors&delSurveyor=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}

	// stevedores
	function allStevedore($thisstevedore = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thisstevedore == "all") {$sql = "SELECT * FROM stevedore";}
		else{$sql = "SELECT * FROM stevedore WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $email = $row['email'];
			// $getvsl = "SELECT * FROM vessels WHERE stevedore = '$id' ";
			// $runvsl = mysqli_query($db, $getvsl);
			// $countvsl = mysqli_num_rows($runvsl);

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td><a href=\"3rd_parties.php?stevedoreview=$id\">$name</a></td>
					<td>$email</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editStevedore$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=consignee&delStevedore=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}

	// stevedores
	function stevedoreapproval(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM stevedore WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td><a href=\"3rd_parties.php?stevedoreview=$id\">$name</a></td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Stevedore being approved!');\"
							href=\"usercontrols.php?page=stevedoreapproval&&aprvstevedore=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editStevedore$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=consignee&delStevedore=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}

	// stevedores
	function allAgent(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM agent";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $company_name = $row['company_name']; $contact_person = $row['contact_person'];
			$contact_1 = $row['contact_1']; $contact_2 = $row['contact_2'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>
						<a href=\"3rd_parties.php?agentview=$id\" data-toggle=\"modal\" data-target=\"#editAgent$id\">
							$company_name
						</a>
					</td>
					<td>
						<a href=\"3rd_parties.php?agentview=$id\" data-toggle=\"modal\" data-target=\"#editAgent$id\">
							$contact_person
						</a>
					</td>
					<td>
						<a href=\"3rd_parties.php?agentview=$id\" data-toggle=\"modal\" data-target=\"#editAgent$id\">
							$contact_1
						</a>
					</td>
					<td>
						<a href=\"3rd_parties.php?agentview=$id\" data-toggle=\"modal\" data-target=\"#editAgent$id\">
							$contact_2
						</a>
					</td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=agents&delAgent=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}


	function allBins($thisbins = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thisbins == "all") {$sql = "SELECT * FROM bins";}
		else{$sql = "SELECT * FROM bins WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $type = $row['type']; $name = $row['name']; $bin = $row['bin'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
			";
			if ($type == "IMPORTER") {
				echo "<td><a href=\"3rd_parties.php?consigneeview=$id\">$name</a></td>";
			}else{echo "<td>$name</td>";}
					
			echo"
					<td>$bin</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editBankBin$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"index.php?del_bin=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
						<p style=\"display: none;\">$type</p>
					</td>
                </tr>
			";
		}
	}


	function binapproval(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM bins WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $type = $row['type']; $name = $row['name']; $bin = $row['bin'];
			$status = $row['status'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
			";
			if ($type == "IMPORTER") {
				echo "<td><a href=\"3rd_parties.php?consigneeview=$id\">$name</a></td>";
			}else{echo "<td>$name</td>";}
					
			echo"
					<td>
						<a 
							onClick=\"javascript: return confirm('This bin is being Approved!');\"
							href=\"usercontrols.php?page=binapproval&&approvebin=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>$bin</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editBankBin$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"index.php?del_bin=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
						<p style=\"display: none;\">$type</p>
					</td>
                </tr>
			";
		}
	}



	function cargoKeys($thiscargokeys = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thiscargokeys == "all") {$sql = "SELECT * FROM cargokeys";}
		else{$sql = "SELECT * FROM cargokeys WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); $num = 0; $prevmsl = 0; $prevcrkey = 0; $countvsl = 0;
		while ($row = mysqli_fetch_assoc($run)) {
			$num++;
			$id = $row['id']; $name = $row['name']; 
			$countvslid = mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels_cargo WHERE cargo_key = '$id' "));

			// $countvslid = "countvsl".$id;
			// $run1 = mysqli_query($db, "SELECT * FROM vessels_cargo WHERE cargo_key = '$id' ");
			// while ($row1 = mysqli_fetch_assoc($run1)) {
			// 	$msl_num = $row1['msl_num']; $crkey = $row1['cargo_key'];
			// 	if($msl_num == $prevmsl){
			// 		if($crkey == $prevcrkey){$countvsl++;}
			// 	}else{$countvsl++;}
			// 	$prevmsl = $msl_num; $prevcrkey = $crkey;
			// }
			// $countvslid = $countvsl;
			// $countvsl = 0;


			echo "
				<tr>
					<td>$num</td>
					<td>$name</td>
					<td>$countvslid</td>
					<th>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editCargoKey$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"others_adds.php?page=cargoKeys&del_CargoKey=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
					</th>
                </tr>
			";
		}
	}



	function cargokeyapproval(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM cargokeys WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); $num = 0;
		while ($row = mysqli_fetch_assoc($run)) {
			$num++;
			$id = $row['id']; $name = $row['name']; $companyid = $row['companyid'];
			$companyname = allData('company', $companyid, 'companyname');
			$countvslid = mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels_cargo WHERE cargo_key = '$id' "));


			echo "
				<tr>
					<td>$num</td>
					<td>$name</td>
					<td>$companyname</td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Cargokey Being Approved!');\"
							href=\"usercontrols.php?page=cargokeyapproval&&aprvcrgokey=$id\" 
							class=\"btn btn-outline-success\"
						>Approved</a>
					</td>
					<td>$countvslid</td>
					<th>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editCargoKey$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"others_adds.php?page=cargoKeys&del_CargoKey=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
					</th>
                </tr>
			";
		}
	}

	function useraccess(){
		GLOBAL $db,$my; $companyid = $my['companyid'];
		$run = mysqli_query($db, "SELECT * FROM useraccess WHERE companyid = '$companyid' ");
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $designation = $row['designation']; $access_ctrl = $row['access_ctrl']; 
			$bin_ctrl = $row['bin_ctrl']; $vessel_ctrl = $row['vessel_ctrl']; 
			$thirdparty_ctrl = $row['thirdparty_ctrl']; $user_ctrl = $row['user_ctrl']; 
			$others_ctrl = $row['others_ctrl']; $forwading_ctrl = $row['forwading_ctrl'];

			if($access_ctrl){$access_ctrlBtnClass="btn-success";$access_ctrlBtnValue="ON";}
			else{$access_ctrlBtnClass = "btn-danger"; $access_ctrlBtnValue = "OFF";}

			if($bin_ctrl){$bin_ctrlBtnClass="btn-success";$bin_ctrlBtnValue="ON";}
			else{$bin_ctrlBtnClass = "btn-danger"; $bin_ctrlBtnValue = "OFF";}

			if($vessel_ctrl){$vessel_ctrlBtnClass="btn-success";$vessel_ctrlBtnValue="ON";}
			else{$vessel_ctrlBtnClass = "btn-danger"; $vessel_ctrlBtnValue = "OFF";}

			if($thirdparty_ctrl){$thirdparty_ctrlBtnClass="btn-success";$thirdparty_ctrlBtnValue="ON";}
			else{$thirdparty_ctrlBtnClass = "btn-danger"; $thirdparty_ctrlBtnValue = "OFF";}

			if($user_ctrl){$user_ctrlBtnClass="btn-success";$user_ctrlBtnValue="ON";}
			else{$user_ctrlBtnClass = "btn-danger"; $user_ctrlBtnValue = "OFF";}

			if($others_ctrl){$others_ctrlBtnClass="btn-success";$others_ctrlBtnValue="ON";}
			else{$others_ctrlBtnClass = "btn-danger"; $others_ctrlBtnValue = "OFF";}

			if($forwading_ctrl){$forwading_ctrlBtnClass="btn-success";$forwading_ctrlBtnValue="ON";}
			else{$forwading_ctrlBtnClass = "btn-danger"; $forwading_ctrlBtnValue = "OFF";}

			// i can't disable my own accesscontrol
			if($id==$my['office_position']){$myctrl="disabled";}else{$myctrl="";}


			echo "
			<form method=\"post\" action=\"others_adds.php?page=useraccess\">
				<tr>
					<td>$designation</td>
					<td>
						<button type=\"submit\" class=\"btn $access_ctrlBtnClass\" name=\"access_ctrl\" value=\"$id\" data-toggle=\"tooltip\" title=\"User can Add and control Designation\" $myctrl>$access_ctrlBtnValue</button>
					</td>
					<td>
						<button type=\"submit\" class=\"btn $bin_ctrlBtnClass\" name=\"bin_ctrl\" value=\"$id\">$bin_ctrlBtnValue</button>
					</td>
					<td>
						<button type=\"submit\" class=\"btn $vessel_ctrlBtnClass\" name=\"vessel_ctrl\" value=\"$id\" data-toggle=\"tooltip\" title=\"User can add/modify vessel, Vessel details, Ship perticular, Do and bl\">$vessel_ctrlBtnValue</button>
					</td>
					<td>
						<button type=\"submit\" class=\"btn $forwading_ctrlBtnClass\" name=\"forwading_ctrl\" value=\"$id\">$forwading_ctrlBtnValue</button>
					</td>
					<td>
						<button type=\"submit\" class=\"btn $thirdparty_ctrlBtnClass\" name=\"thirdparty_ctrl\" value=\"$id\" disabled>$thirdparty_ctrlBtnValue</button>
					</td>
					<td>
						<button type=\"submit\" class=\"btn $user_ctrlBtnClass\" name=\"user_ctrl\" value=\"$id\" disabled>$user_ctrlBtnValue</button>
					</td>
					<td>
						<button type=\"submit\" class=\"btn $others_ctrlBtnClass\" name=\"others_ctrl\" value=\"$id\" disabled>$others_ctrlBtnValue</button>
					</td>
					<th>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"others_adds.php?page=useraccess&del_useraccess=$id\" 
							class=\"btn btn-danger\"
						><i class=\"bi bi-trash\"></i></a>
					</th>
                </tr>
            </form>
			";
		}
	}



	// databackups
	function databackups(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM backups ORDER BY id DESC";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $file = $row['file']; $date = date('d-m-Y', strtotime($row['date']));

			echo "
				<tr>
					<td>$id</td>
					<td>$file</td>
					<td>$date</td>
					<th>
						<a 
							onClick=\"javascript: return confirm('Please confirm Data Restore');\" 
							href=\"databackups.php?restore_database=$id\" 
							class=\"btn btn-outline-secondary\"
						>Restore</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"databackups.php?delbackups=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
					</th>
                </tr>
			";
		}
	}



	function allRemarks(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM remarks";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$name</td>
					<th>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editRemarks$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"index.php?delRemarks=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
					</th>
                </tr>
			";
		}
	}

	function binandimporter(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM bins WHERE type = 'IMPORTER' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $name = $row['name']; $bin_num = $row['bin'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$name</td>
					<td>$bin_num</td>
                </tr>
			";
		}
	}


	function allLoadport($thisloadport = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thisloadport == "all") {$sql = "SELECT * FROM loadport";}
		else{$sql = "SELECT * FROM loadport WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $port_name = $row['port_name']; $port_code = $row['port_code'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$port_name</td>
					<td>$port_code</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editLoadport$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=loadport&delLoadport=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}


	function approveloadport(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM loadport WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $port_name = $row['port_name']; $port_code = $row['port_code'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$port_name</td>
					<td>$port_code</td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Loadport being approved!');\"
							href=\"usercontrols.php?page=loadportapproval&&aprvldprt=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editLoadport$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=loadport&delLoadport=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}


	function allNationality($thisnationality = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thisnationality == "all") {$sql = "SELECT * FROM nationality";}
		else{$sql = "SELECT * FROM nationality WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $port_name = $row['port_name']; $port_code = $row['port_code'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$port_name</td>
					<td>$port_code</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editNationality$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=nationality&delNationality=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}

	function approvenationality(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM nationality WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $port_name = $row['port_name']; $port_code = $row['port_code'];
			$companyid = $row['companyid']; $company = allData('company', $companyid, 'companyname');

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$port_name</td>
					<td>$port_code</td>
					<td>$company</td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Nationality Being Approved!');\"
							href=\"usercontrols.php?page=nationalityapproval&&aprvationality=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editNationality$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=nationality&delNationality=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}

	function allSurveycompany($thissurveycompany = ""){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		if ($thissurveycompany == "all") {$sql = "SELECT * FROM surveycompany";}
		else{$sql = "SELECT * FROM surveycompany WHERE companyid = '$companyid' ";}
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $company_name = $row['company_name']; 
			$contact_person = $row['email']; $contact_number = $row['officenum'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$company_name</td>
					<td>$contact_person</td>
					<td>$contact_number</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editSurveycompany$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=surveycompany&delSurveycompany=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}

	function surveycompanyapproval(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM surveycompany WHERE status = 'unapproved' ";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $company_name = $row['company_name']; 
			$contact_person = $row['email']; $contact_number = $row['officenum'];

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$company_name</td>
					<td>
						<a 
							onClick=\"javascript: return confirm('Survey Company Being Approved!');\"
							href=\"usercontrols.php?page=surveycompanyapproval&&aprvsurveycompany=$id\" 
							class=\"btn btn-outline-success\"
						>Approve</a>
					</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editSurveycompany$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"3rd_parties.php?page=surveycompany&delSurveycompany=$id\" 
							class=\"btn btn-outline-danger\"
						>Delete</a>
					</td>
				</tr>
			";
		}
	}


	function allBonds(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];
		 $sql = "SELECT * FROM prizebond";
		$run = mysqli_query($db, $sql); 
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; $ownerid = $row['owner']; $bond_num = $row['bond_num'];
			$owner = allData('users', $ownerid, 'name');$email = allData('users', $ownerid, 'email');

			echo "
				<tr>
					<th scope=\"row\">$id</th>
					<td>$owner</td>
					<td>$email</td>
					<td>$bond_num</td>
					<td>
						<a 
							href=\"#\" 
							class=\"btn btn-outline-secondary\"
							data-toggle=\"modal\" data-target=\"#editPrizeBond$id\"
						>Edit</a>
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\" 
							href=\"prizebond.php?del_bond=$id\" 
							class=\"btn btn-danger\"
						>Delete</a>
					</td>
                </tr>
			";
		}
	}

	function igm_format($vsl_num = 205){
		GLOBAL $db,$my,$company; $myid = $my['id']; $companyid = $my['companyid'];
		 $filename = ""; 
		// get bl data
        $row3=mysqli_fetch_assoc(mysqli_query($db,"SELECT * 
		FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
		$dep_date=$row3['issue_date'];$load_port=$row3['load_port'];
		$cargo_name=$row3['cargo_name'];
		$dep_day = dbtime($dep_date, "d"); $dep_month = dbtime($dep_date, "m"); $dep_year = dbtime($dep_date, "Y"); $port_name = allData('loadport', $load_port, 'port_name');
		$port_code = allData('loadport', $load_port, 'port_code');


		$t_qty = 0;
		$run4 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
		while ($raw4 = mysqli_fetch_assoc($run4)) {
			$t_qty += (float)$raw4['cargo_qty'];
		}
		$total_qty = formatIndianNumber($t_qty);
		$total_qty_kg = formatIndianNumber($t_qty*1000);

		// get vessel data
        $row1=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessels WHERE id='$vsl_num'"));
        $msl_num = $row1['msl_num']; $vessel = $row1['vessel_name']; $arr_date = $row1['arrived'];
        $year = date("Y"); $month = date("m"); $day = date("d");
        if (!isset($arr_date) || empty($arr_date)) { $vsl_year = $year; }
        else{$vsl_year = dbtime($arr_date, "Y");}


        // get ship_perticular
        $row=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessel_details WHERE vsl_num='$vsl_num'"));
        $vsl_imo = $row['vsl_imo']; 
        $vsl_nationalityid = $row['vsl_nationality']; 
        $vsl_nationality = allData('nationality', $vsl_nationalityid, 'port_name'); 
        $vsl_nationalitycode = allData('nationality', $vsl_nationalityid, 'port_code'); 
        $capt_name = $row['capt_name'];$vsl_grt = formatIndianNumber($row['vsl_grt']);$vsl_nrt = formatIndianNumber($row['vsl_nrt']);

        // get total packages
        $total_bl=mysqli_num_rows(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' "));
		
    	$exten = ".docx";$filename = "igm_format";
    	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("forwadings/templets/".$company['templet']."/others/".$filename.$exten);
    	$templateProcessor->setValues([
    		"msl_num" => "$msl_num",
			"vessel" => "$vessel",
			"vsl_imo" => "$vsl_imo",
			"vsl_nationality" => "$vsl_nationality",
			"vsl_nationalitycode" => "$vsl_nationalitycode",
			"vsl_grt" => "$vsl_grt",
			"vsl_nrt" => "$vsl_nrt",
			"capt_name" => "$capt_name",
			"port_name" => "$port_name",
			"port_code" => "$port_code",
			"dep_day" => "$dep_day",
			"dep_month" => "$dep_month",
			"dep_year" => "$dep_year",
			"total_bl" => "$total_bl",
			"cargo_name" => "$cargo_name",
			"total_qty" => "$total_qty",
			"total_qty_kg" => "$total_qty_kg",
			"vsl_year" => "$vsl_year",
			"year" => "$year",
			"month" => "$month"
    	]); 

		$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; 
		$save = $path.$filename." of ".$msl_num.".MV. ".$vessel.$exten;
		// Create folder if not exist, then save the file to that path
		createpath($path); $templateProcessor->saveAs($save);

		// create igm body
		$run4 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");

		// Create a new PHPWord Object
		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		// Define table style
		$tableStyle = [
		    'borderColor' => '999999',
		    'borderSize' => 6,
		    'cellMargin' => 80,  // Set cell margin to 80
		];
		// Define different widths for each column (in twips)
		$cellStyle1 = ['valign' => 'center', 'width' => 3000];  // First column width
		$cellStyle2 = ['valign' => 'center', 'width' => 200];   // Second column width (for ":")
		$cellStyle3 = ['valign' => 'center', 'width' => 6000];  // Third column width

		// Define text style with zero line spacing
		$textStyle = ['name' => 'Calibri', 'size' => 10];
		$textBlue = ['color' => '2D3BC9', 'name' => 'Calibri', 'size' => 10];
		$textGreen = ['color' => '28A745', 'name' => 'Calibri', 'size' => 10];
		$paragraphStyle = ['spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0];  // Line spacing set to 1 (zero additional spacing)

		// Create a new section
		$section = $phpWord->addSection();

		// Loop through the database results and create tables
		if (mysqli_num_rows($run4) > 0) {
		    while ($row5 = mysqli_fetch_assoc($run4)) {
		    	$importerId = $row5['receiver_name'];
		    	if (isset($importerId) && !empty($importerId)) {
		    		$importerbin = allData('bins', $importerId, 'bin');
					// $importername = allData('bins', $importerId, 'name');
					$importername = str_replace("&", "&amp;", allData('bins', $importerId, 'name'));
					$importer = $importerbin." (".$importername.")";
		    	}else{
		    		$importerbin = "IMPORTER BIN";
					$importername = "IMPORTER NAME";
					$importer = $importerbin." (".$importername.")";
		    	}
					

				$bankId = $row5['bank_name'];
				if (isset($bankId) && !empty($bankId)) {
					$bankbin = allData('bins', $bankId, 'bin');
					// $bankname = allData('bins', $bankId, 'name');
					$bankname = str_replace("&", "&amp;", allData('bins', $bankId, 'name'));
					$bank = $bankbin." (".$bankname.")";
				}else{
					$bankbin = "BANK BIN";
					$bankname = "BANK NAME";
					$bank = $bankbin." (".$bankname.")";
				}
					
				$shipper_name = $row5['shipper_name']; 
				$shipper_address = $row5['shipper_address'];

				// Split the string by newline
				$lines = explode("\n", $text);

		        // Add BL Number above the table, centered
		        $section->addText('Line No: '.$row5['line_num'].' | BL No: ' . $row5['bl_num'], ['bold' => true, 'size' => 14], ['align' => 'center','spaceBefore' => 3, 'spaceAfter' => 3]);

		        // Add table with AutoFit Window
		        $table = $section->addTable(array_merge($tableStyle, ['autofit' => 'window']));

		        // Add rows and cells
		        $table->addRow();
		        $table->addCell(null,$cellStyle1)->addText('Consignor Address', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
		        // $table->addCell(null,$cellStyle3)->addText($shipper, $textStyle, $paragraphStyle);
		        $cell = $table->addCell(null,$cellStyle3)->addTextRun();
		        $cell->addText($shipper_name, $textStyle, $paragraphStyle);
		        $cell->addTextBreak();
		        $cell->addText($shipper_address, $textStyle, $paragraphStyle);

		        $table->addRow();
		        $table->addCell(null,$cellStyle1)->addText('Notify (Receiver)', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle3)->addText($importer, $textBlue, $paragraphStyle);

		        $table->addRow();
		        $table->addCell(null,$cellStyle1)->addText('Importer/Consignee (Bank Name)', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle3)->addText($bank, $textGreen, $paragraphStyle);

		        $table->addRow();
		        $table->addCell(null,$cellStyle1)->addText('Manifested Gross Weight', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle3)->addText($row5['cargo_qty'], $textStyle, $paragraphStyle);

		        $table->addRow();
		        $table->addCell(null,$cellStyle1)->addText('Cargo name', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
		        $table->addCell(null,$cellStyle3)->addText($row5['cargo_name'], $textStyle, $paragraphStyle);

		        // Add space between tables
		        $section->addTextBreak();
				
		    }
		} else {$section->addText('No records found.');}

		// Save the document
		$filename = 'forwadings/auto_forwardings/'.$msl_num.'.MV. '.$vessel.'/igm_body.docx';
		createpath($path); $phpWord->save($filename, 'Word2007');

		// Close database connection
		$db->close();
		header("location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	}








	// function igmfullcargo($vsl_num = 205){
	// 	GLOBAL $db,$my,$company; $myid = $my['id']; $companyid = $my['companyid'];
	// 	 $filename = ""; 
	// 	// get bl data
    //     $row3=mysqli_fetch_assoc(mysqli_query($db,"SELECT * 
	// 	FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
	// 	$dep_date=$row3['issue_date'];$load_port=$row3['load_port'];
	// 	$cargo_name=$row3['cargo_name'];
	// 	$dep_day = dbtime($dep_date, "d"); $dep_month = dbtime($dep_date, "m"); $dep_year = dbtime($dep_date, "Y"); $port_name = allData('loadport', $load_port, 'port_name');
	// 	$port_code = allData('loadport', $load_port, 'port_code');


	// 	$t_qty = 0;
	// 	$run4 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	// 	while ($raw4 = mysqli_fetch_assoc($run4)) {
	// 		$t_qty += (float)$raw4['cargo_qty'];
	// 	}
	// 	$total_qty = formatIndianNumber($t_qty);
	// 	$total_qty_kg = formatIndianNumber($t_qty*1000);

	// 	// get vessel data
    //     $row1=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessels WHERE id='$vsl_num'"));
    //     $msl_num = $row1['msl_num']; $vessel = $row1['vessel_name']; $arr_date = $row1['arrived'];
    //     $year = date("Y"); $month = date("m"); $day = date("d");
    //     if (!isset($arr_date) || empty($arr_date)) { $vsl_year = $year; }
    //     else{$vsl_year = dbtime($arr_date, "Y");}


    //     // get ship_perticular
    //     $row=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessel_details WHERE vsl_num='$vsl_num'"));
    //     $vsl_imo = $row['vsl_imo']; 
    //     $vsl_nationalityid = $row['vsl_nationality']; 
    //     $vsl_nationality = allData('nationality', $vsl_nationalityid, 'port_name'); 
    //     $vsl_nationalitycode = allData('nationality', $vsl_nationalityid, 'port_code'); 
    //     $capt_name = $row['capt_name'];$vsl_grt = formatIndianNumber($row['vsl_grt']);$vsl_nrt = formatIndianNumber($row['vsl_nrt']);

    //     // get total packages
    //     $total_bl=mysqli_num_rows(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' "));
		
    // 	$exten = ".docx";$filename = "igm_format";
    // 	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("forwadings/templets/".$company['templet']."/others/".$filename.$exten);
    // 	$templateProcessor->setValues([
    // 		"msl_num" => "$msl_num",
	// 		"vessel" => "$vessel",
	// 		"vsl_imo" => "$vsl_imo",
	// 		"vsl_nationality" => "$vsl_nationality",
	// 		"vsl_nationalitycode" => "$vsl_nationalitycode",
	// 		"vsl_grt" => "$vsl_grt",
	// 		"vsl_nrt" => "$vsl_nrt",
	// 		"capt_name" => "$capt_name",
	// 		"port_name" => "$port_name",
	// 		"port_code" => "$port_code",
	// 		"dep_day" => "$dep_day",
	// 		"dep_month" => "$dep_month",
	// 		"dep_year" => "$dep_year",
	// 		"total_bl" => "$total_bl",
	// 		"cargo_name" => "$cargo_name",
	// 		"total_qty" => "$total_qty",
	// 		"total_qty_kg" => "$total_qty_kg",
	// 		"vsl_year" => "$vsl_year",
	// 		"year" => "$year",
	// 		"month" => "$month"
    // 	]); 

	// 	$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; 

	// 	// $save = $path.$filename." of ".$msl_num.".MV. ".$vessel.$exten;
	// 	// // Create folder if not exist, then save the file to that path
	// 	// createpath($path); $templateProcessor->saveAs($save);

	// 	// create igm body
	// 	$run4 = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");

	// 	// Create a new PHPWord Object
	// 	$phpWord = new \PhpOffice\PhpWord\PhpWord();

	// 	// Define table style
	// 	$tableStyle = [
	// 	    'borderColor' => '999999',
	// 	    'borderSize' => 6,
	// 	    'cellMargin' => 80,  // Set cell margin to 80
	// 	];
	// 	// Define different widths for each column (in twips)
	// 	$cellStyle1 = ['valign' => 'center', 'width' => 3000];  // First column width
	// 	$cellStyle2 = ['valign' => 'center', 'width' => 200];   // Second column width (for ":")
	// 	$cellStyle3 = ['valign' => 'center', 'width' => 6000];  // Third column width

	// 	// Define text style with zero line spacing
	// 	$textStyle = ['name' => 'Calibri', 'size' => 10];
	// 	$textBlue = ['color' => '2D3BC9', 'name' => 'Calibri', 'size' => 10];
	// 	$textGreen = ['color' => '28A745', 'name' => 'Calibri', 'size' => 10];
	// 	$paragraphStyle = ['spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0];  // Line spacing set to 1 (zero additional spacing)

	// 	// Create a new section
	// 	$section = $phpWord->addSection();

	// 	// Loop through the database results and create tables
	// 	if (mysqli_num_rows($run4) > 0) {
	// 	    while ($row5 = mysqli_fetch_assoc($run4)) {
	// 	    	$importerId = $row5['receiver_name'];
	// 	    	if (isset($importerId) && !empty($importerId)) {
	// 	    		$importerbin = allData('bins', $importerId, 'bin');
	// 				// $importername = allData('bins', $importerId, 'name');
	// 				$importername = str_replace("&", "&amp;", allData('bins', $importerId, 'name'));
	// 				$importer = $importerbin." (".$importername.")";
	// 	    	}else{
	// 	    		$importerbin = "IMPORTER BIN";
	// 				$importername = "IMPORTER NAME";
	// 				$importer = $importerbin." (".$importername.")";
	// 	    	}
					

	// 			$bankId = $row5['bank_name'];
	// 			if (isset($bankId) && !empty($bankId)) {
	// 				$bankbin = allData('bins', $bankId, 'bin');
	// 				// $bankname = allData('bins', $bankId, 'name');
	// 				$bankname = str_replace("&", "&amp;", allData('bins', $bankId, 'name'));
	// 				$bank = $bankbin." (".$bankname.")";
	// 			}else{
	// 				$bankbin = "BANK BIN";
	// 				$bankname = "BANK NAME";
	// 				$bank = $bankbin." (".$bankname.")";
	// 			}
					
	// 			$shipper_name = $row5['shipper_name']; 
	// 			$shipper_address = $row5['shipper_address'];

	// 			// Split the string by newline
	// 			$lines = explode("\n", $text);

	// 	        // Add BL Number above the table, centered
	// 	        // $section->addText('Line No: '.$row5['line_num'].' | BL No: ' . $row5['bl_num'], ['bold' => true, 'size' => 14], ['align' => 'center','spaceBefore' => 3, 'spaceAfter' => 3]);

	// 	        // Add table with AutoFit Window
	// 	        $table = $section->addTable(array_merge($tableStyle, ['autofit' => 'window']));

	// 	        // Add rows and cells
	// 	        $table->addRow();
	// 	        $table->addCell(null,$cellStyle1)->addText('sampletext', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
	// 	        // $table->addCell(null,$cellStyle3)->addText($shipper, $textStyle, $paragraphStyle);
	// 	        $cell = $table->addCell(null,$cellStyle3)->addTextRun();
	// 	        $cell->addText("sampletext", $textStyle, $paragraphStyle);
	// 	        $cell->addTextBreak();
	// 	        $cell->addText("sampletext", $textStyle, $paragraphStyle);

	// 	        $table->addRow();
	// 	        $table->addCell(null,$cellStyle1)->addText('sampletext', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle3)->addText("sampletext", $textBlue, $paragraphStyle);

	// 	        $table->addRow();
	// 	        $table->addCell(null,$cellStyle1)->addText('sampletext', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle3)->addText("sampletext", $textGreen, $paragraphStyle);

	// 	        $table->addRow();
	// 	        $table->addCell(null,$cellStyle1)->addText('sampletext', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle3)->addText("sampletext", $textStyle, $paragraphStyle);

	// 	        $table->addRow();
	// 	        $table->addCell(null,$cellStyle1)->addText('sampletext', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle2)->addText(':', $textStyle, $paragraphStyle);
	// 	        $table->addCell(null,$cellStyle3)->addText("sampletext", $textStyle, $paragraphStyle);

	// 	        // Add space between tables
	// 	        $section->addTextBreak();
				
	// 	    }
	// 	} else {$section->addText('No records found.');}

	// 	// Save the document
	// 	$filename = 'forwadings/auto_forwardings/'.$msl_num.'.MV. '.$vessel.'/igm_body.docx';
	// 	createpath($path); $phpWord->save($filename, 'Word2007');

	// 	// Close database connection
	// 	$db->close();
	// 	header("location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	// }

	// function igmfullcargo($vsl_num = 205) {
	//     GLOBAL $db, $my, $company;
	//     $myid = $my['id']; 
	//     $companyid = $my['companyid'];
	//     $company_name = $company['companyname'];

	//     // Get vessel info
	//     $row1 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels WHERE id='$vsl_num'"));
	//     $msl_num = $row1['msl_num'];
	//     $vessel = $row1['vessel_name'];

	//     $path = "forwadings/auto_forwardings/" . $msl_num . ".MV. " . $vessel . "/";
	//     createpath($path);

	//     require_once 'vendor/autoload.php';
	//     $phpWord = new \PhpOffice\PhpWord\PhpWord();

	//     // Styles
	//     $font = ['size' => 9, 'name' => 'Calibri'];
	//     $bold = ['bold' => true, 'size' => 9, 'name' => 'Calibri'];
	//     $phpWord->addTableStyle('IGMTable', [
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50
	//     ]);

	//     // Section setup: LANDSCAPE
	//     $section = $phpWord->addSection([
	//         'orientation' => 'landscape',
	//         'marginTop' => 600,
	//         'marginBottom' => 600,
	//         'marginLeft' => 600,
	//         'marginRight' => 600,
	//     ]);

	//     // --- Footer (not header) ---
	//     $footer = $section->addFooter();
	//     $footer->addText("Printed on " . date("d/m/Y H:i"), $font, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

	//     // // --- Title Block ---
	//     // $section->addText("Government of the People's Republic of Bangladesh", ['bold' => true, 'size' => 12]);
	//     // $section->addText("National Board of Revenue, Segunbagicha, Dhaka", ['bold' => true, 'size' => 12]);

	//     $section->addText("CARGO MANIFEST - Full Cargo Report", ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
	//     // $section->addTextBreak();

	    
	//     $vsldetails = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
	//     $runvsl = mysqli_fetch_assoc($vsldetails);
	//     $vsl_nationalityid = $runvsl['vsl_nationality'];
	//     $vsl_nationality = allData("nationality", $vsl_nationalityid, "port_name");


	//     $getvsl = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	//     $run1 = mysqli_fetch_assoc($getvsl);
	//     $voy = $run1['msl_num'];
	//     $registration = $run1['rotation'];
	//     $vessel_name = $run1['vessel_name'];
	    

	//     // make bl xml
	//     $row3=mysqli_fetch_assoc(mysqli_query($db,"SELECT * 
	// 	FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num' );"));
	//     $dep_date=$row3['issue_date'];  
	//     $dep_day = dbtime($dep_date, "d"); $dep_month = dbtime($dep_date, "m"); $dep_year = dbtime($dep_date, "y"); 
	//     $deperture_date = $dep_month."/".$dep_day."/".$dep_year;

	//     // make arrival date
	// 	$currentDate = new DateTime(); $currentDate->modify('+6 months');
	// 	$arrival_date = $currentDate->format('m/d/y'); // use

	// 	// --- Top-right Info Table ---
	//     $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])->addText(' ');
	//     $topTable = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'cellMargin' => 00,
	//         'borderSize' => 0,
	//         'borderColor' => '999999',
	//     ]);

	//     $topRows = [
	//         ['Voyage Number', $voy],
	//         ['Date of Deperture', $deperture_date],
	//         ['Date of Arrival', $arrival_date],
	//         ['Vessel Name', $vessel_name],
	//         ['Flag', $vsl_nationality],
	//         ['Shipping line', $company_name],
	//         ['Reg. Num', ''],
	//         ['Reg. Date', '']
	//     ];

	//     foreach ($topRows as $row) {
	//         $topTable->addRow();
	//         $topTable->addCell(2000)->addText($row[0], $bold);
	//         $topTable->addCell(2000)->addText($row[1], $font);
	//     }

	//     $section->addTextBreak(1);

	//     // --- Main IGM Table ---
	//     $table = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50
	//     ]);

	//     // Header Row
	//     $table->addRow();
	//     $table->addCell(1200)->addText("Loading Port", $bold);
	//     $table->addCell(700)->addText("Line", $bold);
	//     $table->addCell(1300)->addText("B/L Number<w:br/>Agents Code<w:br/>Agents Name", $bold);
	//     $table->addCell(2900)->addText("Shipper<w:br/>Consignee<w:br/>Notify<w:br/>No. of Containers", $bold);
	//     $table->addCell(1200)->addText("Container Number <w:br/> Seal Number <w:br/> E/F Type offdock", $bold);
	//     $table->addCell(400)->addText("Number and Type of Package", $bold);
	//     $table->addCell(3000)->addText("Description of Goods Shipping marks", $bold);
	//     $table->addCell(1000)->addText("DG Approval Status", $bold);
	//     $table->addCell(1000)->addText("BL Weight<w:br/>Ctn Weight", $bold);
	//     $table->addCell(1000)->addText("Cus. Value", $bold);

	//     $line_num = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while($row = mysqli_fetch_assoc($run)){
	//     	$line_num++;
	//     	$bl_num = $row['bl_num'];
	//     	$load_portid = $row['load_port'];
	//     	$port_code = allData("loadport",$load_portid,"port_code");
	//     	$load_port = allData("loadport",$load_portid,"port_name");
	//     	$shipper_name = $row['shipper_name'];
	//     	$bank_id = $row['bank_name'];
	//     	$bank_name = allData("bins", $bank_id, "name");
	//     	$receiverid = $row['receiver_name'];
	//     	$receiver = allData("bins", $receiverid, "name");
	//     	$cargo_qty = $row['cargo_qty'];

	//     	$table->addRow();
	//         $table->addCell(1200)->addText($port_code."<w:br/><w:br/>".$load_port, $font);
	//         $table->addCell(600)->addText($line_num, $font);
	//         $table->addCell(1000)->addText($bl_num, $font);
	//         $table->addCell(2900)->addText("SH: ".$shipper_name."<w:br/><w:br/>CN: ".$bank_name."<w:br/><w:br/>NY: ".$receiver, $font);
	//         $table->addCell(1200)->addText("", $font);
	//         $table->addCell(800)->addText("1", $font);
	//         $table->addCell(3000)->addText("SOYBEAN EXTRACTION (MEAL)", $font);
	//         $table->addCell(1000)->addText("", $font);
	//         $table->addCell(1000)->addText($cargo_qty, $font);
	//         $table->addCell(1000)->addText("0.0", $font);
	//     }

	//     // Save File
	//     $savePath = $path . "igm_fullcargo.docx";
	//     $phpWord->save($savePath, 'Word2007');

	//     header("Location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	// }

	// function igmfullcargo($vsl_num = 205) {
	//     GLOBAL $db, $my, $company;
	//     $myid = $my['id']; 
	//     $companyid = $my['companyid'];
	//     $company_name = $company['companyname'];

	//     // Get vessel info
	//     $row1 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels WHERE id='$vsl_num'"));
	//     $msl_num = $row1['msl_num'];
	//     $vessel = $row1['vessel_name'];

	//     $path = "forwadings/auto_forwardings/" . $msl_num . ".MV. " . $vessel . "/";
	//     createpath($path);

	//     require_once 'vendor/autoload.php';
	//     $phpWord = new \PhpOffice\PhpWord\PhpWord();

	//     // Styles
	//     $font = ['size' => 9, 'name' => 'Calibri'];
	//     $bold = ['bold' => true, 'size' => 9, 'name' => 'Calibri'];
	//     $phpWord->addTableStyle('IGMTable', [
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50
	//     ]);

	//     // Section setup: LANDSCAPE
	//     $section = $phpWord->addSection([
	//         'orientation' => 'landscape',
	//         'marginTop' => 600,
	//         'marginBottom' => 600,
	//         'marginLeft' => 600,
	//         'marginRight' => 600,
	//     ]);

	//     // --- Footer ---
	//     $footer = $section->addFooter();
	//     $footer->addText("Printed on " . date("d/m/Y H:i"), $font, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

	//     // Title
	//     $section->addText("CARGO MANIFEST - Full Cargo Report", ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

	//     // Vessel & voyage info
	//     $vsldetails = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
	//     $runvsl = mysqli_fetch_assoc($vsldetails);
	//     $vsl_nationalityid = $runvsl['vsl_nationality'];
	//     $vsl_nationality = allData("nationality", $vsl_nationalityid, "port_name");

	//     $getvsl = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	//     $run1 = mysqli_fetch_assoc($getvsl);
	//     $voy = $run1['msl_num'];
	//     $registration = $run1['rotation'];
	//     $vessel_name = $run1['vessel_name'];

	//     // Get max departure date
	//     $row3 = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num')"));
	//     $dep_date = $row3['issue_date'];  
	//     $dep_day = dbtime($dep_date, "d");
	//     $dep_month = dbtime($dep_date, "m");
	//     $dep_year = dbtime($dep_date, "y"); 
	//     $deperture_date = $dep_month."/".$dep_day."/".$dep_year;

	//     // Arrival date (dummy 6 months ahead)
	//     $currentDate = new DateTime(); 
	//     $currentDate->modify('+6 months');
	//     $arrival_date = $currentDate->format('m/d/y');

	//     // --- Top-right Info Table ---
	//     $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])->addText(' ');
	//     $topTable = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'cellMargin' => 0,
	//         'borderSize' => 0,
	//         'borderColor' => '999999',
	//     ]);

	//     $topRows = [
	//         ['Voyage Number', $voy],
	//         ['Date of Deperture', $deperture_date],
	//         ['Date of Arrival', $arrival_date],
	//         ['Vessel Name', $vessel_name],
	//         ['Flag', $vsl_nationality],
	//         ['Shipping line', $company_name],
	//         ['Reg. Num', ''],
	//         ['Reg. Date', '']
	//     ];

	//     foreach ($topRows as $row) {
	//         $topTable->addRow();
	//         $topTable->addCell(2000)->addText($row[0], $bold);
	//         $topTable->addCell(2000)->addText($row[1], $font);
	//     }

	//     // --- Summary Line Above Table ---
	//     $line_num = 0;
	//     $total_qty = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while ($row = mysqli_fetch_assoc($run)) {
	//         $line_num++;
	//         $total_qty += (float)$row['cargo_qty'];
	//     }
	//     $lines_per_page = 20;
	//     $total_pages = ceil($line_num / $lines_per_page);
	//     $total_qty_formatted = number_format($total_qty, 1);

	//     $section->addTextBreak(1);
	//     $summary = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
	//     $summary->addText("Total Number of: $line_num     ", $bold);
	//     $summary->addText("Total Gross: $total_qty_formatted     ", $bold);
	//     $summary->addText("Number of Pages: $total_pages", $bold);

	//     // --- Main IGM Table ---
	//     $table = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50
	//     ]);

	//     // Header Row
	//     $table->addRow();
	//     $table->addCell(1200)->addText("Loading Port", $bold);
	//     $table->addCell(700)->addText("Line", $bold);
	//     $table->addCell(1300)->addText("B/L Number<w:br/>Agents Code<w:br/>Agents Name", $bold);
	//     $table->addCell(2900)->addText("Shipper<w:br/>Consignee<w:br/>Notify<w:br/>No. of Containers", $bold);
	//     $table->addCell(1200)->addText("Container Number <w:br/> Seal Number <w:br/> E/F Type offdock", $bold);
	//     $table->addCell(400)->addText("Number and Type of Package", $bold);
	//     $table->addCell(3000)->addText("Description of Goods Shipping marks", $bold);
	//     $table->addCell(1000)->addText("DG Approval Status", $bold);
	//     $table->addCell(1000)->addText("BL Weight<w:br/>Ctn Weight", $bold);
	//     $table->addCell(1000)->addText("Cus. Value", $bold);

	//     // Data rows
	//     $line_num = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while ($row = mysqli_fetch_assoc($run)) {
	//         $line_num++;
	//         $bl_num = $row['bl_num'];
	//         $load_portid = $row['load_port'];
	//         $port_code = allData("loadport", $load_portid, "port_code");
	//         $load_port = allData("loadport", $load_portid, "port_name");
	//         $shipper_name = $row['shipper_name'];
	//         $bank_id = $row['bank_name'];
	//         $bank_name = allData("bins", $bank_id, "name");
	//         $receiverid = $row['receiver_name'];
	//         $receiver = allData("bins", $receiverid, "name");
	//         $cargo_qty = $row['cargo_qty'];

	//         $table->addRow();
	//         $table->addCell(1200)->addText($port_code . "<w:br/><w:br/>" . $load_port, $font);
	//         $table->addCell(600)->addText($line_num, $font);
	//         $table->addCell(1000)->addText($bl_num, $font);
	//         $table->addCell(2900)->addText("SH: $shipper_name<w:br/><w:br/>CN: $bank_name<w:br/><w:br/>NY: $receiver", $font);
	//         $table->addCell(1200)->addText("", $font);
	//         $table->addCell(800)->addText("1", $font);
	//         $table->addCell(3000)->addText("SOYBEAN EXTRACTION (MEAL)", $font);
	//         $table->addCell(1000)->addText("", $font);
	//         $table->addCell(1000)->addText($cargo_qty, $font);
	//         $table->addCell(1000)->addText("0.0", $font);
	//     }

	//     // Save File
	//     $savePath = $path . "igm_fullcargo.docx";
	//     $phpWord->save($savePath, 'Word2007');

	//     header("Location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	// }

	// function igmfullcargo($vsl_num = 205) {
	//     GLOBAL $db, $my, $company;
	//     $myid = $my['id']; 
	//     $companyid = $my['companyid'];
	//     $company_name = $company['companyname'];

	//     // Get vessel info
	//     $row1 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels WHERE id='$vsl_num'"));
	//     $msl_num = $row1['msl_num'];
	//     $vessel = $row1['vessel_name'];

	//     $path = "forwadings/auto_forwardings/" . $msl_num . ".MV. " . $vessel . "/";
	//     createpath($path);

	//     require_once 'vendor/autoload.php';
	//     $phpWord = new \PhpOffice\PhpWord\PhpWord();

	//     // Styles
	//     $font = ['size' => 9, 'name' => 'Calibri'];
	//     $bold = ['bold' => true, 'size' => 9, 'name' => 'Calibri'];
	//     $phpWord->addTableStyle('IGMTable', [
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50
	//     ]);

	//     // Section setup: LANDSCAPE
	//     $section = $phpWord->addSection([
	//         'orientation' => 'landscape',
	//         'marginTop' => 600,
	//         'marginBottom' => 600,
	//         'marginLeft' => 600,
	//         'marginRight' => 600,
	//     ]);

	//     // Footer
	//     $footer = $section->addFooter();
	//     $footer->addText("Printed on " . date("d/m/Y H:i"), $font, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

	//     // Title
	//     $section->addText("CARGO MANIFEST - Full Cargo Report", ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

	//     // Vessel details
	//     $vsldetails = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
	//     $runvsl = mysqli_fetch_assoc($vsldetails);
	//     $vsl_nationalityid = $runvsl['vsl_nationality'];
	//     $vsl_nationality = allData("nationality", $vsl_nationalityid, "port_name");

	//     $getvsl = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	//     $run1 = mysqli_fetch_assoc($getvsl);
	//     $voy = $run1['msl_num'];
	//     $registration = $run1['rotation'];
	//     $vessel_name = $run1['vessel_name'];

	//     // Departure date
	//     $row3 = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num')"));
	//     $dep_date = $row3['issue_date'];  
	//     $dep_day = dbtime($dep_date, "d");
	//     $dep_month = dbtime($dep_date, "m");
	//     $dep_year = dbtime($dep_date, "y"); 
	//     $deperture_date = $dep_month."/".$dep_day."/".$dep_year;

	//     // Arrival date (6 months ahead)
	//     $currentDate = new DateTime(); 
	//     $currentDate->modify('+6 months');
	//     $arrival_date = $currentDate->format('m/d/y');

	//     // --- Top-right Info Table ---
	//     $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])->addText(' ');
	//     $topTable = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'cellMargin' => 0,
	//         'borderSize' => 0,
	//         'borderColor' => '999999',
	//     ]);

	//     $topRows = [
	//         ['Voyage Number', $voy],
	//         ['Date of Deperture', $deperture_date],
	//         ['Date of Arrival', $arrival_date],
	//         ['Vessel Name', $vessel_name],
	//         ['Flag', $vsl_nationality],
	//         ['Shipping line', $company_name],
	//         ['Reg. Num', ''],
	//         ['Reg. Date', '']
	//     ];

	//     foreach ($topRows as $row) {
	//         $topTable->addRow();
	//         $topTable->addCell()->addText($row[0], $bold);
	//         $topTable->addCell()->addText($row[1], $font);
	//     }

	//     // Summary (line count & total cargo)
	//     $line_num = 0;
	//     $total_qty = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while ($row = mysqli_fetch_assoc($run)) {
	//         $line_num++;
	//         $total_qty += (float)$row['cargo_qty'];
	//     }
	//     $total_qty_formatted = number_format($total_qty, 1);

	//     $section->addTextBreak(1);

	//     $leftSummary = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
	//     $leftSummary->addText("Total Number of: $line_num", $bold);

	//     $rightSummary = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
	//     $rightSummary->addText("Total Gross: $total_qty_formatted", $bold);

	//     // --- Main IGM Table ---
	//     $table = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50
	//     ]);

	//     // Header row (repeat on each page)
	//     $table->addRow(null, ['tblHeader' => true]);
	//     $table->addCell()->addText("Loading Port", $bold);
	//     $table->addCell()->addText("Line", $bold);
	//     $table->addCell()->addText("B/L Number<w:br/>Agents Code<w:br/>Agents Name", $bold);
	//     $table->addCell()->addText("Shipper<w:br/>Consignee<w:br/>Notify<w:br/>No. of Containers", $bold);
	//     $table->addCell()->addText("Container Number<w:br/>Seal Number<w:br/>E/F Type offdock", $bold);
	//     $table->addCell()->addText("Number and Type of Package", $bold);
	//     $table->addCell()->addText("Description of Goods Shipping marks", $bold);
	//     $table->addCell()->addText("DG Approval Status", $bold);
	//     $table->addCell()->addText("BL Weight<w:br/>Ctn Weight", $bold);
	//     $table->addCell()->addText("Cus. Value", $bold);

	//     // Data rows
	//     $line_num = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while ($row = mysqli_fetch_assoc($run)) {
	//         $line_num++;
	//         $bl_num = $row['bl_num'];
	//         $load_portid = $row['load_port'];
	//         $port_code = allData("loadport", $load_portid, "port_code");
	//         $load_port = allData("loadport", $load_portid, "port_name");
	//         $shipper_name = $row['shipper_name'];
	//         $bank_id = $row['bank_name'];
	//         $bank_name = allData("bins", $bank_id, "name");
	//         $receiverid = $row['receiver_name'];
	//         $receiver = allData("bins", $receiverid, "name");
	//         $cargo_qty = $row['cargo_qty'];

	//         $table->addRow();
	//         $table->addCell()->addText($port_code . "<w:br/><w:br/>" . $load_port, $font);
	//         $table->addCell()->addText($line_num, $font);
	//         $table->addCell()->addText($bl_num, $font);
	//         $table->addCell()->addText("SH: $shipper_name<w:br/><w:br/>CN: $bank_name<w:br/><w:br/>NY: $receiver", $font);
	//         $table->addCell()->addText("", $font);
	//         $table->addCell()->addText("1", $font);
	//         $table->addCell()->addText("SOYBEAN EXTRACTION (MEAL)", $font);
	//         $table->addCell()->addText("", $font);
	//         $table->addCell()->addText($cargo_qty, $font);
	//         $table->addCell()->addText("0.0", $font);
	//     }

	//     // Save File
	//     $savePath = $path . "igm_fullcargo.docx";
	//     $phpWord->save($savePath, 'Word2007');

	//     header("Location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	// }

	// function igmfullcargo($vsl_num = 205) {
	//     GLOBAL $db, $my, $company;
	//     $myid = $my['id']; 
	//     $companyid = $my['companyid'];
	//     $company_name = $company['companyname'];

	//     // Get vessel info
	//     $row1 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels WHERE id='$vsl_num'"));
	//     $msl_num = $row1['msl_num'];
	//     $vessel = $row1['vessel_name'];

	//     $path = "forwadings/auto_forwardings/" . $msl_num . ".MV. " . $vessel . "/";
	//     createpath($path);

	//     require_once 'vendor/autoload.php';
	//     $phpWord = new \PhpOffice\PhpWord\PhpWord();

	//     // Styles
	//     $font = ['size' => 9, 'name' => 'Calibri'];
	//     $bold = ['bold' => true, 'size' => 9, 'name' => 'Calibri'];

	//     $phpWord->addTableStyle('IGMTable', [
	//         'borderSize' => 0,
	//         'borderColor' => '000000',
	//         'cellMargin' => 50,
	//         'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
	//     ]);

	//     // Section: Landscape
	//     $section = $phpWord->addSection([
	//         'orientation' => 'landscape',
	//         'marginTop' => 600,
	//         'marginBottom' => 600,
	//         'marginLeft' => 600,
	//         'marginRight' => 600,
	//     ]);

	//     // Footer
	//     $footer = $section->addFooter();
	//     $footer->addText("Printed on " . date("d/m/Y H:i"), $font, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

	//     // Title
	//     $section->addText("CARGO MANIFEST - Full Cargo Report", ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

	//     // Vessel details
	//     $vsldetails = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
	//     $runvsl = mysqli_fetch_assoc($vsldetails);
	//     $vsl_nationalityid = $runvsl['vsl_nationality'];
	//     $vsl_nationality = allData("nationality", $vsl_nationalityid, "port_name");

	//     $getvsl = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	//     $run1 = mysqli_fetch_assoc($getvsl);
	//     $voy = $run1['msl_num'];
	//     $registration = $run1['rotation'];
	//     $vessel_name = $run1['vessel_name'];

	//     // Departure date
	//     $row3 = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num')"));
	//     $dep_date = $row3['issue_date'];  
	//     $dep_day = dbtime($dep_date, "d");
	//     $dep_month = dbtime($dep_date, "m");
	//     $dep_year = dbtime($dep_date, "y"); 
	//     $deperture_date = $dep_month."/".$dep_day."/".$dep_year;

	//     // Arrival date (dummy)
	//     $currentDate = new DateTime(); 
	//     $currentDate->modify('+6 months');
	//     $arrival_date = $currentDate->format('m/d/y');

	//     // --- Top-right Info Table ---
	//     $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])->addText(' ');
	//     $topTable = $section->addTable([
	//         'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	//         'cellMargin' => 0,
	//         'borderSize' => 0,
	//         'borderColor' => '999999',
	//     ]);

	//     $topRows = [
	//         ['Voyage Number', $voy],
	//         ['Date of Deperture', $deperture_date],
	//         ['Date of Arrival', $arrival_date],
	//         ['Vessel Name', $vessel_name],
	//         ['Flag', $vsl_nationality],
	//         ['Shipping line', $company_name],
	//         ['Reg. Num', ''],
	//         ['Reg. Date', '']
	//     ];

	//     foreach ($topRows as $row) {
	//         $topTable->addRow();
	//         $topTable->addCell()->addText($row[0], $bold);
	//         $topTable->addCell()->addText($row[1], $font);
	//     }

	//     // --- Summary line ---
	//     $line_num = 0;
	//     $total_qty = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while ($row = mysqli_fetch_assoc($run)) {
	//         $line_num++;
	//         $total_qty += (float)$row['cargo_qty'];
	//     }
	//     $total_qty_formatted = number_format($total_qty, 1);

	//     $section->addTextBreak(1);
	//     $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT])
	//             ->addText("Total Number of: $line_num", $bold);
	//     $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])
	//             ->addText("Total Gross: $total_qty_formatted", $bold);

	//     // --- Main Table ---
	//     $table = $section->addTable('IGMTable');

	//     // Header (repeat on page break)
	//     $table->addRow(null, ['tblHeader' => true]);
	//     $table->addCell(1200)->addText("Loading Port", $bold);
	//     $table->addCell(800)->addText("Line", $bold); // widened
	//     $table->addCell(1300)->addText("B/L Number<w:br/>Agents Code<w:br/>Agents Name", $bold);
	//     $table->addCell(2800)->addText("Shipper<w:br/>Consignee<w:br/>Notify<w:br/>No. of Containers", $bold);
	//     $table->addCell(1200)->addText("Container Number<w:br/>Seal Number<w:br/>E/F Type offdock", $bold);
	//     $table->addCell(600)->addText("Number and Type of Package", $bold); // shrunk
	//     $table->addCell(3000)->addText("Description of Goods Shipping marks", $bold);
	//     $table->addCell(1000)->addText("DG Approval Status", $bold);
	//     $table->addCell(1000)->addText("BL Weight<w:br/>Ctn Weight", $bold);
	//     $table->addCell(1000)->addText("Cus. Value", $bold);

	//     // Data rows
	//     $line_num = 0;
	//     $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	//     while ($row = mysqli_fetch_assoc($run)) {
	//         $line_num++;
	//         $bl_num = $row['bl_num'];
	//         $load_portid = $row['load_port'];
	//         $port_code = allData("loadport", $load_portid, "port_code");
	//         $load_port = allData("loadport", $load_portid, "port_name");
	//         $shipper_name = $row['shipper_name'];
	//         $bank_id = $row['bank_name'];
	//         $bank_name = allData("bins", $bank_id, "name");
	//         $receiverid = $row['receiver_name'];
	//         $receiver = allData("bins", $receiverid, "name");
	//         $cargo_qty = $row['cargo_qty'];

	//         $table->addRow();
	//         $table->addCell(1200)->addText($port_code . "<w:br/><w:br/>" . $load_port, $font);
	//         $table->addCell(800)->addText($line_num, $font);
	//         $table->addCell(1300)->addText($bl_num, $font);
	//         $table->addCell(2800)->addText("SH: $shipper_name<w:br/><w:br/>CN: $bank_name<w:br/><w:br/>NY: $receiver", $font);
	//         $table->addCell(1200)->addText("", $font);
	//         $table->addCell(600)->addText("1", $font);
	//         $table->addCell(3000)->addText("SOYBEAN EXTRACTION (MEAL)", $font);
	//         $table->addCell(1000)->addText("", $font);
	//         $table->addCell(1000)->addText($cargo_qty, $font);
	//         $table->addCell(1000)->addText("0.0", $font);
	//     }

	//     // Save DOCX
	//     $savePath = $path . "igm_fullcargo.docx";
	//     $phpWord->save($savePath, 'Word2007');

	//     header("Location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	// }

	function igmfullcargo($vsl_num = 205) {
	    GLOBAL $db, $my, $company;
	    $myid = $my['id']; 
	    $companyid = $my['companyid'];
	    $company_name = $company['companyname'];

	    // Get vessel info
	    $row1 = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM vessels WHERE id='$vsl_num'"));
	    $msl_num = $row1['msl_num'];
	    $vessel = $row1['vessel_name'];

	    $path = "forwadings/auto_forwardings/" . $msl_num . ".MV. " . $vessel . "/";
	    createpath($path);

	    require_once 'vendor/autoload.php';
	    $phpWord = new \PhpOffice\PhpWord\PhpWord();

	    // Styles
	    $font = ['size' => 9, 'name' => 'Calibri'];
	    $bold = ['bold' => true, 'size' => 9, 'name' => 'Calibri'];

	    $phpWord->addTableStyle('IGMTable', [
	        'borderSize' => 0,
	        'borderColor' => '000000',
	        'cellMargin' => 50,
	        'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
	    ]);

	    // Section: Landscape
	    $section = $phpWord->addSection([
	        'orientation' => 'landscape',
	        'marginTop' => 600,
	        'marginBottom' => 600,
	        'marginLeft' => 600,
	        'marginRight' => 600,
	    ]);

	    // Footer
	    $footer = $section->addFooter();
	    $footer->addText("Printed on " . date("d/m/Y H:i"), $font, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

	    // Title
	    $section->addText("CARGO MANIFEST - Full Cargo Report", ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

	    // Vessel details
	    $vsldetails = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
	    $runvsl = mysqli_fetch_assoc($vsldetails);
	    $vsl_nationalityid = $runvsl['vsl_nationality'];
	    $vsl_nationality = allData("nationality", $vsl_nationalityid, "port_name");

	    $getvsl = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	    $run1 = mysqli_fetch_assoc($getvsl);
	    $voy = $run1['msl_num'];
	    $registration = $run1['rotation'];
	    $vessel_name = $run1['vessel_name'];

	    // Departure date
	    $row3 = mysqli_fetch_assoc(mysqli_query($db,"SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND issue_date = (SELECT MAX(issue_date) FROM vessels_bl WHERE vsl_num = '$vsl_num')"));
	    $dep_date = $row3['issue_date'];  
	    $dep_day = dbtime($dep_date, "d");
	    $dep_month = dbtime($dep_date, "m");
	    $dep_year = dbtime($dep_date, "y"); 
	    $deperture_date = $dep_month."/".$dep_day."/".$dep_year;

	    // Arrival date (dummy)
	    $currentDate = new DateTime(); 
	    $currentDate->modify('+6 months');
	    $arrival_date = $currentDate->format('m/d/y');

	    // --- Top-right Info Table ---
	    $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])->addText(' ');
	    $topTable = $section->addTable([
	        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
	        'cellMargin' => 0,
	        'borderSize' => 0,
	        'borderColor' => '999999',
	    ]);

	    $topRows = [
	        ['Voyage Number', $voy],
	        ['Date of Deperture', $deperture_date],
	        ['Date of Arrival', $arrival_date],
	        ['Vessel Name', $vessel_name],
	        ['Flag', $vsl_nationality],
	        ['Shipping line', $company_name],
	        ['Reg. Num', ''],
	        ['Reg. Date', '']
	    ];

	    foreach ($topRows as $row) {
	        $topTable->addRow();
	        $topTable->addCell()->addText($row[0], $bold);
	        $topTable->addCell()->addText($row[1], $font);
	    }

	    // --- Summary line (one row, left & right)
	    $line_num = 0;
	    $total_qty = 0;
	    $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	    while ($row = mysqli_fetch_assoc($run)) {
	        $line_num++;
	        $total_qty += (float)$row['cargo_qty'];
	    }
	    // $total_qty_formatted = number_format($total_qty, 1);
	    $total_qty_formatted = formatInternationalNumber(ttlcargoqty($vsl_num)*1000,1);
		// $total_cargo_qty = formatInternationalNumber($ttlqty*1000);

	    $section->addTextBreak(1);
	    $summaryTable = $section->addTable(['borderSize' => 0, 'cellMargin' => 0]);
	    $summaryTable->addRow();
	    $summaryTable->addCell(7200)->addText("Total Number of: $line_num", $bold);
	    $summaryTable->addCell(7200)->addText("Total Gross: $total_qty_formatted", $bold, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

	    // --- Main Table ---
	    $table = $section->addTable('IGMTable');

	    // Header (repeat on page break)
	    $table->addRow(null, ['tblHeader' => true]);
	    $table->addCell(1320)->addText("Loading Port", $bold);
	    $table->addCell(880)->addText("Line", $bold);
	    $table->addCell(1430)->addText("B/L Number<w:br/>Agents Code<w:br/>Agents Name", $bold);
	    $table->addCell(3080)->addText("Shipper<w:br/>Consignee<w:br/>Notify<w:br/>No. of Containers", $bold);
	    $table->addCell(1320)->addText("Container Number<w:br/>Seal Number<w:br/>E/F Type offdock", $bold);
	    $table->addCell(660)->addText("Number and Type of Package", $bold);
	    $table->addCell(3300)->addText("Description of Goods Shipping marks", $bold);
	    $table->addCell(1100)->addText("DG Approval Status", $bold);
	    $table->addCell(1100)->addText("BL Weight<w:br/>Ctn Weight", $bold);
	    $table->addCell(1100)->addText("Cus. Value", $bold);

	    // Data rows
	    $line_num = 0;
	    $run = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	    while ($row = mysqli_fetch_assoc($run)) {
	        $line_num++;
	        $bl_num = $row['bl_num'];
	        $load_portid = $row['load_port'];
	        $port_code = allData("loadport", $load_portid, "port_code");
	        $load_port = allData("loadport", $load_portid, "port_name");
	        $shipper_name = $row['shipper_name'];
	        $bank_id = $row['bank_name'];
	        $bank_name = allData("bins", $bank_id, "name");
	        $receiverid = $row['receiver_name'];
	        $receiver = allData("bins", $receiverid, "name");
	        $cargo_qty = formatInternationalNumber($row['cargo_qty']*1000,1);

	        $table->addRow();
	        $table->addCell(1320)->addText($port_code . "<w:br/><w:br/>" . $load_port, $font);
	        $table->addCell(880)->addText($line_num, $font);
	        $table->addCell(1430)->addText($bl_num, $font);
	        $table->addCell(3080)->addText("SH: $shipper_name<w:br/><w:br/>CN: $bank_name<w:br/><w:br/>NY: $receiver", $font);
	        $table->addCell(1320)->addText("", $font);
	        $table->addCell(660)->addText("1", $font);
	        $table->addCell(3300)->addText("SOYBEAN EXTRACTION (MEAL)", $font);
	        $table->addCell(1100)->addText("", $font);
	        $table->addCell(1100)->addText($cargo_qty, $font);
	        $table->addCell(1100)->addText("0.0", $font);
	    }

	    // Save DOCX
	    $savePath = $path . "igm_fullcargo.docx";
	    $phpWord->save($savePath, 'Word2007');

	    header("Location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	}















	function do_format($doId = 1){
		GLOBAL $db,$my,$company; $myid = $my['id']; $companyid = $my['companyid'];
		 $filename = ""; $companyname = $company['companyname'];

		// get vessels_bl data
		$row2=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessels_bl WHERE id='$doId'"));
		$vsl_num = $row2['vsl_num'] ;$c_cargoname = $row2['c_cargoname']; $c_cnfid = $row2['cnf_name'];
		$c_num = $row2['c_num']; $c_date = date('d/m/Y', strtotime($row2['c_date']));
		$c_cnfname = allData('cnf', $c_cnfid, 'name');
		$c_cnfname = str_replace("&", "&amp;", $c_cnfname); 
		$c_importerid = $row2['receiver_name'];
		$c_importername = str_replace("&", "&amp;", allData('bins', $c_importerid, 'name'));
		$bl_num = $row2['bl_num'];
		$c_qty = $row2['cargo_qty']; $c_cargoqty = formatIndianNumber($c_qty); 
		$qty_inwords = strtoupper(numberToWords($c_qty)); 
		$line_num = $row2['line_num']; $linenum_inwords = strtoupper(numberToWords($line_num));

		
		// get vessel data
        $row1=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessels WHERE id='$vsl_num'"));
        $msl_num = $row1['msl_num']; $vessel = $row1['vessel_name']; $rotation = $row1['rotation']; $arr_date = $row1['arrived'];


        $year = date("Y"); $month = date("m"); $day = date("d");
        $vsl_year = dbtime($arr_date, "Y");
        $vsl_month = dbtime($arr_date, "m");
        $vsl_day = dbtime($arr_date, "d");
        $arrived = dbtime($arr_date, "d.m.Y");


        // get ship_perticular
        $row=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessel_details WHERE vsl_num='$vsl_num'"));
        $vsl_cargo = $row['vsl_cargo'];
		
    	$exten = ".docx";$filename = "DO".$exten; $filenameraw = "DO";
    	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("forwadings/templets/".$company['templet']."/others/".$filename);
    	$templateProcessor->setValues([
    		"msl_num" => "$msl_num",
			"companyname" => "$companyname",
			"vessel" => "$vessel",
			"rotation" => "$rotation",
			"bl_num" => "$bl_num",
			"line_num" => "$line_num",
			"linenum_inwords" => "$linenum_inwords",
			"c_cargoname" => "$c_cargoname",
			"c_cargoqty" => "$c_cargoqty",
			"qty_inwords" => "$qty_inwords",
			"c_num" => "$c_num",
			"c_date" => "$c_date",
			"c_cnfname" => "$c_cnfname",
			"c_importername" => "$c_importername",
			"vsl_cargo" => "$vsl_cargo",
			"arrived" => "$arrived",
			"vsl_year" => "$vsl_year",
			"vsl_month" => "$vsl_month",
			"vsl_day" => "$vsl_day",
			"year" => "$year",
			"month" => "$month",
			"day" => "$day"
    	]); 
		$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel."/"; 
		$save = $path.$filenameraw." OF OBL NO ".$bl_num." MV. ".$vessel.$exten;
		// Create folder if not exist, then save the file to that path
		createpath($path); $templateProcessor->saveAs($save);
		// Check if the file exists
		header("location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	}


	function vsl_bl($vsl_num = "100"){
		GLOBAL $db, $my, $company;
		
		$sql = "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ";
		// show dynamicsql
		$run = mysqli_query($db, $sql); $ctgqty = $retention_qty = $total = 0;
		while ($row = mysqli_fetch_assoc($run)) {

			$id = $row['id']; //
			$line_num = $row['line_num']; //
			$bl_num = $row['bl_num']; // 1
			$cargo_name = $row['cargo_name']; // 1
			$cargo_qty = $row['cargo_qty']; // 1
			$loadPortId = $row['load_port']; // 1
			$desc_portId = $row['desc_port']; // 1
			$load_port = allData('loadport', $loadPortId, 'port_name');
			$port_code = allData('loadport', $loadPortId, 'port_code');
			if ($desc_portId == $company['port']) {$ctgqty = $ctgqty + $cargo_qty;}
			else{$retention_qty = $retention_qty + $cargo_qty;}
			$total = $total+$cargo_qty;

			$cargokeyId = $row['cargokeyId']; //
            $receiverId = $row['receiver_name'];
            $bankId = $row['bank_name'];
            $shipper_name = $row['shipper_name'];
            $shipper_address = $row['shipper_address'];
            $issue_date = dbtimefotmat('Y-m-d', $row['issue_date'], 'd/m/Y');


            $percent = $count = 0;

			if(!empty($bl_num)){ $count++; }
			if(!empty($cargo_name)){ $count++; }
			if(!empty($cargo_qty)){ $count++; }
			if(!empty($loadPortId)){ $count++; }
			if(!empty($desc_portId)){ $count++; }

			if(!empty($cargokeyId)){ $count++; }
			if(!empty($receiverId)){ $count++; }
			if(!empty($bankId)){ $count++; }
			if(!empty($shipper_name)){ $count++; }
			if(!empty($shipper_address)){ $count++; }
			if(!empty($issue_date)){ $count++; }

			$per = (100/11) * $count;
			$percent = number_format((float)$per, 0, '.', '')."% ";

			if ($percent == 100) {$percent = "";}
			

			echo "
				<tr>
					<th scope=\"row\">$line_num</th>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editBlInput$id\"
						>
							$bl_num
						</a>
					</td>
					<td>$cargo_name</td>
					
					<td style=\"text-align:left;\">
						$load_port [ $port_code ]
					</td>
					<td>$cargo_qty MT</td>
					";

					// useraccess for vessel_ctrl
        			if (allData('useraccess',$my['office_position'],'vessel_ctrl')){
					echo"<td scope=\"col\">
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editBlInput$id\"
						>
							".$percent." <span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i> </span>
						</a>
						|
						<a 
							onClick=\"javascript: return confirm('Please confirm deletion');\"
							href=\"vessel_details.php?blinputs=$vsl_num&&bldelete=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-trash\"></i> </span>
						</a>
					</td>";
					}

                echo"</tr>
			";
		}
		echo "
			<tr>

				<td scope=\"row\" colspan=\"6\" style=\"text-align:right;\">
					Ctg Qty: &nbsp;".formatIndianNumberNew($ctgqty)." MT  &nbsp; &nbsp; &nbsp;
					Retention: &nbsp;".formatIndianNumberNew($retention_qty)." MT  &nbsp; &nbsp; &nbsp;
					Total: &nbsp;".formatIndianNumberNew($total)." MT
				</td>
            </tr>
		";
	}









	function vsl_do($vsl_num = "100"){
		GLOBAL $db,$my; 
		
		$sql = "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ";
		// show dynamicsql
		$run = mysqli_query($db, $sql); $total = 0;
		while ($row = mysqli_fetch_assoc($run)) {
			$id = $row['id']; //
			$line_num = $row['line_num']; //
			$bl_num = $row['bl_num']; //
			$cargo_name = $row['cargo_name']; //
			if (empty($row['c_cargoname'])) {$c_cargoname = $cargo_name;}
             else{$c_cargoname = $row['c_cargoname'];}
			$cargo_qty = $row['cargo_qty']; //
			$c_num = $row['c_num']; //
			$loadPortId = $row['load_port'];
			$load_port = allData('loadport', $loadPortId, 'port_name');
			$port_code = allData('loadport', $loadPortId, 'port_code');
			$total = $total+$cargo_qty;
			

			echo "
			<form method=\"post\" action=\"".pagename().pageurl()."\">
				<tr>
					<th scope=\"row\">$line_num</th>
					<td>
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editDoInput$id\"
						>
							$bl_num
						</a>
					</td>
					<td>$c_cargoname</td>
					
					<td style=\"text-align:left;\">
						$c_num
					</td>
					<td>$cargo_qty MT</td>
					<td style=\"text-align: center;\"><input type=\"checkbox\" name=\"multipledo[]\" value=\"$id\" /></td>
					";
					// useraccess for vessel_ctrl
        			if (allData('useraccess',$my['office_position'],'vessel_ctrl')){
					echo"<td scope=\"col\">
						<a 
							href=\"#\" 
							style=\"text-decoration: none; padding: 5px;\"
							data-toggle=\"modal\" data-target=\"#editDoInput$id\"
						>
							<span style=\"padding: 5px;\"><i class=\"bi bi-pencil\"></i> </span>
						</a>
						|
						<a 
							onClick=\"javascript: return confirm('Export DO of bl no: ".$bl_num." ?');\"
							href=\"vessel_details.php?doinputs=$vsl_num&&exportdo=$id\" 
							style=\"text-decoration: none; padding: 5px;\"
						>
							<span style=\"padding: 5px;\"><i class=\"icon icon-log-out-1\"></i> </span>
						</a>
					</td>";
					}
                echo"</tr>
			";
		}
		echo "
			<tr>
				<th scope=\"row\" colspan=\"4\" style=\"text-align:right;\">Total: &nbsp;</th>
				<td colspan=\"1\">
					<a href=\"vessel_details.php?edit=$vsl_num\">
						".formatIndianNumber($total)." MT
					</a>
				</td>
				<td colspan=\"2\">
					<button type=\"submit\" name=\"multipledoexport\" class=\"btn btn-sm btn-secondary\">
						<i class=\"icon icon-log-out-1\"></i> Export Selected
					</button>
				</td>
            </tr>
            </form>
		";
	}














	function stevedorewise(){
		GLOBAL $db,$my; $myid = $my['id']; $companyid = $my['companyid'];

		$sql = "SELECT * FROM stevedore ";
		$run = mysqli_query($db, $sql); 
		$serial = 0;
		while ($row = mysqli_fetch_assoc($run)) {

			$stevedoreid = $row['id']; $stevedore = $row['name'];
			$sql2 = "SELECT * FROM vessels WHERE stevedore = '$stevedoreid' AND STR_TO_DATE(rcv_date, '%d-%m-%Y') BETWEEN '2024-01-01' AND '2024-12-31' ";
			$run2 = mysqli_query($db, $sql2);
			$count = mysqli_num_rows($run2);
			if ($count == 0) {
				continue;
			}
			$serial++;
			// echo "
			// 	<tr>
			// 		<td colspan=\"3\">
			// 			$stevedore ($count)
			// 		</td>
			// 	</tr>
			// ";
			echo "
				<tr style=\"border: 1px solid #dee2e6 !important;\">
					<td style=\"border: 1px solid #dee2e6; border-right: none;\" rowspan=\"$count\">$serial</td>
					<td style=\"border: 1px solid #dee2e6; border-right: none;\" rowspan=\"$count\">$stevedore ($count)</td>
			"; 
			
			while ($row2 = mysqli_fetch_assoc($run2)) {
				// $vslid = $run2['id'];
				$vsl_num = $row2['vsl_num'];
				$msl_num = $row2['msl_num']; //
				$vessel_name = $row2['vessel_name']; //
				$qty = (float)ttlcargoqty($vsl_num, "total");
				echo"
					
						<td scope=\"row\" style=\"border: 1px solid #dee2e6; border-right: none;\">$msl_num</th>
						<td style=\"border: 1px solid #dee2e6; border-right: none;\">
							<a href=\"vessel_details.php?edit=$vsl_num\">
								MV.$vessel_name
							</a>
						</td>
						<td style=\"border: 1px solid #dee2e6;\">$qty MT</td>
					</tr>
				";
			}
			echo "
				</tr>
			";
		}
	}








	// igm xml
	function igm_xml($vsl_num){
		GLOBAL $db,$my,$company; 
		$myid = $my['id']; $companyid = $company['id']; $companyain = $company['ain'];

		// make general segment xml
		// Define vessel id (as per your example)
	    $vessel_id = $_POST['vsl_num']; $vsl_num = $_POST['vsl_num'];

	    $vessels = mysqli_query($db, "SELECT * FROM vessels WHERE id = '$vsl_num' ");
	    $ship_perticular = mysqli_query($db, "SELECT * FROM vessel_details WHERE vsl_num = '$vsl_num' ");
	    $vessels_bl = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");

	    $vessel_row = mysqli_fetch_assoc($vessels);
	    // Prepare dynamic variables
	    $vsl_num = $vessel_row['id'];
	    $msl_num = $vessel_row['msl_num'];
	    $arrival_date = $vessel_row['arrived'];
	    $vessel_name = $vessel_row['vessel_name'];

	    $path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel_name."/igm/"; 
	    // create path if not exist
	    createpath($path);
	    
	    $vessel_details_row = mysqli_fetch_assoc($ship_perticular);

	    $capt_name = $vessel_details_row['capt_name']; // use

	    $vsl_nationalityid = $vessel_details_row['vsl_nationality'];
	    $vsl_nationalitycode = allData('nationality', $vsl_nationalityid, 'port_code');

	    $vsl_imo = $vessel_details_row['vsl_imo'];
	    $vsl_grt = $vessel_details_row['vsl_grt'];
	    $vsl_nrt = $vessel_details_row['vsl_nrt'];

	    if (empty($vessel_details_row['vsl_nrt'])) {$packages_codes = "VR";}
	    else{$packages_codes = $vessel_details_row['packages_codes'];}

	    $company_port = $company['port'];
	    $total_bls = mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' AND desc_port = '$company_port' ")); 

	    // $t_qty = $total_cargo_qty = 0; 
	    // while ($bl_row = mysqli_fetch_assoc($vessels_bl)) { $t_qty += $bl_row['cargo_qty']; }
	    // $total_cargo_qty = $t_qty * 1000;
	    $total_cargo_qty = ttlcargoqty($vsl_num, 'ctg')*1000;
	    $company_portcode = allData('loadport', $company['port'], 'port_code');

	    switch ($company_portcode) {
	    	case 'BDCGP':
	    		$customcode = 301;
	    		break;

	    	case 'BDMGL':
	    		$customcode = 501;
	    		break;
	    	
	    	default:
	    		$customcode = 301;
	    		break;
	    }



	    // make generalsegment xml
	    $deperture_date = deperture_date($vsl_num);
	    $last_portid = deperture_info($vsl_num, "last_portid"); 
	    $last_portcode = allData('loadport', $last_portid, 'port_code');

		// // make arrival date
		// // condition 1: 6 Month ahead from current date.
		// $currentDate = new DateTime(); 
		// $currentDate->modify('+6 months');
		

		// // condition 2: minimum 7 day ahead from deperture date
		// $deperture_day = dbtime($deperture_date, "d");
		// $arrival_day = dbtime($currentDate->format('m/d/y'), "d");
		// $current_day = date("d");
		// if ($deperture_day < $arrival_day || $deperture_day == $arrival_day) {
		// 	$diff_ck = $arrival_day - $deperture_day; if ($diff_ck < 8) {
		// 		$increase = 8 - $diff_ck + $arrival_day;
		// 		$temparrival_date = $currentDate->modify('+'.$increase.' days');
		// 	}
		// }else{
		// 	$diff_ck = $deperture_day - $arrival_day; 
		// 	$increase = $arrival_day + $diff_ck + 8;
		// 	$temparrival_date = $currentDate->modify('+'.$increase.' days');
		// }
		// if ($current_day < $arrival_day || $current_day == $arrival_day) {
		// 	$diff_ck = $arrival_day - $current_day; if ($diff_ck < 8) {
		// 		$increase = 8 - $diff_ck + $arrival_day;
		// 		$temparrival_date = $currentDate->modify('+'.$increase.' days');
		// 	}
		// }else{
		// 	$diff_ck = $current_day - $arrival_day; 
		// 	$increase = $arrival_day + $diff_ck + 8;
		// 	$temparrival_date = $currentDate->modify('+'.$increase.' days');
		// }
		// $arrival_date = $currentDate->format('m/d/y'); // use

	    // make arrival date
		// condition 1: 6 Month ahead from current date.
		// condition 2: 8 Days ahead from deperture date.
		// condition 3: 8 Days ahead from xml generate date.
		$arrival_date = gmarrivaldate($vsl_num);

	    // Create the XML structure
	    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="no"?><AsycudaWorld_Manifest/>');
	    $xml->addAttribute('id', '310611');

	    // Identification Segment
	    $identification_segment = $xml->addChild('Identification_segment');
	    $identification_segment->addChild('Voyage_number', "MSL/$msl_num/".date('Y'));
	    $identification_segment->addChild('Date_of_departure', $deperture_date);

	    // Customs Office Segment
	    $customs_office_segment = $identification_segment->addChild('Customs_office_segment');
	    $customs_office_segment->addChild('Code', $customcode);
	    // $customs_office_segment->addChild('Name', 'Custom House, Chattogram');

	    // General Segment
	    $general_segment = $xml->addChild('General_segment');

	    // Master Information
	    $general_segment->addChild('Master_information', "CAPT. $capt_name");

	    // Totals Segment
	    $totals_segment = $general_segment->addChild('Totals_segment');
	    $totals_segment->addChild('Total_number_of_bols', $total_bls);
	    $totals_segment->addChild('Total_numer_of_packages', $total_bls);
	    $totals_segment->addChild('Total_number_of_containers', '0');
	    $totals_segment->addChild('Total_gross_mass', $total_cargo_qty);

	    // Arrival Segment
	    $arrival_segment = $general_segment->addChild('Arrival_segment');
	    $arrival_segment->addChild('Date_of_arrival', $arrival_date);
	    $arrival_segment->addChild('Time_of_arrival');

	    // Departure Segment
	    $departure_segment = $general_segment->addChild('Departure_segment');
	    $departure_segment->addChild('Place_of_departure_code', $last_portcode);
	    // $departure_segment->addChild('Place_of_departure_name', 'Paranagua');

	    // Destination Segment
	    $destination_segment = $general_segment->addChild('Destination_segment');
	    $destination_segment->addChild('Place_of_destination_code', 'BDCGP');
	    // $destination_segment->addChild('Place_of_destination_name', 'Chittagong');

	    // Carrier Segment
	    $carrier_segment = $general_segment->addChild('Carrier_segment');
	    $carrier_segment->addChild('Carrier_code', $companyain);
	    // $carrier_segment->addChild('Carrier_name', 'MULTIPORT SHIPPING LIMITED');
	    // $carrier_segment->addChild('Carrier_address', "JEVCO M.K.PLAZA (5TH FLOOR),\nAGRABAD HIGHWAY EXCESS ROAD,\nHALISHAHAR, CHITTAGONG.");

	    // Shipping Agent Segment
	    $shipping_agent_segment = $general_segment->addChild('Shipping_Agent_segment');
	    $shipping_agent_segment->addChild('Shipping_Agent_code')->addChild('null');
	    $shipping_agent_segment->addChild('Shipping_Agent_name')->addChild('null');

	    // Transport Segment
	    $transport_segment = $general_segment->addChild('Transport_segment');
	    $transport_segment->addChild('Name_of_transporter', "MV. ".$vessel_name);
	    $transport_segment->addChild('Place_of_transporter')->addChild('null');

	    // Mode of Transport Segment
	    $mode_of_transport_segment = $transport_segment->addChild('Mode_of_transport_segment');
	    $mode_of_transport_segment->addChild('Code', '1');
	    // $mode_of_transport_segment->addChild('Name', 'Sea Transport');

	    // Nationality of Transport Segment
	    $nationality_of_transport_segment = $transport_segment->addChild('Nationality_of_transport_segment');
	    $nationality_of_transport_segment->addChild('Code', $vsl_nationalitycode);
	    // $nationality_of_transport_segment->addChild('Name', $vsl_nationality);

	    // Transporter Registration Segment
	    $transporter_registration_segment = $transport_segment->addChild('Transporter_registration_segment');
	    $transporter_registration_segment->addChild('Registration_number', $vsl_imo);
	    $transporter_registration_segment->addChild('Registration_date');

		$g_segment = $general_segment->addChild('General_segment');
	    // General Segment - Tonnage
	    $tonnage_segment = $g_segment->addChild('Tonnage_segment');
	    // $tonnage_segment = $general_segment->addChild('Tonnage_segment');
	    $tonnage_segment->addChild('Tonnage_gross', $vsl_grt);
	    $tonnage_segment->addChild('Tonnage_net', $vsl_nrt);

	    // Tonnage Last Discharge
	    $general_segment->addChild('Tonnage_last_discharge');

	    // Create a DOM document for indentation
	    $dom = new DOMDocument('1.0', 'UTF-8');
	    $dom->preserveWhiteSpace = false;
	    $dom->formatOutput = true;
	    $dom->loadXML($xml->asXML());

	    // Save the XML file with proper indentation
	    $xml_file = $path.'General_Segment.xml';
	    $dom->save($xml_file);
	    
	    $vessels_bl = mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' ");
	    $line_num = 0;

	    // Loop through BOL data
	    while ($bl_row = mysqli_fetch_assoc($vessels_bl)) {
	        // $line_num = $line_num + 1;
	        $bl_num = $bl_row['bl_num'];
	        $cargo_name = $bl_row['cargo_name'];
	        $cargo_qty = $bl_row['cargo_qty'];
	        $qty = $cargo_qty * 1000;
	        $shipper_name = $bl_row['shipper_name'];
	        $shipper_address = $bl_row['shipper_address'];
	        $importerid = $bl_row['receiver_name'];
	        $importer_bin = allData('bins', $importerid, 'bin');
	        $bankid = $bl_row['bank_name'];
	        $bank_bin = allData('bins', $bankid, 'bin');
	        $load_portid = $bl_row['load_port'];
	        $load_portcode = allData('loadport', $load_portid, 'port_code');

	        $desc_portId = $bl_row['desc_port'];
	        $desc_portcode = allData('loadport', $desc_portId, 'port_code');

	        $bldeperture_date = deperture_info($vsl_num, "deperture_date");

	        if ($desc_portcode != "BDCGP") {continue;}
	        $line_num = $line_num + 1;

	        // Start XML creation
	        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="no"?><AsycudaWorld_Manifest/>');
	        $xml->addAttribute('id', '27977244');

	        // Identification Segment
	        $identification_segment = $xml->addChild('Identification_segment');
	        $identification_segment->addChild("Voyage_number", "MSL/$msl_num/".date('Y'));
	        $identification_segment->addChild('Date_of_departure', $bldeperture_date);
	        $identification_segment->addChild('Bol_reference', $bl_num);

	        // Customs Office Segment
	        $customs_office_segment = $identification_segment->addChild('Customs_office_segment');
	        $customs_office_segment->addChild('Code', '301');
	        // $customs_office_segment->addChild('Name', 'Custom House, Chattogram');

	        // BOL specific Segment
	        $Bol_specific_segment = $xml->addChild('Bol_specific_segment');

	        // Add the BOL data to the XML here
	        $Bol_specific_segment->addChild('Line_number', $line_num);
	        $Bol_specific_segment->addChild('Sub_line_number', '');
	        $Bol_specific_segment->addChild('Status', 'HSE');
	        $Bol_specific_segment->addChild('Previous_document_reference')->addChild('null');
	        $Bol_specific_segment->addChild('Bol_Nature', '23');
	        $Bol_specific_segment->addChild('Unique_carrier_reference')->addChild('null');
	        $Bol_specific_segment->addChild('Total_number_of_containers', '0');
	        $Bol_specific_segment->addChild('Total_gross_mass_manifested', $qty);
	        $Bol_specific_segment->addChild('Volume_in_cubic_meters');
	        $Bol_specific_segment->addChild('Number_of_sub_bols', '0');

	        $Bol_type_segment = $Bol_specific_segment->addChild('Bol_type_segment');
	        $Bol_type_segment->addChild('Code', 'HSB');

	        $Exporter_segment = $Bol_specific_segment->addChild('Exporter_segment');
	        $Exporter_segment->addChild('Code')->addChild('null');
	        $Exporter_segment->addChild('Name', $shipper_name);
	        $Exporter_segment->addChild('Address', $shipper_address);

	        $Consignee_segment = $Bol_specific_segment->addChild('Consignee_segment');
	        $Consignee_segment->addChild('Code', $bank_bin);

	        $Notify_segment = $Bol_specific_segment->addChild('Notify_segment');
	        $Notify_segment->addChild('Code', $importer_bin);

	        $Place_of_loading_segment = $Bol_specific_segment->addChild('Place_of_loading_segment');
	        $Place_of_loading_segment->addChild('Code', $load_portcode);

	        $Place_of_unloading_segment = $Bol_specific_segment->addChild('Place_of_unloading_segment');
	        $Place_of_unloading_segment->addChild('Code', 'BDCGP');

	        $Packages_segment = $Bol_specific_segment->addChild('Packages_segment');
	        $Packages_segment->addChild('Package_type_code', $packages_codes);
	        $Packages_segment->addChild('Number_of_packages', '1');

	        $Shipping_segment = $Bol_specific_segment->addChild('Shipping_segment');
	        $Shipping_segment->addChild('Shipping_marks', '-');

	        $Goods_segment = $Bol_specific_segment->addChild('Goods_segment');
	        $Goods_segment->addChild('Goods_description', $cargo_name);

	        // Add the SCI segment inside Goods_segment
	        $SCI_segment = $Goods_segment->addChild('SCI');
	        $SCI_segment->addChild('code')->addChild('null');
	        $SCI_segment->addChild('description')->addChild('null');

	        // Add the Freight_segment
	        $Freight_segment = $Bol_specific_segment->addChild('Freight_segment');
	        $Freight_segment->addChild('Value');
	        $Currency = $Freight_segment->addChild('Currency');
	        $Currency->addChild('null');

	        // Add the Indicator_segment inside Freight_segment
	        $Indicator_segment = $Freight_segment->addChild('Indicator_segment');
	        $Indicator_segment->addChild('Code')->addChild('null');
	        $Indicator_segment->addChild('Name')->addChild('null');

	        // Add the Customs_segment
	        $Customs_segment = $Bol_specific_segment->addChild('Customs_segment');
	        $Customs_segment->addChild('Value');
	        $Currency = $Customs_segment->addChild('Currency');
	        $Currency->addChild('null');

	        // Add the Transport_segment
	        $Transport_segment = $Bol_specific_segment->addChild('Transport_segment');
	        $Transport_segment->addChild('Value');
	        $Currency = $Transport_segment->addChild('Currency');
	        $Currency->addChild('null');

	        // Add the Insurance_segment
	        $Insurance_segment = $Bol_specific_segment->addChild('Insurance_segment');
	        $Insurance_segment->addChild('Value');
	        $Currency = $Insurance_segment->addChild('Currency');
	        $Currency->addChild('null');

	        // Add the Seals_segment
	        $Seals_segment = $Bol_specific_segment->addChild('Seals_segment');
	        $Seals_segment->addChild('Number_of_seals');
	        $Marks_of_seals = $Seals_segment->addChild('Marks_of_seals');
	        $Marks_of_seals->addChild('null');
	        $Seals_segment->addChild('Sealing_party_code')->addChild('null');
	        $Seals_segment->addChild('Sealing_party_name')->addChild('null');

	        // Add the Information_segment
	        $Information_segment = $Bol_specific_segment->addChild('Information_segment');
	        $Information_segment->addChild('Information_part_a')->addChild('null');

	        // Add the Operations_segment
	        $Operations_segment = $Bol_specific_segment->addChild('Operations_segment');
	        $Operations_segment->addChild('Packages_remaining');
	        $Operations_segment->addChild('Gross_mass_remaining');

	        // Add the Location_segment inside Operations_segment
	        $Location_segment = $Operations_segment->addChild('Location_segment');
	        $Location_segment->addChild('Code')->addChild('null');
	        $Location_segment->addChild('Name')->addChild('null');
	        $Location_segment->addChild('Information')->addChild('null');

	        // Add the Onward_transport_segment inside Operations_segment
	        $Onward_transport_segment = $Operations_segment->addChild('Onward_transport_segment');

	        // Add the Transit_segment inside Onward_transport_segment
	        $Transit_segment = $Onward_transport_segment->addChild('Transit_segment');
	        $Transit_segment->addChild('Customs_office_code')->addChild('null');
	        $Transit_segment->addChild('Customs_office_name')->addChild('null');
	        $Transit_segment->addChild('Document_reference')->addChild('null');

	        // Add the Transhipment_segment inside Onward_transport_segment
	        $Transhipment_segment = $Onward_transport_segment->addChild('Transhipment_segment');
	        $Transhipment_segment->addChild('Transipment_location_code')->addChild('null');
	        $Transhipment_segment->addChild('Transhipment_location_name')->addChild('null');
	        $Transhipment_segment->addChild('Document_reference')->addChild('null');

	        // Add the Onward_carrier_segment inside Onward_transport_segment
	        $Onward_carrier_segment = $Onward_transport_segment->addChild('Onward_carrier_segment');
	        $Onward_carrier_segment->addChild('Code')->addChild('null');
	        $Onward_carrier_segment->addChild('Name')->addChild('null');

	        // Create a DOM document for indentation
	        $xml_string = $xml->asXML();  // Get the raw XML string
	        $dom = new DOMDocument('1.0', 'UTF-8');
	        $dom->preserveWhiteSpace = false;
	        $dom->formatOutput = true;
	        $dom->loadXML($xml_string);

	        // Save the XML file
	        // $xml_file = $path . $bl_num . '.xml';  // Absolute path
	        $xml_file = $path . sanitize_filename($bl_num) . '.xml';
	        $dom->save($xml_file);
	        echo "XML file '$bl_num.xml' generated successfully.</br>";
	    }

	    $db->close();

	    // checks all the files in that folder then creates zip
	    createzip($path, "igm_xml_format_MV. ".$vessel_name);
	    // move the zip file to downloadable folder
	    $sourceFile = $path."igm_xml_format_MV. ".$vessel_name.".zip";
	    $destinationFolder = "forwadings/auto_forwardings/".$msl_num.".MV. ".$vessel_name."/";
		$destinationFile = $destinationFolder . basename($sourceFile);
	    rename($sourceFile, $destinationFile);
	    // delete the igm folder
	    deleteIfEmpty($destinationFolder."igm");

	    // redirect to file
		header("location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	}


	// export global
	function export_forwading($vsl_num = 205, $purpose = "vessel_details"){
		GLOBAL $db,$my,$company,$forwading,$thisvessel; $filename = "";

		// for sof
	    // Step 3: Query to get receiver_name and total cargo_qty grouped
		$sql = "
		    SELECT receiver_name, SUM(cargo_qty) AS total_qty
		    FROM vessels_bl
		    WHERE vsl_num = ?
		    GROUP BY receiver_name
		    ORDER BY bl_num ASC
		";

		$stmt = $db->prepare($sql);
		$stmt->bind_param("s", $vsl_num);
		$stmt->execute();
		$result = $stmt->get_result();


		// Step 1: Check if any quantity has decimal
		$result->data_seek(0);
		$hasDecimal = false;

		while ($row = $result->fetch_assoc()) {
		    if (fmod((float)$row['total_qty'], 1) != 0.0) {
		        $hasDecimal = true;
		        break;
		    }
		}

		// Step 2: Reset and loop again
		$result->data_seek(0);
		// $consigneewisecargo = "";
		// // Step 4: Loop through each receiver_name
		// while ($row = $result->fetch_assoc()) {
		//     $receiver_name = $row['receiver_name'];
		//     // $total_qty = number_format($row['total_qty']); // format with comma
		//     // Format quantity with or without decimal
		//     if ($hasDecimal) {
		//         $total_qty = number_format($row['total_qty'], 3);
		//     } else {
		//         $total_qty = number_format($row['total_qty']);
		//     }

		//     // Step 5: Find the corresponding name from bins table
		//     $bin_sql = "SELECT name FROM bins WHERE id = ?";
		//     $bin_stmt = $db->prepare($bin_sql);
		//     $bin_stmt->bind_param("s", $receiver_name);
		//     $bin_stmt->execute();
		//     $bin_result = $bin_stmt->get_result();

		//     if ($bin_row = $bin_result->fetch_assoc()) {
		//         $name = $bin_row['name'];
		//         // ✅ Step 6: Check length and replace LIMITED if needed
		//         if (strlen($name) > 28) {
		//             $name = str_replace("LIMITED", "LTD", $name);
		//         }

		//         // // Pad name manually if not using tab stops in Word
        // 		// $name = str_pad($name, 40); // 40-character width name column

		//         // Step 6: Append to final string
		//         $consigneewisecargo .= "$name\tB/L QTTY \t$total_qty MT<w:br/>";
		//     }


		//   //  $bin_stmt->close();
		// }

		$consigneewisecargo = "";

		// ✅ [NEW] এই তিনটা লাইন একদম নতুন — সব নাম collect করার জন্য
		$names = [];            // Added: সব নাম store করার জন্য
		$total_qtys = [];       // Added: quantity store করার জন্য
		$replace_all = false;   // Added: একবার long name detect হলে সব replace হবে

		// Step 4: Loop through each receiver_name
		while ($row = $result->fetch_assoc()) {
		    $receiver_name = $row['receiver_name'];

		    // Format quantity with or without decimal
		    if ($hasDecimal) {
		        $total_qty = number_format($row['total_qty'], 3);
		    } else {
		        $total_qty = number_format($row['total_qty']);
		    }

		    // Step 5: Find the corresponding name from bins table
		    $bin_sql = "SELECT name FROM bins WHERE id = ?";
		    $bin_stmt = $db->prepare($bin_sql);
		    $bin_stmt->bind_param("s", $receiver_name);
		    $bin_stmt->execute();
		    $bin_result = $bin_stmt->get_result();

		    if ($bin_row = $bin_result->fetch_assoc()) {
		        $name = $bin_row['name'];

		        // ✅ [MODIFIED] এই condition টা ছিল তোমার code-এ।
		        // আগের মতোই আছে, শুধু এখন এখানে replace করার বদলে শুধু flag detect করছি।
		        if (strlen($name) > 28) {
		            $replace_all = true; // Added: replace flag true হবে
		        }

		        // ✅ [NEW] এখন replace করিনি, শুধু পরে use করার জন্য name ও qty রাখছি
		        $names[] = $name;        // Added
		        $total_qtys[] = $total_qty; // Added
		    }

		    $bin_stmt->close(); // আগেও comment করা ছিল, এখন activate করে রেখেছি
		}

		// ✅ [NEW LOOP] — পুরো লিস্ট second loop-এ process হচ্ছে
		foreach ($names as $i => $name) {
		    if ($replace_all) {
		        // Added: যদি একবার long name পাওয়া যায়, সব name-এ replace হবে
		        $name = str_replace("LIMITED", "LTD", $name);
		    } else {
		        // Original replace logic (unchanged), শুধু এখানে সরানো হয়েছে
		        if (strlen($name) > 28) {
		            $name = str_replace("LIMITED", "LTD", $name);
		        }
		    }

		    // Step 6: Append to final string (Original line)
		    $consigneewisecargo .= "$name\tB/L QTTY \t{$total_qtys[$i]} MT<w:br/>";
		}


		// Step 7: Close dbections
		// $stmt->close();
		// $db->close();
		// end for sof

        // lightdues
        $lightduesamount = formatIndianNumber($thisvessel['rawnrt']*10); //
		$lightduesamountinword=strtoupper(numberToWords($thisvessel['rawnrt']*10));//

        // vatchalan
		$whole = $thisvessel['rawnrt'] * 10; //
		$yearprev = date("Y") - 1;

		$chalan15foramount = $whole * 0.15;//
		$vat15 = formatIndianNumber($chalan15foramount);//
		$chalan15amount = formatIndianNumber($thisvessel['rawnrt']*10); //
		$chalan15amountinword=strtoupper(numberToWords($chalan15foramount));//

		// vatchalan10
		$vat10 = formatIndianNumber($whole * 0.10);//
		$vat10amount = formatIndianNumber($thisvessel['rawnrt']*10); //
		$vat10amountinword=strtoupper(numberToWords($thisvessel['rawnrt']));//


		// get vessel data
        $row1=mysqli_fetch_assoc(mysqli_query($db,"SELECT*FROM vessels WHERE id='$vsl_num'"));
        // $vessel = $row1['vessel_name']; 
        $msl_num = $row1['msl_num'];
        $sailed = dbtime($thisvessel['sailing_date'], "d.m.Y");

        if(!empty($thisvessel['arrived'])){//
        	$arrived = date('d.m.Y', strtotime($thisvessel['arrived']));//
        	$vsl_year = date('Y', strtotime($thisvessel['arrived']));//
        }//
        else{$arrived = ""; $vsl_year = date('Y');}//
        if(!empty($thisvessel['sailing_date'])){$sailing_date=date('d.m.Y',strtotime($thisvessel['sailing_date']));}//
        else{$sailing_date = "";$sailed = "";}//

        $arrived_day = date('l', strtotime($thisvessel['arrived']));//

        $year = date("Y"); $month = date("m"); $day = date("d"); $today = date("l"); // 
        $rotation = $thisvessel['rotation']; if(empty($rotation)){$rotation = date("Y")."/______";}//

        $rotation_2 = substr($rotation,7)."/".substr($rotation,0,-7);//]
        if(empty($rotation)){$rotation = date("Y")."/______"; $rotation_2 = "______/".date("Y");}//

        $lstdaynextmonth = date('t',strtotime('next month'));//
        $nextmonth = date('m', strtotime('+1 month', strtotime($thisvessel['arrived'])));//
        $inctxsaildate = $lstdaynextmonth.".".$nextmonth.".".$year;//

        // for general segment and igm purpose
        // deperture date according to bl
        $deperture_date = deperture_date($vsl_num);
        // modified arrival for igm
		$currentDate = new DateTime(); $currentDate->modify('+6 months');
		$arrivaldate_forigm = $currentDate->format('m/d/y'); // use

		$total_bls = mysqli_num_rows(mysqli_query($db, "SELECT * FROM vessels_bl WHERE vsl_num = '$vsl_num' "));
		$ttlqty = ttlcargoqty($vsl_num);
		$total_cargo_qty = formatInternationalNumber($ttlqty*1000);

        // the filename/forwading comes form infostore
        $exten = ".docx"; $filename = $forwading[$purpose];
    	
    	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor("forwadings/templets/".$company['templet']."/".$filename.$exten);
		
		// set pc forwading values
    	$templateProcessor->setValues(
			[
				"companyain" => $company['ain'],
				"companyname" => $company['companyname'],
				"companytelephone" => $company['telephone'],
				"companyaddress" => $company['address'],
				"companyemail" => $company['email'],
				"vsl_num" => $vsl_num,
				"msl_num" => $thisvessel['msl_num'],
				"vessel" => $thisvessel['vessel_name'],
				"arrived" => $arrived,
				"sailing_date" => $sailing_date,
				"sailed" => $sailed,
				"inctxsaildate" => $inctxsaildate,
				"rotation" => $rotation,
				"rotation_2" => $rotation_2,
				"stevedore" => $thisvessel['stevedore'],
				"vsl_imo" => $thisvessel['imo'],
				"vsl_year" => $vsl_year,
				"vsl_call_sign" => $thisvessel['callsign'],
				"vsl_mmsi_number" => $thisvessel['mmsi_number'],
				"vsl_class" => $thisvessel['class'],
				// "vsl_nationality" => $thisvessel['nationality'],
				// "vsl_nationalitycode" => $thisvessel['nationalitycode'],
				// "vsl_registry" => $thisvessel['registry'],
				"vsl_nationality" => strtoupper($thisvessel['nationality']),
				"vsl_nationalitycode" => strtoupper($thisvessel['nationalitycode']),
				"vsl_registry" => strtoupper($thisvessel['registry']),
				"vsl_official_number" => $thisvessel['official_number'],
				"vsl_grt" => $thisvessel['grt'],
				"vsl_nrt" => $thisvessel['nrt'],
				"vsl_dead_weight" => $thisvessel['dead_weight'],
				"vsl_breth" => $thisvessel['breth'],
				"vsl_depth" => $thisvessel['depth'],
				"vsl_loa" => $thisvessel['loa'],
				"vsl_owner_name" => $thisvessel['owner_name'],
				"vsl_owner_address" => $thisvessel['owner_address'],
				"vsl_owner_email" => $thisvessel['owner_email'],
				"vsl_operator_name" => $thisvessel['operator_name'],
				"vsl_operator_address" => $thisvessel['operator_address'],
				"year_of_built" => $thisvessel['year_of_built'],
				"number_of_hatches_cranes" => $thisvessel['number_of_hatches_cranes'],
				"vsl_nature" => $thisvessel['nature'],
				"shipper_name" => $thisvessel['shipper_name'],
				"shipper_address" => $thisvessel['shipper_address'],
				"last_portcode" => $thisvessel['last_portcode'],
				"last_port" => $thisvessel['last_port'],
				"capt_name" => $thisvessel['capt_name'],
				"number_of_crew" => $thisvessel['number_of_crew'],
				"with_retention" => $thisvessel['with_retention'],
				"next_port" => strtoupper($thisvessel['next_port']),
				"year" => $year,
				"month" => $month,
				"day" => $day,
				"today" => $today,
				"arrived_day" => $arrived_day,
				"vsl_cargo" => $thisvessel['cargo'],
				"vsl_cargo_name" => $thisvessel['cargo_name'],
				"vsl_cargo_qty" => $thisvessel['cargo_qty'],
				"vsl_pni" => $thisvessel['pni'],
	    		"rep_goodname" => $thisvessel['rep_name'],
	    		"rep_contact" => $thisvessel['rep_contact'],
	    		"lightduesamount" => $lightduesamount,
	    		"lightduesamountinword" => $lightduesamountinword,
	    		"vat15" => $vat15,
	    		"chalan15amount" => $chalan15amount,
	    		"chalan15amountinword" => $chalan15amountinword,
	    		"vat10" => $vat10,
	    		"vat10amount" => $vat10amount,
	    		"vat10amountinword" => $vat10amountinword,
	    		"yearprev" => $yearprev,
	    		"consigneewisecargo" => $consigneewisecargo,
	    		// for igm{generalsegment}
	    		"deperture_date" => $deperture_date,
	    		"arrivaldate_forigm" => $arrivaldate_forigm,
	    		"total_bls" => $total_bls,
	    		"total_cargo_qty" => $total_cargo_qty
			]
		); 

    	$new_filename = $vsl_num.".MV. ".$thisvessel['vessel_name']." ".$filename;
		$path = "forwadings/auto_forwardings/".$msl_num.".MV. ".$thisvessel['vessel_name']."/";
		// vessel name
		$renm = " of ".$msl_num.".MV. ".$thisvessel['vessel_name'];
		// $save = $path.$new_filename.$exten;
		// if ($purpose == "softemplet") { $save = $path.$new_filename.$renm.$exten; }
		// else{$save = $path.$filename.$renm.$exten;}
		$save = $path.$filename.$renm.$exten;
		
		// Create folder if not exist
		createpath($path);
		// save file
		$templateProcessor->saveAs($save);

		// Check if the file exists
		header("location: vessel_details.php?forwadingpage=$vsl_num#downloads");
	}
?>