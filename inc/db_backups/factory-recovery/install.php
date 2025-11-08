<?php
	$host = $username = $password = $database = $msg = "";
	// Err message
	function alertMsg($msg = "Error Message Here", $type = "success"){
		$val = "<div class=\"alert alert-$type\" role=\"alert\">$msg</div>"; return $val;
	}

	if (isset($_POST['install'])) {
		// grab the value from user data
		$host = $_POST['host']; $username = $_POST['username']; $password = $_POST['password'];
		$database = $_POST['database'];

		// database connection without database name
		// check if database exist
	 	$mysqli = @new mysqli($host, $username, $password, $database);
	 	if ($mysqli->connect_error) {
	 		// if not exist, connect without database
	        $db = mysqli_connect($host, $username, $password);
	    }else{
	    	// if exist, connect to database
	    	$db = mysqli_connect($host, $username, $password, $database);
		}

		// if can't connect, die and show error
		if ($db->connect_error) { die("Connection failed: " . $db->connect_error); } 

		//Drop Database if exist
		$sql = "DROP DATABASE IF EXISTS ".$database;
		if ($db->query($sql) === TRUE){echo "Database Dropped <br/>";} 
		else {echo "Database Not Droped <br/>". $db->error;}

		// Create database
		$sql = "CREATE DATABASE ".$database;
		if ($db->query($sql) === TRUE) {echo "Database created successfully <br/>";} 
		else {echo "Error creating database: <br/>" . $db->error;}

		// connection close
		$db->close();

		// again connect to database for new connection and restore backup
		$db = mysqli_connect($host, $username, $password, $database);
		// prepare backup files
		// scan folder for all files
		$sourceFolderPath = 'inc/db_backups/factory-recovery';
		// Get the list of all files and directories in the folder
		$files = scandir($sourceFolderPath); foreach ($files as $file) {
		    // Use pathinfo to get the file extension
		    $fileInfo = pathinfo($file); if ($file == "." || $file == "..") { continue; }
		}

		// filter sql files
		// Loop through the files and filter for only sql files (to input in database)
		$allowedExtensions = array('sql');
		foreach ($files as $file) {
		    // Use pathinfo to get the file extension
		    $fileInfo = pathinfo($file);
		    // Check if the "extension" key exists and if it's in the list of allowed extensions
		    if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $allowedExtensions)) { $sqlFiles[] = $file; }
		}

		// get the sqlfile name {gets the last value of an array}
		$sqlFileName = end($sqlFiles);

		// restore the database sql file
		$filePath = $sourceFolderPath."/$sqlFileName";
		// restore database
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
			if ($error) { $msg = alertMsg($error, "danger"); } 
			// delete files after installation after restore
			else{
				$errcount = 0;
				// delete backup from file
				if (!unlink($filePath)) { $errcount++;
					$msg = alertMsg("Could Not Delete Database File!", "danger");
				}else{
					// now create the conn file based on userdata for database connection
					// Prepare the connection script content
			        $fileContent = "<?php\n";
			        $fileContent .= "\$host = '$host';\n";
			        $fileContent .= "\$username = '$username';\n";
			        $fileContent .= "\$password = '$password';\n";
			        $fileContent .= "\$database = '$database';\n\n";
			        $fileContent .= "\$db = new mysqli(\$host, \$username, \$password, \$database);\n\n";
			        $fileContent .= "if(\$db->connect_error) {die('Connection failed: ' . \$db->connect_error);}\n";
			        $fileContent .= "?>";

			        // Define the file path where the connection file will be created
			        $conFilePath = 'inc/conn.php';

			        // Create the new PHP file
			        if (file_put_contents($conFilePath, $fileContent)){echo "File: 'conn.php' Created Successfully!";} 
			        else {echo "Error: Unable to create connection file.";}
				}
				// delete Installation file
				if (!unlink("install.php")) { $errcount++;
					$msg = alertMsg("Could Not Delete Installation File!", "danger");
				}
				if ($errcount == 0) {
					// redirect to multiport website
					header("location: index.php");
				}else{$msg = alertMsg("Some Wrong Happened!", "danger");}
			}
		}
		$db->close(); // close database connection
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
	<style>
		.gg-add {
			box-sizing: border-box;
			position: relative;
			display: block;
			width: 22px;
			height: 22px;
			border: 2px solid;
			transform: scale(var(--ggs,1));
			border-radius: 22px
		}

		.gg-add::after,
		.gg-add::before {
			content: "";
			display: block;
			box-sizing: border-box;
			position: absolute;
			width: 10px;
			height: 2px;
			background: currentColor;
			border-radius: 5px;
			top: 8px;
			left: 4px
		}

		.gg-add::after {
			width: 2px;
			height: 10px;
			top: 4px;
			left: 8px
		} 
		html, body
		{height: 100%;}
		.container {height: 100%; position: relative;}

		.center {
			width: 35%;
			margin: 0;
			position: absolute;
			top: 50%;
			left: 50%;
			-ms-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}
		.custom{
			width: 100%;
			border: 1px solid #CED4DA;
			border-radius: 10px;
			padding: 30px;
			/*box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);*/
			box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
		}
		.shadowfont{
			border: 1px solid #CED4DA;
			color: white;
			box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="center">
			<?php echo $msg; ?>
			
			<form class="custom" method="post" action="install.php">
				<h2 style="color: #92757D;">Install Database</h2>
                
                    <div class="form-group">
                      <label for="exampleInputEmail1">Host</label>
                      <input type="text" name="host" class="form-control" value="localhost" placeholder="localhost" required>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Username</label>
                      <input type="text" name="username" class="form-control" value="root" placeholder="Database Username" required>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Password</label>
                      <input type="text" name="password" class="form-control" value="" placeholder="Database Password">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Database</label>
                      <input type="text" name="database" class="form-control" value="multiport" placeholder="Database Name" required>
                    </div>
                
                <div class="modal-footer">
                  <button type="submit" name="install" class="btn btn-secondary">Install</button>
                </div>
            </form>
		</div>
	</div>
</body>
</html>