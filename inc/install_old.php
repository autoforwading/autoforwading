<?php
	$host = $username = $password = $database = $msg = "";
	// Err message
	function alertMsg($msg = "Error Message Here", $type = "success"){
		$val = "<div class=\"alert alert-$type\" role=\"alert\">$msg</div>"; return $val;
	}

	if (isset($_POST['install'])) {
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

		
		if ($db->connect_error) {
			die("Connection failed: " . $db->connect_error);
		} 

		//Drop Database
		$sql = "DROP DATABASE IF EXISTS ".$database;
		if ($db->query($sql) === TRUE) {
			echo "Database Dropped <br/>";
		} else {
			echo "Database Not Droped <br/>". $db->error;
		}
		// Create database
		$sql = "CREATE DATABASE ".$database;
		if ($db->query($sql) === TRUE) {
			echo "Database created successfully <br/>";
		} else {
			echo "Error creating database: <br/>" . $db->error;
		}

		$db = mysqli_connect($host, $username, $password, $database);
		$sql = "CREATE TABLE IF NOT EXISTS `backups` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`file` varchar(255) NOT NULL,
			`date` varchar(100) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

		if ($db->multi_query($sql) === TRUE) {
			echo "Tables created successfully <br/>";
		} else {
			echo "Error creating table: <br/>" . $db->error;
		}


		// prepare backup files
		// scan folder for all files
		$sourceFolderPath = 'inc/db_backups';
		// Get the list of all files and directories in the folder
		$files = scandir($sourceFolderPath); foreach ($files as $file) {
		    // Use pathinfo to get the file extension
		    $fileInfo = pathinfo($file);
		    if ($file == "." || $file == "..") { continue; }
		    // echo $file."</br>";
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

		// insert sql files to database
		// loop through all sql file and insert into database
		foreach ($sqlFiles as $sqlFileName) {
			mysqli_query($db, "INSERT INTO backups(file,date)VALUES('$sqlFileName', '')");
		}		
		// $db->close();
	}

	if (isset($_POST['restore_database'])) {
		$id = $_POST['restore_database'];
		$host = $_POST['host']; $username = $_POST['username']; $password = $_POST['password'];
		$database = $_POST['database'];
		$db = mysqli_connect($host, $username, $password, $database);

		$run = mysqli_query($db, "SELECT * FROM backups WHERE id = '$id' "); 
		if(mysqli_num_rows($run)>0){$row=mysqli_fetch_assoc($run);$file=$row['file'];}
		// delete backup from database
		$delbackupdatabase = mysqli_query($db, "DELETE FROM backups WHERE id = '$id' ");

		// delete backup table
		mysqli_query($db, "DROP TABLE `backups` ");
		

		$filePath = "inc/db_backups/$file";
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
			<?php if(!isset($_GET['step']) || isset($_GET['step']) && $_GET['step'] == 1){ ?>
			<form class="custom" method="post" action="install.php?step=2">
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
        	<?php }else{ ?>
        	<div class="table-responsive shadow custom"> 
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col" style="text-align: center;">File</th>
                      <!-- <th scope="col">Restore</th> -->
                      <th scope="col" style="text-align: center;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  	<form method="post" action="install.php?step=2">
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
                    <?php
                    // $db = mysqli_connect("localhost", "root", "", "multiport"); 
                    $sql = "SELECT * FROM backups ORDER BY id DESC";
					$run = mysqli_query($db, $sql); while ($row = mysqli_fetch_assoc($run)) {
						$id = $row['id']; $file = $row['file']; $date = date('d-m-Y', strtotime($row['date']));

						echo "
							<tr>
								<td>$file</td>
								<th>
									<button 
										name=\"restore_database\" 
										value=\"$id\"
										class=\"btn btn-outline-secondary\"
									>Restore</button>
								</th>
			                </tr>
						";
					}
                    ?>
                	</form>
                  </tbody>
                </table>
              </div>
        	<?php } ?>
		</div>
	</div>
</body>
</html>